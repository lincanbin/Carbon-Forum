<?php
SetStyle('api', 'API');
Auth(1, 0, false);
require(ServicePath . 'inbox.php');
$InboxID = Request('Post', 'inbox_id');
$Content = Request('Post', 'Content');
$UserInfo = array();
if(!preg_match('/^[1-9][0-9]*$/', $InboxID)) {
	$InboxID = GetInboxID($InboxID);
}

$DialogInfo = $DB->row('SELECT * FROM ' . PREFIX . 'inbox WHERE ID = :ID AND (SenderID = :SenderID OR ReceiverID = :ReceiverID)', array(
	'ID' => $InboxID,
	'SenderID' => $CurUserID,
	'ReceiverID' => $CurUserID,
));

$Result = CreateMessage($DialogInfo, $Content);

if (empty($InboxID) || empty($DialogInfo)){
	AlertMsg('404 Not Found', '404 Not Found', 404);
}

// 页面变量
$PageTitle = 'Create new message';
$ContentFile = $TemplatePath . 'inbox_create.php';
include($TemplatePath . 'layout.php');