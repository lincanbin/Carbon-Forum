<?php
require(dirname(__FILE__)."/common.php");
Auth(1);
$NotificationsArray = $DB->query('SELECT n.ID as NID, n.Type, n.IsRead, p.ID, p.TopicID, p.IsTopic, p.UserID, p.UserName, p.Subject, p.Content, p.PostTime, p.IsDel FROM '.$Prefix.'notifications n LEFT JOIN '.$Prefix.'posts p on p.ID=n.PostID Where n.UserID = ? ORDER BY n.Time DESC LIMIT 200',array($CurUserID));
$ReplyArray = array();
$MentionArray = array();
if($NotificationsArray){
	foreach ($NotificationsArray as $Value) {
		switch ($Value['Type']) {
			case 1:
				$ReplyArray[] = $Value;
				break;
			case 2:
				$MentionArray[] = $Value;
				break;
			default:
				break;
		}
	}
}
unset($NotificationsArray);

$DB->query("UPDATE ".$Prefix."users force index(PRI) SET NewMessage = 0 Where ID=:UserID",array('UserID'=>$CurUserID));
$CurUserInfo['NewMessage'] = 0;
$DB->CloseConnection();
// 页面变量
$PageTitle = '消息中心';
$ContentFile = $TemplatePath.'notifications.php';
include($TemplatePath.'layout.php');
?>