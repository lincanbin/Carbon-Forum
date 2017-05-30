<?php
require(LanguagePath . 'reply.php');
SetStyle('api', 'API');
Auth(1, 0, true);

$ErrorCodeList = require(LibraryPath . 'code/new.error.code.php');
$Error = '';
$ErrorCode = $ErrorCodeList['Default'];
$TopicID = intval(Request('Post', 'TopicID'));
$Content = '';

$Topic = $DB->row("SELECT * FROM " . PREFIX . "topics WHERE ID=?", array(
	$TopicID
));
if (!$Topic || ($Topic['IsDel'] && $CurUserRole < 3)) {
	AlertMsg('404 NOT FOUND', '404 NOT FOUND', 404);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!ReferCheck(Request('Post', 'FormHash'))) {
		AlertMsg($Lang['Error_Unknown_Referer'], $Lang['Error_Unknown_Referer'], 403);
	}
	do {
		if ($Topic['IsLocked'] && $CurUserRole < 3) { //被锁的帖子无法回复
			$Error = $Lang['Topic_Has_Been_Locked'];
			$ErrorCode = $ErrorCodeList['Topic_Has_Been_Locked'];
			break;
		}

		//发帖至少要间隔8秒
		if (DEBUG_MODE === false && ($CurUserRole < 3 && ($TimeStamp - intval($CurUserInfo['LastPostTime'])) <= intval($Config['PostingInterval']))) {
			$Error = $Lang['Posting_Too_Often'];
			$ErrorCode = $ErrorCodeList['Posting_Too_Often'];
			break;
		}


		$Content = Request('Post', 'Content');
		if (!$Content) {
			$Error = $Lang['Content_Empty'];
			$ErrorCode = $ErrorCodeList['Too_Long'];
			break;
		}


		if (strlen($Content) > $Config['MaxPostChars']) {
			$Error = str_replace('{{MaxPostChars}}', $Config['MaxPostChars'], $Lang['Too_Long']);
			$ErrorCode = $ErrorCodeList['Too_Long'];
			break;
		}


		// 内容过滤系统
		$ContentFilterResult = Filter($Content);
		$GagTime = $CurUserRole < 3 ? $ContentFilterResult['GagTime'] : 0;
		$Prohibited = $ContentFilterResult['Prohibited'];
		if ($Prohibited) {
			$Error = $Lang['Prohibited_Content'];
			$ErrorCode = $ErrorCodeList['Prohibited_Content'];
			if ($GagTime) {
				//禁言用户 $GagTime 秒
				UpdateUserInfo(array(
					"LastPostTime" => $TimeStamp + $GagTime
				));
			}
			break;
		}
		$Content = $ContentFilterResult['Content'];

		try {
			$DB->beginTransaction();
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
			$NewPostResult = $DB->query("INSERT INTO `" . PREFIX . "posts`
                (`ID`, `TopicID`, `IsTopic`, `UserID`, `UserName`, `Subject`, `Content`, `PostIP`, `PostTime`, `IsDel`) 
                VALUES (:ID,:TopicID,:IsTopic,:UserID,:UserName,:Subject,:Content,:PostIP,:PostTime,:IsDel)", $PostData);

			$PostID = $DB->lastInsertId();

			if ($NewPostResult) {
				//更新全站统计数据
				$NewConfig = array(
					"NumPosts" => $Config["NumPosts"] + 1,
					"DaysPosts" => $Config["DaysPosts"] + 1
				);
				UpdateConfig($NewConfig);
				//更新主题统计数据
				$DB->query("UPDATE `" . PREFIX . "topics` SET Replies=Replies+1,LastTime=?,LastName=? WHERE `ID`=?", array(
					($TimeStamp > $Topic['LastTime']) ? $TimeStamp : $Topic['LastTime'],
					$CurUserName,
					$TopicID
				));
				//更新用户自身统计数据
				UpdateUserInfo(array(
					"Replies" => $CurUserInfo['Replies'] + 1,
					"LastPostTime" => $TimeStamp + $GagTime
				));
				//标记附件所对应的帖子标签
				$DB->query("UPDATE `" . PREFIX . "upload` SET PostID=? WHERE `PostID`=0 and `UserName`=?", array(
					$PostID,
					$CurUserName
				));
				//添加提醒消息
				AddingNotifications($Content, $TopicID, $PostID, $Topic['UserName']);
				if ($CurUserID != $Topic['UserID']) {
					$DB->query('INSERT INTO `' . PREFIX . 'notifications`
                    (`ID`, `UserID`, `UserName`, `Type`, `TopicID`, `PostID`, `Time`, `IsRead`) 
                    VALUES (NULL,?,?,?,?,?,?,?)', array(
						$Topic['UserID'],
						$CurUserName,
						1,
						$TopicID,
						$PostID,
						$TimeStamp,
						0
					));
					$DB->query('UPDATE `' . PREFIX . 'users` SET `NewReply` = `NewReply`+1 WHERE ID = :UserID', array(
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
				//Redirect('t/'.$TopicID);

			}
			$DB->commit();
		} catch (Exception $ex) {
			$DB->rollBack();
			$Error = $Lang['Posting_Too_Often'];
			$ErrorCode = $ErrorCodeList['Posting_Too_Often'];
		}
	} while (false);
}
$DB->CloseConnection();

// 页面变量
$PageTitle = 'Reply';
$ContentFile = $TemplatePath . 'reply.php';
include($TemplatePath . 'layout.php');