<?php
require(LanguagePath . 'notifications.php');
Auth(1);
$DB->CloseConnection();

// 页面变量
$PageTitle   = $Lang['Notifications'];
$ContentFile = $TemplatePath . 'notifications_list.php';
include($TemplatePath . 'layout.php');