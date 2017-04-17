<?php
SetStyle('api', 'API');
Auth(1, 0, false);
require(ServicePath . 'inbox.php');
$InboxID = intval(Request('Post', 'inbox_id'));
$Content = CharCV(Request('Post', 'Content'));
$UserInfo = array();
if(!preg_match('/^[1-9][0-9]*$/', $InboxID)) {
	$UserInfo = $DB->row('SELECT * FROM ' . PREFIX . 'users WHERE UserName = :UserName', array(
		'UserName' => $InboxID
	));
	$InboxID = !empty($UserInfo) ? GetInboxID($UserInfo['ID']) : 0;
}

$DialogInfo = $DB->row('SELECT * FROM ' . PREFIX . 'inbox WHERE ID = :ID AND (SenderID = :SenderID OR ReceiverID = :ReceiverID)', array(
	'ID' => $InboxID,
	'SenderID' => $CurUserID,
	'ReceiverID' => $CurUserID,
));

$Result = CreateMessage($InboxID, $Content);

if (empty($InboxID) || empty($DialogInfo)){
	AlertMsg('404 Not Found', '404 Not Found', 404);
}

// 页面变量
$PageTitle = 'Create new message';
$ContentFile = $TemplatePath . 'inbox_create.php';
include($TemplatePath . 'layout.php');