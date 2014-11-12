<?php
include(dirname(__FILE__) . '/common.php');

SetStyle('api','API');
Auth(1,0,true);

$Error = '';
$TopicID = intval(Request('Post','TopicID'));
$Content = '';

$Topic = $DB->row("SELECT * FROM ".$Prefix."topics Where ID=?",array($TopicID));
if(!$Topic || ($Topic['IsDel'] && $CurUserRole<3))
{
	AlertMsg('帖子不存在','帖子不存在');
}else if($Topic['IsLocked'] && $CurUserRole<3){
	AlertMsg('此帖已被锁定','此帖已被锁定，禁止回复');
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!ReferCheck($_POST['FormHash'])) {
		AlertMsg('来源错误','来源错误(unknown referer)',403);
	}
	$Content = Request('Post','Content');
	if($Content){
		if(strlen($Content) <= $Config['MaxPostChars']){
			//往Posts表插入数据
			$PostData = array(
				"ID" => null, 
				"TopicID" => $TopicID, 
				"IsTopic" => 0, 
				"UserID" => $CurUserID, 
				"UserName" => $CurUserName, 
				"Subject" => $Topic['Topic'], 
				"Content" => XssEscape($Content),
				"PostIP" => $CurIP, 
				"PostTime" => $TimeStamp,
				"IsDel" => 0
				);
			$NewPostResult = $DB->query("INSERT INTO `".$Prefix."posts`(`ID`, `TopicID`, `IsTopic`, `UserID`, `UserName`, `Subject`, `Content`, `PostIP`, `PostTime`, `IsDel`) VALUES (:ID,:TopicID,:IsTopic,:UserID,:UserName,:Subject,:Content,:PostIP,:PostTime,:IsDel)",$PostData);

			$PostID = $DB->lastInsertId();

			if($NewPostResult)
			{
				//更新全站统计数据
				$NewConfig = array(
					"NumPosts" => $Config["NumPosts"]+1
					);
				UpdateConfig($NewConfig);
				//更新主题统计数据
				$DB->query("UPDATE `".$Prefix."topics` SET Replies=Replies+1,LastTime=?,LastName=? WHERE `ID`=?",array($TimeStamp, $CurUserName, $TopicID));
				//更新用户自身统计数据
				$DB->query("UPDATE `".$Prefix."users` SET Replies=Replies+1,LastPostTime=? WHERE `ID`=?",array($TimeStamp, $CurUserID));
				//标记附件所对应的帖子标签
				$DB->query("UPDATE `".$Prefix."upload` SET PostID=? WHERE `PostID`=0 and `UserName`=?",array($PostID, $CurUserName));
				//添加提醒消息
				AddingNotifications($Content, $TopicID, $PostID, $Topic['UserName']);
				if($CurUserID != $Topic['UserID']){
					$DB->query('INSERT INTO `'.$Prefix.'notifications`(`ID`, `UserID`, `UserName`, `Type`, `TopicID`, `PostID`, `Time`, `IsRead`) VALUES (null,?,?,?,?,?,?,?)',array($Topic['UserID'], $CurUserName, 1, $TopicID, $PostID, $TimeStamp, 0));
					$DB->query('UPDATE `'.$Prefix.'users` SET `NewMessage` = `NewMessage`+1 WHERE ID = :UserID',array('UserID' => $Topic['UserID']));
				}
				//跳转到主题页
				//计算页数，跳转到准确页数
				$TotalPage = ceil($Topic['Replies']+1/$Config['PostsPerPage']);
				//header('location: '.$Config['WebsitePath'].'/t/'.$TopicID);
			}
		}else{
			$Error = '内容长度不能超过'.$Config['MaxPostChars'].'个字节';
		}
	}else{
		$Error = '内容不能为空';
	}
}
$DB->CloseConnection();

// 页面变量
$PageTitle = '回复帖子';
$ContentFile = $TemplatePath.'reply.php';
include($TemplatePath.'layout.php');
?>