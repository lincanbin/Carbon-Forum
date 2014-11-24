<?php
require(dirname(__FILE__)."/common.php");
require(dirname(__FILE__).'/language/'.ForumLanguage.'/manage.php');
SetStyle('api','API');

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
					$Message = '成功还原主题';
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
					$Message = '成功永久删除';
				}else{
					AlertMsg('Bad Request','请确认帖子在回收站');
				}
				break;
			//主题下沉（LastTime-30*86400）
			case 'Sink':
				Auth(4);
				$DB->query("UPDATE ".$Prefix."topics SET LastTime = LastTime-2592000 Where ID=:ID",array("ID"=>$ID));
				$Message = '下沉成功';
				break;
			//主题上浮（LastTime+30*86400）
			case 'Rise':
				Auth(4);
				$DB->query("UPDATE ".$Prefix."topics SET LastTime = LastTime+2592000 Where ID=:ID",array("ID"=>$ID));
				$Message = '上浮成功';
				break;
			//主题锁定
			case 'Lock':
				Auth(4);
				$DB->query("UPDATE ".$Prefix."topics SET IsLocked = :IsLocked Where ID=:ID",array("ID"=>$ID, "IsLocked"=>$TopicInfo['IsLocked']?0:1));
				$Message = $TopicInfo['IsLocked']?'解锁成功':'成功锁定';
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
		$IsFavorite = $DB->single("SELECT ID FROM ".$Prefix."favorites Where UserID=:UserID and Type=:Type and FavoriteID=:FavoriteID",array('UserID'=>$CurUserID, 'Type'=>$Action, 'FavoriteID'=>$ID));//添加索引
		$MessageType=false;//false表示收藏，true表示关注
		$SQLAction = intval($IsFavorite)?'-1':'+1';
		switch ($Action) {
			//1:Topic 2:Tag 3:User 4:Post 5:Blog
			case 1://Topic
				$Title = $DB->single("SELECT Topic FROM ".$Prefix."topics Where ID=:FavoriteID",array('FavoriteID'=>$ID));
				break;
			case 2://Tag
				$Title = $DB->single("SELECT Name FROM ".$Prefix."tags Where ID=:FavoriteID",array('FavoriteID'=>$ID));
				$MessageType=true;
				break;
			case 3://User
				$Title = $DB->single("SELECT UserName FROM ".$Prefix."users Where ID=:FavoriteID",array('FavoriteID'=>$ID));
				$MessageType=true;
				break;
			case 4://Post
				$Title = $DB->single("SELECT Subject FROM ".$Prefix."posts Where ID=:FavoriteID",array('FavoriteID'=>$ID));
				break;
			case 5://Blog
				$Title = $DB->single("SELECT Subject FROM ".$Prefix."blogs Where ID=:FavoriteID and ParentID=0",array('FavoriteID'=>$ID));
				break;
			default:
				AlertMsg('Bad Request','Bad Request');
				break;
		}
		if($Title)
		{
			if(!$IsFavorite){
				if(!$DB->query('INSERT INTO `'.$Prefix.'favorites`(`ID`, `UserID`, `Category`, `Title`, `Type`, `FavoriteID`, `DateCreated`, `Description`) VALUES (?,?,?,?,?,?,?,?)',array(null, $CurUserID, '', $Title, $Action, $ID, $TimeStamp, '')))
					AlertMsg('Unknown Error','Unknown Error');
			}else{
				$DB->query('DELETE FROM `'.$Prefix.'favorites` WHERE `UserID`=? and `Type`=? and `FavoriteID`=?',array($CurUserID, $Action, $ID));
			}
			switch ($Action) {
				//1:Topic 2:Tag 3:User 4:Post 5:Blog
				case 1://Topic
					$DB->query('UPDATE '.$Prefix.'topics SET Favorites = Favorites'.$SQLAction.' Where ID=:FavoriteID',array('FavoriteID'=>$ID));
					$DB->query('UPDATE `'.$Prefix.'users` SET NumFavTopics=NumFavTopics'.$SQLAction.' WHERE `ID`=?',array($CurUserID));
					break;
				case 2://Tag
					$DB->query('UPDATE '.$Prefix.'tags SET Followers = Followers'.$SQLAction.' Where ID=:FavoriteID',array('FavoriteID'=>$ID));
					$DB->query('UPDATE `'.$Prefix.'users` SET NumFavTags=NumFavTags'.$SQLAction.' WHERE `ID`=?',array($CurUserID));
					break;
				case 3://User
					$DB->query('UPDATE '.$Prefix.'users SET Followers = Followers'.$SQLAction.' Where ID=:FavoriteID',array('FavoriteID'=>$ID));
					$DB->query('UPDATE `'.$Prefix.'users` SET NumFavUsers=NumFavUsers'.$SQLAction.' WHERE `ID`=?',array($CurUserID));
					break;
				case 4://Post
					break;
				case 5://Blog
					break;
				default:
					AlertMsg('Bad Request','Bad Request');
					break;
			}
			$Message = $IsFavorite?($MessageType?'取消关注成功':'取消收藏成功'):($MessageType?'关注成功':'收藏成功');
			//$FavoriteID = $DB->lastInsertId();
		}else{
			AlertMsg('404 Not Found','404 Not Found');
		}
		
		
		
		break;
//Error
	default:
		AlertMsg('Bad Request','Bad Request');
		break;
}
$PageTitle = 'Manage';
$ContentFile = $TemplatePath.'manage.php';
include($TemplatePath.'layout.php');
?>