<?php
require(LanguagePath . 'notifications.php');
SetStyle('api', 'API');
Auth(1);

$Type = Request('GET', 'type', false);
$Page = max(intval(Request('Request', 'page', 1)), 1);

$ResultArray = array(
	"Status" => 1
);
if ($Type === false || $Type === 'reply') {
	$ResultArray['ReplyArray'] = $DB->query('
		SELECT n.ID as NID, n.Type, n.IsRead, p.ID, p.TopicID, p.IsTopic, p.UserID, p.UserName, p.Subject, p.Content, p.PostTime, p.IsDel 
		FROM ' . PREFIX . 'notifications n LEFT JOIN ' . PREFIX . 'posts p 
		on p.ID=n.PostID 
		WHERE n.UserID = :UserID and n.Type=1 
		ORDER BY n.Time DESC LIMIT :Offset, :Number', array(
			'UserID' => $CurUserID,
			'Offset' => ($Page - 1) * $Config['TopicsPerPage'],
			'Number' => $Config['TopicsPerPage']
	));
	if (empty($ResultArray['ReplyArray'])) {
		$ResultArray['ReplyArray'] = array();
	}
	foreach($ResultArray['ReplyArray'] as $Key => $Post)
	{
		$ResultArray['ReplyArray'][$Key]['PostFloor'] = -1;
		$ResultArray['ReplyArray'][$Key]['FormatPostTime'] = FormatTime($Post['PostTime']);
		$ResultArray['ReplyArray'][$Key]['Content'] = strip_tags(mb_substr($Post['Content'], 0, 256, 'utf-8'),'<p><br><a>');
	}
}

if ($Type === false || $Type === 'mention') {
	$ResultArray['MentionArray'] = $DB->query('SELECT n.ID as NID, n.Type, n.IsRead, p.ID, p.TopicID, p.IsTopic, p.UserID, p.UserName, p.Subject, p.Content, p.PostTime, p.IsDel 
		FROM ' . PREFIX . 'notifications n 
		LEFT JOIN ' . PREFIX . 'posts p 
		on p.ID=n.PostID 
		WHERE n.UserID = :UserID and n.Type=2 
		ORDER BY n.Time DESC LIMIT :Offset, :Number', array(
			'UserID' => $CurUserID,
			'Offset' => ($Page - 1) * $Config['TopicsPerPage'],
			'Number' => $Config['TopicsPerPage']
	));
	if (empty($ResultArray['MentionArray'])) {
		$ResultArray['MentionArray'] = array();
	}
	foreach($ResultArray['MentionArray'] as $Key => $Post)
	{
		$ResultArray['MentionArray'][$Key]['PostFloor'] = -1;
		$ResultArray['MentionArray'][$Key]['FormatPostTime'] = FormatTime($Post['PostTime']);
		$ResultArray['MentionArray'][$Key]['Content'] = strip_tags(mb_substr($Post['Content'], 0, 256, 'utf-8'),'<p><br><a>');
	}
}

if ($Type === false || $Type === 'inbox') {
	$ResultArray['InboxArray'] = $DB->query('SELECT ID, ReceiverID as ContactID, ReceiverName as ContactName, LastContent as Content, LastTime 
            FROM ' . PREFIX . 'inbox
			WHERE SenderID = :SenderID AND IsDel = :IsDel1
		UNION
		(SELECT ID, SenderID as ContactID, SenderName as ContactName, LastContent as Content, LastTime 
		    FROM ' . PREFIX . 'inbox
			WHERE ReceiverID = :ReceiverID AND IsDel = :IsDel2)
		ORDER BY LastTime DESC 
		LIMIT :Offset, :Number;', array(
			'SenderID' => $CurUserID,
			'ReceiverID' => $CurUserID,
			'IsDel1' => 0,
			'IsDel2' => 0,
			'Offset' => ($Page - 1) * $Config['TopicsPerPage'],
			'Number' => intval($Config['TopicsPerPage'])
		));
	if (empty($ResultArray['InboxArray'])) {
		$ResultArray['InboxArray'] = array();
	}
	foreach($ResultArray['InboxArray'] as $Key => $Message)
	{
		$ResultArray['InboxArray'][$Key]['FormatPostTime'] = FormatTime($Message['LastTime']);
		$ResultArray['InboxArray'][$Key]['Content'] = strip_tags(mb_substr($Message['Content'], 0, 80, 'utf-8'),'<p><br><a>') . '……';
	}
}
//Clear unread marks
UpdateUserInfo(array(
	'NewReply' => 0,
	'NewMention' => 0,
	'NewMessage' => 0
));
$CurUserInfo['NewReply'] = 0;
$CurUserInfo['NewMention'] = 0;
$CurUserInfo['NewMessage'] = 0;
$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Notifications'];
$ContentFile = $TemplatePath . 'notifications.php';
include($TemplatePath . 'layout.php');