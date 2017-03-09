<?php
require(ServicePath . 'inbox.php');
$InboxID = intval(Request('Get', 'inbox_id'));
$UserInfo = array();
if(preg_match('/^[1-9][0-9]*$/', $InboxID)) {
	$DialogInfo = $DB->row('SELECT * FROM ' . PREFIX . 'inbox WHERE ID=:ID', array(
		'ID' => $InboxID
	));
} else {
	$UserInfo = $DB->row('SELECT * FROM ' . PREFIX . 'users WHERE ID=:ID', array(
		'UserName' => $UserName
	));
	$InboxID = !empty($UserInfo) ? GetInboxID($UserInfo['ID']) : 0;
	if ($InboxID) {
		Redirect('inbox/' . $InboxID);
	}
}

if (empty($InboxID) || empty($DialogInfo)){
	AlertMsg('404 Not Found', '404 Not Found', 404);
}