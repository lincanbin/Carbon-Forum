<?php
require(dirname(__FILE__) . '/common.php');
require(dirname(__FILE__) . '/language/' . ForumLanguage . '/user.php');
$UserName = Request('Get', 'username');
$UserInfo = array();
$UserInfo = $DB->row('SELECT * FROM ' . $Prefix . 'users Where UserName=:UserName', array(
	'UserName' => $UserName
));
if (!$UserInfo)
	AlertMsg('404 Not Found', '404 Not Found', 404);
if ($CurUserID)
	$IsFavorite = $DB->single("SELECT ID FROM " . $Prefix . "favorites Where UserID=:UserID and Type=3 and FavoriteID=:FavoriteID", array(
		'UserID' => $CurUserID,
		'FavoriteID' => $UserInfo['ID']
	));
$PostsArray = $DB->query('SELECT * FROM ' . $Prefix . 'posts Where UserName=:UserName and IsDel=0 ORDER BY PostTime DESC LIMIT 30', array(
	'UserName' => $UserInfo['UserName']
));
$DB->CloseConnection();
$PageTitle    = $UserInfo['UserName'];
$PageMetaDesc = $UserInfo['UserName'] . ' - ' . htmlspecialchars(strip_tags(mb_substr($UserInfo['UserIntro'], 0, 150, 'utf-8')));
$ContentFile  = $TemplatePath . 'user.php';
include($TemplatePath . 'layout.php');