<?php
require(dirname(__FILE__)."/common.php");
require(dirname(__FILE__).'/language/'.ForumLanguage.'/favorites.php');
Auth(1);
$Page = Request('Get', 'page');
$TotalPage = ceil($CurUserInfo['NumFavTopics']/$Config['TopicsPerPage']);
if($Page<0 || $Page==1){
	header('location: '.$Config['WebsitePath'].'/favorites');
	exit;
}
if($Page>$TotalPage){
	header('location: '.$Config['WebsitePath'].'/favorites/page/'.$TotalPage);
	exit;
}
if($Page == 0) $Page = 1;
if($Page<=10)

$TopicsArray = $DB->query('SELECT * FROM '.$Prefix.'favorites force index(UsersFavorites) Where UserID=? and Type=1 ORDER BY DateCreated DESC LIMIT '.($Page-1)*$Config['TopicsPerPage'].','.$Config['TopicsPerPage'],array($CurUserID));
$DB->CloseConnection();
$PageTitle = $Lang['My_Favorites'];
$PageTitle .= $Page>1?' Page'.$Page:'';
$ContentFile = $TemplatePath.'favorites.php';
include($TemplatePath.'layout.php');
?>