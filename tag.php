<?php
require(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/tag.php');
$TagName = Request('Get', 'name');
$Page    = Request('Get', 'page');
$TagInfo = array();
if ($TagName)
	$TagInfo = $DB->row('SELECT * FROM ' . $Prefix . 'tags force index(TagName) Where Name=:Name', array(
		'Name' => $TagName
	));
if (empty($TagInfo) || $TagInfo['TotalPosts'] == 0 || ($TagInfo['IsEnabled'] == 0 && $CurUserRole < 3))
	AlertMsg('404 Not Found', '404 Not Found', 404);
$TotalPage = ceil($TagInfo['TotalPosts'] / $Config['TopicsPerPage']);
if ($Page < 0 || $Page == 1) {
	header('location: ' . $Config['WebsitePath'] . '/tag/' . $TagInfo['Name']);
	exit;
}
if ($Page > $TotalPage) {
	header('location: ' . $Config['WebsitePath'] . '/tag/' . $TagInfo['Name'] . '/page/' . $TotalPage);
	exit;
}
if ($Page == 0)
	$Page = 1;
if ($Page <= 10)
	$TagIDArray = $DB->column('SELECT TopicID FROM ' . $Prefix . 'posttags force index(TagsIndex) Where TagID=:TagID ORDER BY TopicID DESC LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . $Config['TopicsPerPage'], array(
		'TagID' => $TagInfo['ID']
	));
else
	$TagIDArray = $DB->column('SELECT TopicID FROM ' . $Prefix . 'posttags force index(TagsIndex) Where TagID=:TagID and TopicID<=(SELECT TopicID FROM ' . $Prefix . 'posttags force index(TagsIndex) Where TagID=:TagID2 ORDER BY TopicID DESC LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',1) ORDER BY TopicID DESC LIMIT ' . $Config['TopicsPerPage'], array(
		'TagID' => $TagInfo['ID'],
		'TagID2' => $TagInfo['ID']
	));
$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` FROM ' . $Prefix . 'topics force index(PRI) Where ID in (?) and IsDel=0 ORDER BY LastTime DESC', $TagIDArray);
if ($CurUserID)
	$IsFavorite = $DB->single("SELECT ID FROM " . $Prefix . "favorites Where UserID=:UserID and Type=2 and FavoriteID=:FavoriteID", array(
		'UserID' => $CurUserID,
		'FavoriteID' => $TagInfo['ID']
	));
$DB->CloseConnection();
$PageTitle = $TagInfo['Name'];
$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$PageMetaDesc = $TagInfo['Name'] . ' - ' . htmlspecialchars(mb_substr(trim(strip_tags($TagInfo['Description'])), 0, 150, 'utf-8'));
$ContentFile  = $TemplatePath . 'tag.php';
include($TemplatePath . 'layout.php');