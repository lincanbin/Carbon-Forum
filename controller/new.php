<?php
require(LanguagePath . 'new.php');
Auth(1, 0, true);

$ErrorCodeList = require(LibraryPath . 'code/new.error.code.php');
$Error     = '';
$ErrorCode = $ErrorCodeList['Default'];
$Title     = '';
$Content   = '';
$TagsArray = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$ReferCheckResult = ReferCheck(Request('Post', 'FormHash'));
	SetStyle('api', 'API');
	if (!$ReferCheckResult) {
		AlertMsg($Lang['Error_Unknown_Referer'], $Lang['Error_Unknown_Referer'], 403);
	}
	$Title     = Request('Post', 'Title');
	$Content   = Request('Post', 'Content');
	$TagsArray = isset($_POST['Tag']) ? array_unique($_POST['Tag']) : array();
	do {
		if ($Config['AllowNewTopic'] === 'false' && $CurUserRole < 3) {
			$Error     = $Lang['Prohibited_New_Topic'];
			$ErrorCode = $ErrorCodeList['Prohibited_New_Topic'];
			break;
		}

        //发帖至少要间隔8秒
        if (DEBUG_MODE === false && ($CurUserRole < 3 && ($TimeStamp - intval($CurUserInfo['LastPostTime'])) <= intval($Config['PostingInterval']))) {
			$Error     = $Lang['Posting_Too_Often'];
			$ErrorCode = $ErrorCodeList['Posting_Too_Often'];
			break;
		}

		if ($Title === '') {
			$Error     = $Lang['Title_Empty'];
			$ErrorCode = $ErrorCodeList['Title_Empty'];
			break;
		}
		
		
		if (strlen($Title) > $Config['MaxTitleChars'] || strlen($Content) > $Config['MaxPostChars']) {
			$Error     = str_replace('{{MaxPostChars}}', $Config['MaxPostChars'], str_replace('{{MaxTitleChars}}', $Config['MaxTitleChars'], $Lang['Too_Long']));
			$ErrorCode = $ErrorCodeList['Too_Long'];
			break;
		}
		
		
		$TagsArray = TagsDiff($TagsArray, array());
		if ($Config['AllowEmptyTags'] !== 'true' && (empty($TagsArray) || in_array('', $TagsArray) || count($TagsArray) > $Config["MaxTagsNum"])) {
			$Error     = $Lang['Tags_Empty'];
			$ErrorCode = $ErrorCodeList['Tags_Empty'];
			break;
		}


		// 内容过滤系统
		$TitleFilterResult = Filter($Title);
		$ContentFilterResult = Filter($Content);
		$GagTime = ($TitleFilterResult['GagTime'] > $ContentFilterResult['GagTime']) ? $TitleFilterResult['GagTime'] : $ContentFilterResult['GagTime'];
		$GagTime = $CurUserRole < 3 ? $GagTime : 0;
		$Prohibited = $TitleFilterResult['Prohibited'] | $ContentFilterResult['Prohibited'];
		if ($Prohibited){
			$Error     = $Lang['Prohibited_Content'];
			$ErrorCode = $ErrorCodeList['Prohibited_Content'];
			if ($GagTime) {
				//禁言用户 $GagTime 秒
				UpdateUserInfo(array(
					"LastPostTime" => $TimeStamp + $GagTime
				));
			}
			break;	
		}

		$Title = $TitleFilterResult['Content'];
		$Content = $ContentFilterResult['Content'];
		try {
            $DB->beginTransaction();
            //获取已存在的标签
			if (!empty($TagsArray)) {
				$TagsExistArray = $DB->query("SELECT ID, Name FROM `" . PREFIX . "tags` WHERE `Name` IN (?)", $TagsArray);
			} else {
				$TagsExistArray = array();
			}
			$TagsExist      = ArrayColumn($TagsExistArray, 'Name');
			$TagsID         = ArrayColumn($TagsExistArray, 'ID');
			$NewTags        = TagsDiff($TagsArray, $TagsExist);
			//新建不存在的标签
			if ($NewTags) {
				foreach ($NewTags as $Name) {
					$TempTagsID = $DB->insert(PREFIX . "tags", array(
						'ID'                 => null,
						'Name'               => $Name,
						'Followers'          => 0,
						'Icon'               => 0,
						'Description'        => null,
						'IsEnabled'          => 1,
						'TotalPosts'         => 1,
						'MostRecentPostTime' => $TimeStamp,
						'DateCreated'        => $TimeStamp
					));
					if ($TempTagsID !== false) {
						$TagsID[] = $TempTagsID;
					}
				}
				//更新全站统计数据
				$NewConfig = array(
					"NumTags" => $Config["NumTags"] + count($NewTags)
				);
				//var_dump($NewTags);
			}
			$TagsArray      = array_merge($TagsExist, $NewTags);
			$AutoIncrementID = $DB->single('SELECT
					AUTO_INCREMENT
				FROM
					`information_schema`.`TABLES`
				WHERE
					table_schema = DATABASE()
				AND TABLE_NAME = :TableName;', array(
				'TableName' => PREFIX . "topics"
			));
			//往Topics表插入数据
			$TopicData = array(
				"ID"             => null,
				"Topic"          => htmlspecialchars($Title),
				"Tags"           => implode("|", $TagsArray), //过滤不合法的标签请求
				"UserID"         => $CurUserID,
				"UserName"       => $CurUserName,
				"LastName"       => "",
				"PostTime"       => $TimeStamp,
				"LastTime"       => $TimeStamp,
				"LastTimeIndex"  => BigIntHex2Dec(uniqid('', false) . str_pad(substr(dechex($AutoIncrementID), -3), 3, '0')),
				"IsLocked"       => 0,
				"IsDel"          => 0,
				"Views"          => 0,
				"Replies"        => 0,
				"LastViewedTime" => 0,
				"Favorites"      => 0,
			);
			$TopicID = $DB->insert(PREFIX . 'topics', $TopicData);
			//往Posts表插入数据
			$PostData = array(
				"ID"       => null,
				"TopicID"  => $TopicID,
				"IsTopic"  => 1,
				"UserID"   => $CurUserID,
				"UserName" => $CurUserName,
				"Subject"  => CharCV($Title),
				"Content"  => XssEscape($Content),
				"PostIP"   => $CurIP,
				"PostTime" => $TimeStamp
			);
			$PostID = $DB->insert(PREFIX . 'posts', $PostData);
			
			if ($TopicID !== false && $PostID !== false) {
				//更新全站统计数据
				$NewConfig = array(
					"NumTopics" => $Config["NumTopics"] + 1,
					"DaysTopics" => $Config["DaysTopics"] + 1
				);
				UpdateConfig($NewConfig);
				//更新用户自身统计数据
				UpdateUserInfo(array(
					"Topics" => $CurUserInfo['Topics'] + 1,
					"LastPostTime" => $TimeStamp + $GagTime
				));
				//标记附件所对应的帖子标签
				$DB->query("UPDATE `" . PREFIX . "upload` SET PostID=? WHERE `PostID`=0 and `UserName`=?", array(
					$PostID,
					$CurUserName
				));
				//记录标签与TopicID的对应关系
				foreach ($TagsID as $TagID) {
					$DB->insert(PREFIX . 'posttags', array(
						'TagID' => $TagID,
						'TopicID' => $TopicID,
						'PostID' => $PostID
					));
				}
				//更新标签统计数据
				if ($TagsExist) {
					$DB->query("UPDATE `" . PREFIX . "tags` SET TotalPosts=TotalPosts+1, MostRecentPostTime=" . $TimeStamp . " WHERE `Name` in (?)", $TagsExist);
				}
				//添加提醒消息
				AddingNotifications($Content, $TopicID, $PostID);
				//清理首页内存缓存
				if ($MCache) {
					$MCache->delete(MemCachePrefix . 'Homepage');
				}
				//跳转到主题页
				//Redirect('t/'.$TopicID);
			}
            $DB->commit();
		} catch(Exception $ex) {
			$DB->rollBack();
            $Error     = $Lang['Posting_Too_Often'] . DEBUG_MODE ? $ex->getMessage() : '';
            $ErrorCode = $ErrorCodeList['Posting_Too_Often'];
		}
	} while (false);
}
$DB->closeConnection();
// 页面变量
$PageTitle   = $Lang['Create_New_Topic'];
$ContentFile = $TemplatePath . 'new.php';
include($TemplatePath . 'layout.php');