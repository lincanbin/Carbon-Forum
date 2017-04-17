<?php
/*
 * Get inbox id
 * @param integer $ReceiverID
 * @return integer
 * */
function GetInboxID($ReceiverID)
{
	global $DB, $CurUserID, $CurUserName, $TimeStamp;
	if (empty($CurUserID) || empty($ReceiverID)) {
		return 0;
	}
	$InboxID = $DB->single('(SELECT ID FROM ' . PREFIX . 'inbox
			WHERE SenderID = :SenderID1 AND ReceiverID = :ReceiverID1
			LIMIT 1)
		UNION
		(SELECT ID FROM ' . PREFIX . 'inbox
			WHERE SenderID = :ReceiverID2 AND ReceiverID = :SenderID2
			LIMIT 1)
		LIMIT 1;', array(
		'SenderID1' => $CurUserID,
		'SenderID2' => $CurUserID,
		'ReceiverID1' => $ReceiverID,
		'ReceiverID2' => $ReceiverID
	));
	$TargetUserInfo = $DB->row('SELECT * FROM ' . PREFIX . 'users WHERE ID=:ID', array(
		'ID' => $ReceiverID
	));
	if (empty($TargetUserInfo)) {
		return 0;
	}
	if (empty($InboxID)) {
		$InboxID = $DB->query('INSERT INTO ' . PREFIX . 'inbox 
			(`ID`, `SenderID`, `SenderName`, `ReceiverID`, `ReceiverName`, `LastContent`, `LastTime`, `IsDel`) VALUES 
			(NULL, :SenderID, :SenderName, :ReceiverID, :ReceiverName, "", :TimeStamp, :IsDel)', array(
			'SenderID' => $CurUserID,
			'SenderName' => $CurUserName,
			'ReceiverID' => $ReceiverID,
			'ReceiverName' => $TargetUserInfo['UserName'],
			'TimeStamp' => $TimeStamp,
			'IsDel' => 1
		));
	}

	return $InboxID;
}

/*
 * Create new message
 * @param integer $InboxID
 * @param string $Content
 * @return boolean
 * */
function CreateMessage($InboxID, $Content)
{
	global $DB, $CurUserID, $TimeStamp;
	if (empty($CurUserID) || empty($InboxID) || empty($Content)) {
		var_dump($Content);exit();
		return false;
	}
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
			'Content' => $Content,
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
		$DB->commit();
		return !empty($MessageID) && !empty($InboxResult);
	} catch (Exception $ex) {
		$DB->rollBack();
		return false;
	}
}