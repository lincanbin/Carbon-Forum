<?php
require(dirname(__FILE__) . '/common.php');
require(dirname(__FILE__) . '/language/' . ForumLanguage . '/topic.php');
$ID    = intval($_GET['id']);
$Page  = intval(Request('Get', 'page'));
$Topic = $DB->row("SELECT * FROM " . $Prefix . "topics force index(PRI) Where ID=:id", array(
	"id" => $ID
));
if (!$Topic || ($Topic['IsDel'] && $CurUserRole < 3)) {
	AlertMsg('404 Not Found', '404 Not Found', 404);
}
$TotalPage = ceil(($Topic['Replies'] + 1) / $Config['PostsPerPage']);
if ($Page < 0 || $Page == 1) {
	header('location: ' . $Config['WebsitePath'] . '/t/' . $ID);
	exit;
}
if ($Page > $TotalPage) {
	header('location: ' . $Config['WebsitePath'] . '/t/' . $ID . '-' . $TotalPage);
	exit;
}
if ($Page == 0)
	$Page = 1;
$PostsArray = $DB->query("SELECT `ID`, `TopicID`,`UserID`, `UserName`, `Content`, `PostTime`, `IsDel` FROM " . $Prefix . "posts force index(TopicID) Where TopicID=:id ORDER BY PostTime ASC LIMIT " . ($Page - 1) * $Config['PostsPerPage'] . "," . $Config['PostsPerPage'], array(
	"id" => $ID
));
if ($CurUserID) {
	$IsFavorite = $DB->single("SELECT ID FROM " . $Prefix . "favorites Where UserID=:UserID and Type=1 and FavoriteID=:FavoriteID", array(
		'UserID' => $CurUserID,
		'FavoriteID' => $ID
	));
	//只有注册用户才更新访问数据，Spider实在太烦。
	$DB->query("UPDATE " . $Prefix . "topics force index(PRI) SET Views = Views+1,LastViewedTime = :LastViewedTime Where ID=:id", array(
		"LastViewedTime" => $TimeStamp,
		"id" => $ID
	));
}
if($Page != $TotalPage || ($Topic['Replies'] + 1) % $Config['PostsPerPage'] == 0){
	$EnableQuote = true;
}else{
	$EnableQuote = false;
}
$DB->CloseConnection();
$PageTitle = $Topic['Topic'];
$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$PageMetaDesc    = htmlspecialchars(strip_tags(mb_substr($PostsArray[0]['Content'], 0, 150, 'utf-8')));
$PageMetaKeyword = str_replace('|', ',', $Topic['Tags']);
$ContentFile     = $TemplatePath . 'topic.php';
include($TemplatePath . 'layout.php');