<?php


function getInboxID($ReceiverID) {
	global $DB, $CurUserID;
	/*
	$InboxID = $DB->single('SELECT
			ID
		FROM
			carbon_inbox
		WHERE (SenderID = :SenderID1 AND ReceiverID = :ReceiverID1)
		OR (SenderID = :ReceiverID2 AND ReceiverID = :SenderID2)
		LIMIT 1');
	*/
	$InboxID = $DB->single('(SELECT ID FROM carbon_inbox
			WHERE SenderID = :SenderID1 AND ReceiverID = :ReceiverID1
			LIMIT 1)
		UNION
		(SELECT ID FROM carbon_inbox
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
		$InboxID = $DB->query('INSERT INTO `carbon`.`carbon_inbox` 
			(`ID`, `SenderID`, `SenderName`, `ReceiverID`, `ReceiverName`, `LastContent`, `LastTime`) VALUES 
			(null, :SenderID, :SenderName, :ReceiverID, :ReceiverName, "", :TimeStamp)', array(
				'SenderID' => $CurUserID,
				'SenderName' => $CurUserName,
				'ReceiverID' => $ReceiverID,
				'ReceiverName' => $TargetUserInfo['UserName'],
				'TimeStamp' => $TimeStamp
		));
	}

	return $InboxID;
}


function CreateMessage($ReceiverID, $Content) {
	global $DB, $CurUserID;
}