<?php
require(LanguagePath . 'user.php');
$UserName = Request('Get', 'username');
$UserInfo = array();
if(preg_match('/^[1-9][0-9]*$/', $UserName)) {
	$UserInfo = $DB->row('SELECT * FROM ' . PREFIX . 'users WHERE ID=:ID', array(
		'ID' => $UserName
	));
	if (!empty($UserInfo)) {
		Redirect('u/' . urlencode($UserInfo['UserName']));
	} else {
		AlertMsg('404 Not Found', '404 Not Found', 404);
	}
}

$UserInfo = $DB->row('SELECT * FROM ' . PREFIX . 'users WHERE UserName=:UserName', array(
	'UserName' => $UserName
));
if (!$UserInfo)
	AlertMsg('404 Not Found', '404 Not Found', 404);

if ($CurUserID)
	$IsFavorite = $DB->single("SELECT ID FROM " . PREFIX . "favorites WHERE UserID=:UserID and Type = 3 and FavoriteID=:FavoriteID", array(
		'UserID' => $CurUserID,
		'FavoriteID' => $UserInfo['ID']
	));
$PostsArray = $DB->query('SELECT * FROM ' . PREFIX . 'posts WHERE UserName=:UserName and IsDel = 0 ORDER BY PostTime DESC LIMIT 30', array(
	'UserName' => $UserInfo['UserName']
));
$DB->CloseConnection();
$PageTitle    = $UserInfo['UserName'];
$PageMetaDesc = $UserInfo['UserName'] . ' - ' . htmlspecialchars(strip_tags(mb_substr($UserInfo['UserIntro'], 0, 150, 'utf-8')));
$ContentFile  = $TemplatePath . 'user.php';
include($TemplatePath . 'layout.php');