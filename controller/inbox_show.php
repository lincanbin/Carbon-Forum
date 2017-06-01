<?php
SetStyle('api', 'API');
header("Content-Type: text/html; charset=utf-8");
Auth(1, 0, false);

$InboxID = intval(Request('Get', 'inbox_id'));
$Page = intval(Request('Get', 'page', 1));
$UserInfo = array();
$DialogInfo = $DB->row('SELECT * FROM ' . PREFIX . 'inbox WHERE ID=:ID AND (SenderID = :SenderID OR ReceiverID = :ReceiverID)', array(
	'ID' => $InboxID,
	'SenderID' => $CurUserID,
	'ReceiverID' => $CurUserID
));

if ($Page <= 0 || empty($DialogInfo)){
	AlertMsg('404 Not Found', '404 Not Found', 404);
}

$MessagesArray = $DB->query('SELECT UserID as ContactID, Content, Time FROM ' . PREFIX . 'messages 
	WHERE IsDel = :IsDel AND InboxID = :InboxID ORDER BY Time DESC
	LIMIT :Offset, :Number', array(
		'IsDel' => 0,
		'InboxID' => $InboxID,
		'Offset' => ($Page - 1) * $Config['PostsPerPage'],
		'Number' => $Config['PostsPerPage']
));
foreach ($MessagesArray as &$Value) {
	$Value['IsMe'] = ($Value['ContactID'] == $CurUserID);
	$Value['ContactName'] = $DialogInfo['SenderID'] == $Value['ContactID'] ? $DialogInfo['SenderName'] : $DialogInfo['ReceiverName'];
	$Value['FormatTime'] = FormatTime($Value['Time']);
}
$DB->CloseConnection();
// 页面变量
$PageTitle   = 'Show message list of some inbox';
$ContentFile = $TemplatePath . 'inbox.php';
include($TemplatePath . 'layout.php');