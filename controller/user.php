<?php
require(LanguagePath . 'user.php');
$UserName = Request('Get', 'username');
$UserInfo = array();
if(preg_match('/^[1-9][0-9]*$/', $UserName)) {
	$UserInfo = GetUserInfo($UserName);
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
if ($UserInfo['UserAccountStatus'] || $CurUserRole >= 4) {
	$PostsArray = $DB->query('SELECT * FROM ' . PREFIX . 'posts WHERE UserName=:UserName ORDER BY PostTimeIndex DESC LIMIT 30', array(
		'UserName' => $UserInfo['UserName']
	));
} else {
	$PostsArray = [];
}

$DB->closeConnection();
$PageTitle    = $UserInfo['UserName'];
$PageMetaDesc = $UserInfo['UserName'] . ' - ' . htmlspecialchars(strip_tags(mb_substr($UserInfo['UserIntro'], 0, 150, 'utf-8')));
$ContentFile  = $TemplatePath . 'user.php';
include($TemplatePath . 'layout.php');