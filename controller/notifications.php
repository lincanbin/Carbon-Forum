<?php
require(LanguagePath . 'notifications.php');
Auth(1);
$ReplyArray   = $DB->query('SELECT n.ID as NID, n.Type, n.IsRead, p.ID, p.TopicID, p.IsTopic, p.UserID, p.UserName, p.Subject, p.Content, p.PostTime, p.IsDel 
							FROM ' . PREFIX . 'notifications n LEFT JOIN ' . PREFIX . 'posts p 
							on p.ID=n.PostID 
							Where n.UserID = ? and n.Type=1 
							ORDER BY n.Time DESC LIMIT 30', array(
	$CurUserID
));
$MentionArray = $DB->query('SELECT n.ID as NID, n.Type, n.IsRead, p.ID, p.TopicID, p.IsTopic, p.UserID, p.UserName, p.Subject, p.Content, p.PostTime, p.IsDel 
							FROM ' . PREFIX . 'notifications n 
							LEFT JOIN ' . PREFIX . 'posts p 
							on p.ID=n.PostID 
							Where n.UserID = ? and n.Type=2 
							ORDER BY n.Time DESC LIMIT 30', array(
	$CurUserID
));
//Clear unread marks
UpdateUserInfo(array(
	'NewMessage' => 0
));
$CurUserInfo['NewMessage'] = 0;
$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Notifications'];
$ContentFile = $TemplatePath . 'notifications.php';
include($TemplatePath . 'layout.php');