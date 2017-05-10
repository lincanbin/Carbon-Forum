<?php
require(LanguagePath . 'inbox.php');
Auth(1, 0, false);
require(ServicePath . 'inbox.php');
$InboxID = Request('Get', 'inbox_id');
if(!preg_match('/^[1-9][0-9]*$/', $InboxID)) {
	$InboxID = GetInboxID($InboxID);
}

$DialogInfo = $DB->row('SELECT * FROM ' . PREFIX . 'inbox WHERE ID = :ID AND (SenderID = :SenderID OR ReceiverID = :ReceiverID)', array(
	'ID' => $InboxID,
	'SenderID' => $CurUserID,
	'ReceiverID' => $CurUserID,
));

if (empty($InboxID) || empty($DialogInfo)){
	AlertMsg('404 Not Found', '404 Not Found', 404);
}

$ContactUserName = $DialogInfo['SenderID'] == $CurUserID ? $DialogInfo['ReceiverName'] : $DialogInfo['SenderName'];

// 页面变量
$PageTitle   = str_replace('{{UserName}}', $ContactUserName, $Lang['Chat_With_SB']);
$ContentFile = $TemplatePath . 'inbox.php';
include($TemplatePath . 'layout.php');