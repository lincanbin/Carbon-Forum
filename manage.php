<?php
require(dirname(__FILE__)."/common.php");

$IsApp = true;
$TemplatePath = dirname(__FILE__) .'/styles/api/template/';
$Style = 'API';
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

Auth(4);
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
				# code...
				break;
			
			default:
				AlertMsg('Bad Request','Bad Request');
				break;
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