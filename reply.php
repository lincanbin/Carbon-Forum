<?php
include(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/reply.php');
SetStyle('api', 'API');
Auth(1, 0, true);

$Error   = '';
$TopicID = intval(Request('Post', 'TopicID'));
$Content = '';

$Topic = $DB->row("SELECT * FROM " . $Prefix . "topics Where ID=?", array(
	$TopicID
));
if (!$Topic || ($Topic['IsDel'] && $CurUserRole < 3)) {
	AlertMsg('404 NOT FOUND', '404 NOT FOUND', 404);
} else if ($Topic['IsLocked'] && $CurUserRole < 3) {
	AlertMsg($Lang['Topic_Has_Been_Locked'], $Lang['Topic_Has_Been_Locked']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!ReferCheck($_POST['FormHash'])) {
		AlertMsg($Lang['Error_Unknown_Referer'], $Lang['Error_Unknown_Referer'], 403);
	}
	if (($TimeStamp - $CurUserInfo['LastPostTime']) <= 5) { //发帖至少要间隔5秒
		AlertMsg($Lang['Posting_Too_Often'], $Lang['Posting_Too_Often']);
	}
	$Content = Request('Post', 'Content');
	if ($Content) {
		if (strlen($Content) <= $Config['MaxPostChars']) {
			//往Posts表插入数据
			$PostData      = array(
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
			$NewPostResult = $DB->query("INSERT INTO `" . $Prefix . "posts`(`ID`, `TopicID`, `IsTopic`, `UserID`, `UserName`, `Subject`, `Content`, `PostIP`, `PostTime`, `IsDel`) VALUES (:ID,:TopicID,:IsTopic,:UserID,:UserName,:Subject,:Content,:PostIP,:PostTime,:IsDel)", $PostData);
			
			$PostID = $DB->lastInsertId();
			
			if ($NewPostResult) {
				//更新全站统计数据
				$NewConfig = array(
					"NumPosts" => $Config["NumPosts"] + 1,
					"DaysPosts" => $Config["DaysPosts"] + 1
				);
				UpdateConfig($NewConfig);
				//更新主题统计数据
				$DB->query("UPDATE `" . $Prefix . "topics` SET Replies=Replies+1,LastTime=?,LastName=? WHERE `ID`=?", array(
					($TimeStamp > $Topic['LastTime']) ? $TimeStamp : $Topic['LastTime'],
					$CurUserName,
					$TopicID
				));
				//更新用户自身统计数据
				UpdateUserInfo(array(
					"Replies" => $CurUserInfo['Replies'] + 1,
					"LastPostTime" => $TimeStamp
				));
				//标记附件所对应的帖子标签
				$DB->query("UPDATE `" . $Prefix . "upload` SET PostID=? WHERE `PostID`=0 and `UserName`=?", array(
					$PostID,
					$CurUserName
				));
				//添加提醒消息
				AddingNotifications($Content, $TopicID, $PostID, $Topic['UserName']);
				if ($CurUserID != $Topic['UserID']) {
					$DB->query('INSERT INTO `' . $Prefix . 'notifications`(`ID`, `UserID`, `UserName`, `Type`, `TopicID`, `PostID`, `Time`, `IsRead`) VALUES (null,?,?,?,?,?,?,?)', array(
						$Topic['UserID'],
						$CurUserName,
						1,
						$TopicID,
						$PostID,
						$TimeStamp,
						0
					));
					$DB->query('UPDATE `' . $Prefix . 'users` SET `NewMessage` = `NewMessage`+1 WHERE ID = :UserID', array(
						'UserID' => $Topic['UserID']
					));
					//清理内存缓存
					if ($MCache) {
						$MCache->delete(MemCachePrefix . 'UserInfo_' . $Topic['UserID']);
					}
				}
				if ($MCache) {
					//清理首页内存缓存
					$MCache->delete(MemCachePrefix . 'Homepage');
					//清理主题缓存
					$MCache->delete(MemCachePrefix . 'Topic_' . $TopicID);
				}
				//跳转到主题页
				//计算页数，跳转到准确页数
				$TotalPage = ceil(($Topic['Replies'] + 2) / $Config['PostsPerPage']);
				//header('location: '.$Config['WebsitePath'].'/t/'.$TopicID);
			}
		} else {
			$Error = str_replace('{{MaxPostChars}}', $Config['MaxPostChars'], $Lang['Too_Long']);
		}
	} else {
		$Error = $Lang['Content_Empty'];
	}
}
$DB->CloseConnection();

// 页面变量
$PageTitle   = 'Reply';
$ContentFile = $TemplatePath . 'reply.php';
include($TemplatePath . 'layout.php');