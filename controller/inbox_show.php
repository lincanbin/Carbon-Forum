<?php
SetStyle('api', 'API');
header("Content-Type: text/html; charset=utf-8");
Auth(1, 0, true);

$InboxID = intval(Request('Get', 'inbox_id'));
$Page = intval(Request('Get', 'page', 1));
$UserInfo = array();
$DialogInfo = $DB->row('SELECT * FROM ' . PREFIX . 'inbox WHERE ID=:ID', array(
	'ID' => $InboxID
));

if ($Page <= 0 || empty($DialogInfo)){
	AlertMsg('404 Not Found', '404 Not Found', 404);
}

$MessagesArray = $DB->query('SELECT * FROM ' . PREFIX . 'messages 
	WHERE IsDel = :IsDel AND InboxID=:InboxID ORDER BY Time DESC
	LIMIT :Offset, :Number', array(
		'IsDel' => 0,
		'InboxID' => $InboxID,
		'Offset' => ($Page - 1) * $Config['PostsPerPage'],
		'Number' => $Config['PostsPerPage']
));

$DB->CloseConnection();

// 页面变量
$PageTitle   = 'Inbox';
$ContentFile = $TemplatePath . 'reply.php';
include($TemplatePath . 'layout.php');