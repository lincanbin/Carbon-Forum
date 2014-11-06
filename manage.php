<?php
require(dirname(__FILE__)."/common.php");

$IsApp = true;
$TemplatePath = dirname(__FILE__) .'/styles/api/template/';
$Style = 'API';
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$ID = intval(Request('POST','ID',0));
$Type = intval(Request('POST','Type',0));//1:Topic,2:Post,3:User
$Action = Request('POST','Action',false);

switch ($Type) 
{
//Topic
	case 1:
	$TopicInfo = $DB->row("SELECT * FROM ".$Prefix."topics force index(PRI) Where ID=:ID",array("ID"=>$ID));
	if(!$TopicInfo)
	{
		AlertMsg('Topic Not Found','Topic Not Found');
	}
		switch ($Action)
		{
			//将主题移动至回收站
			case 'Delete':
				Auth(4);
				if($TopicInfo['IsDel']==0){
					$DB->query("UPDATE ".$Prefix."topics SET IsDel = 1 Where ID=:ID",array("ID"=>$ID));
					//更新全站统计数据
					$NewConfig = array(
						"NumTopics" => $Config["NumTopics"]-1
						);
					UpdateConfig($NewConfig);
					//更新用户自身统计数据
					$DB->query("UPDATE `".$Prefix."users` SET Topics=Topics-1 WHERE `ID`=?",array($TopicInfo['UserID']));
					//更新标签统计
					if($TopicInfo['Tags'])
					{
						$DB->query("UPDATE `".$Prefix."tags` SET TotalPosts=TotalPosts-1 WHERE `Name` in (?)",explode('|', $TopicInfo['Tags']));
					}
					$Message = '成功将主题移动至回收站';
				}else{
					AlertMsg('Bad Request','帖子已进入回收站，请勿重复操作');
				}
				break;
			//从回收站恢复主题
			case 'Recover':
				Auth(4);
				if($TopicInfo['IsDel']==1){
					$DB->query("UPDATE ".$Prefix."topics SET IsDel = 0 Where ID=:ID",array("ID"=>$ID));
					//更新全站统计数据
					$NewConfig = array(
						"NumTopics" => $Config["NumTopics"]+1
						);
					UpdateConfig($NewConfig);
					//更新用户自身统计数据
					$DB->query("UPDATE `".$Prefix."users` SET Topics=Topics+1 WHERE `ID`=?",array($TopicInfo['UserID']));
					//更新标签统计
					if($TopicInfo['Tags'])
					{
						$DB->query("UPDATE `".$Prefix."tags` SET TotalPosts=TotalPosts+1 WHERE `Name` in (?)",explode('|', $TopicInfo['Tags']));
					}
					$Message = '成功将主题从回收站取回';
				}else{
					AlertMsg('Bad Request','恢复失败，请确认帖子在回收站');
				}
				break;
			//永久删除主题（需要先将主题移动至回收站）
			case 'PermanentlyDelete':
				Auth(4);
				if($TopicInfo['IsDel']==1){
					$DB->query('DELETE FROM `'.$Prefix.'posttags` WHERE TopicID=?',array($ID));
					$DB->query('DELETE FROM `'.$Prefix.'posts` WHERE TopicID=?',array($ID));
					$DB->query('DELETE FROM `'.$Prefix.'topics` WHERE ID=?',array($ID));
					$DB->query('DELETE FROM `'.$Prefix.'notifications` WHERE TopicID=?',array($ID));
					$Message = '成功将主题永久删除';
				}else{
					AlertMsg('Bad Request','请确认帖子在回收站');
				}
				break;

			default:
				AlertMsg('Bad Request','Bad Request');
				break;
		}
		break;
//Post
	case 2:
	$PostInfo = $DB->row("SELECT * FROM ".$Prefix."posts force index(PRI) Where ID=:ID",array("ID"=>$ID));
	if(!$PostInfo)
	{
		AlertMsg('Post Not Found','Post Not Found');
	}
		switch ($Action)
		{
			case 'Delete':
				Auth(4);
				$DB->query('DELETE FROM `'.$Prefix.'posts` WHERE ID=?',array($ID));
				$DB->query('DELETE FROM `'.$Prefix.'notifications` WHERE PostID=?',array($ID));
				//更新全站统计数据
				$NewConfig = array(
					"NumPosts" => $Config["NumPosts"]-1
					);
				UpdateConfig($NewConfig);
				//更新主题统计数据
				$DB->query("UPDATE `".$Prefix."topics` SET Replies=Replies-1 WHERE `ID`=?",array($PostInfo['TopicID']));
				//更新用户自身统计数据
				$DB->query("UPDATE `".$Prefix."users` SET Replies=Replies-1 WHERE `ID`=?",array($PostInfo['UserID']));
				$Message = '成功删除帖子';
				break;

			default:
				AlertMsg('Bad Request','Bad Request');
				break;
		}
		break;
//User
	case 3:
		switch ($Action)
		{
			case 'Delete':
				Auth(4);
				# code...
				break;
			
			default:
				AlertMsg('Bad Request','Bad Request');
				break;
		}
		break;
//Follow or Favorite
	case 4:
		Auth(1);
		$Action = intval($Action);
		//检查主题/标签/用户/帖子是否存在
		//添加合适索引：TODO
		$IsFavorite = $DB->single("SELECT ID FROM ".$Prefix."favorites Where UserID=:UserID and Type=:Type and FavoriteID=:FavoriteID",array('UserID'=>$CurUserID, 'Type'=>$Action, 'FavoriteID'=>$ID));//添加索引
		switch ($Action) {
			//1:Topic 2:Tag 3:User 4:Post
			case 1:
				$Title = $DB->single("SELECT Topic FROM ".$Prefix."topics Where ID=:FavoriteID",array('FavoriteID'=>$ID));
				break;
			case 2:
				$Title = $DB->single("SELECT Name FROM ".$Prefix."tags Where ID=:FavoriteID",array('FavoriteID'=>$ID));
				break;
			case 3:
				$Title = $DB->single("SELECT UserName FROM ".$Prefix."users Where ID=:FavoriteID",array('FavoriteID'=>$ID));
				break;
			case 4:
				$Title = $DB->single("SELECT Subject FROM ".$Prefix."posts Where ID=:FavoriteID",array('FavoriteID'=>$ID));
				break;
			default:
				AlertMsg('Bad Request','Bad Request');
				break;
		}
		if($Title)
		{
			if(!$IsFavorite){
				$DB->query('INSERT INTO `'.$Prefix.'favorites`(`ID`, `UserID`, `Category`, `Title`, `Type`, `FavoriteID`, `DateCreated`, `Description`) VALUES (?,?,?,?,?,?,?,?)',array(null, $CurUserID, '', $Title, $Action, $ID, $TimeStamp, ''));
				$Message = '收藏成功';
			}else{
				$DB->query('DELETE FROM `'.$Prefix.'favorites` WHERE `UserID`=? and `Type`=? and `FavoriteID`=?',array($CurUserID, $Action, $ID));
				$Message = '取消收藏成功';
			}

		}else{
			AlertMsg('404 Not Found','404 Not Found');
		}
		
		
		
		break;
//Error
	default:
		AlertMsg('Bad Request','Bad Request');
		break;
}
$PageTitle = '论坛管理';
$ContentFile = $TemplatePath.'manage.php';
include($TemplatePath.'layout.php');
?>