<?php
Auth(1, 0, false);
require(ServicePath . 'inbox.php');
$InboxID = intval(Request('Get', 'inbox_id'));
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

if (empty($InboxID) || empty($DialogInfo)){
	AlertMsg('404 Not Found', '404 Not Found', 404);
}

$ContactUserName = $DialogInfo['SenderID'] == $CurUserID ? $DialogInfo['ReceiverName'] : $DialogInfo['SenderName'];

// 页面变量
$PageTitle   = 'Contact With ' . $ContactUserName;
$ContentFile = $TemplatePath . 'inbox.php';
include($TemplatePath . 'layout.php');