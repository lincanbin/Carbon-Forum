<?php
/*
 * Get inbox id
 * @param integer $ReceiverID
 * @return integer
 * */
function GetInboxID($ReceiverName)
{
	global $DB, $CurUserID, $CurUserName, $TimeStamp;
	if (empty($CurUserID) || empty($ReceiverName)) {
		return 0;
	}
	try {
		$DB->beginTransaction();
		$TargetUserInfo = $DB->row('SELECT `ID`, `UserName` FROM ' . PREFIX . 'users WHERE UserName = :UserName', array(
			'UserName' => $ReceiverName
		));
		if (empty($TargetUserInfo)) {
			return 0;
		}
		$ReceiverID = $TargetUserInfo['ID'];

		$InboxID = $DB->single('(SELECT ID FROM ' . PREFIX . 'inbox
				WHERE SenderID = :SenderID1 AND ReceiverID = :ReceiverID1
				LIMIT 1)
			UNION
			(SELECT ID FROM ' . PREFIX . 'inbox
				WHERE SenderID = :SenderID2 AND ReceiverID = :ReceiverID2
				LIMIT 1)
			LIMIT 1;', array(
			'SenderID1' => $CurUserID,
			'ReceiverID1' => $ReceiverID,
			'SenderID2' => $ReceiverID,
			'ReceiverID2' => $CurUserID
		));
		if (empty($InboxID)) {
			$DB->query('INSERT INTO ' . PREFIX . 'inbox 
				(`ID`, `SenderID`, `SenderName`, `ReceiverID`, `ReceiverName`, `LastContent`, `LastTime`, `IsDel`) VALUES 
				(NULL, :SenderID, :SenderName, :ReceiverID, :ReceiverName, "", :TimeStamp, :IsDel)', array(
				'SenderID' => $CurUserID,
				'SenderName' => $CurUserName,
				'ReceiverID' => $ReceiverID,
				'ReceiverName' => $TargetUserInfo['UserName'],
				'TimeStamp' => $TimeStamp,
				'IsDel' => 1
			));
			$InboxID = $DB->lastInsertId();
		}
		$DB->commit();
		return $InboxID;
	} catch (Exception $ex) {
		$DB->rollBack();
		return 0;
	}
}

/*
 * Create new message
 * @param array $DialogInfo
 * @param string $Content
 * @return boolean
 * */
function CreateMessage($DialogInfo, $Content)
{
	global $DB, $CurUserID, $TimeStamp;
	if (empty($CurUserID) || empty($DialogInfo) || empty($Content)) {
		return false;
	}
	$InboxID = $DialogInfo['ID'];
	$ReceiverID = $DialogInfo['SenderID'] == $CurUserID ? $DialogInfo['ReceiverID'] : $DialogInfo['SenderID'];
	$Content = CharCV($Content);
	try {
		$DB->beginTransaction();

		$MessageID = $DB->query('INSERT INTO ' . PREFIX . 'messages (
				`ID`,
				`InboxID`,
				`UserID`,
				`Content`,
				`Time`,
				`IsDel`
			)
			VALUES
			(
				NULL,
				:InboxID,
				:UserID,
				:Content,
				:Time,
				:IsDel
			);', array(
			'InboxID' => $InboxID,
			'UserID' => $CurUserID,
			'Content' => nl2br($Content),
			'Time' => $TimeStamp,
			'IsDel' => 0,
		));

		$InboxResult = $DB->query('UPDATE ' . PREFIX . 'inbox
			SET 
			 `LastContent` = :LastContent,
			 `LastTime` = :LastTime,
			 `IsDel` = :IsDel
			WHERE
				(`ID` = :InboxID);', array(
			'InboxID' => $InboxID,
			'LastContent' => mb_substr($Content, 0, 255, 'utf-8'),
			'LastTime' => $TimeStamp,
			'IsDel' => 0,
		));
		$DB->query('UPDATE `' . PREFIX . 'users` SET `NewMessage` = NewMessage+1 WHERE ID = :UserID', array(
			'UserID' => $ReceiverID
		));
		$DB->commit();
		return !empty($MessageID) && !empty($InboxResult);
	} catch (Exception $ex) {
		$DB->rollBack();
		return false;
	}
}