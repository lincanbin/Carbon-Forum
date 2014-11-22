<?php
require(dirname(__FILE__)."/common.php");
require(dirname(__FILE__).'/language/'.ForumLanguage.'/favorite_tags.php');
Auth(1);
$Page = Request('Get', 'page');
if($Page<0 || $Page==1){
	header('location: '.$Config['WebsitePath'].'/tags/following');
	exit;
}
if($Page == 0) $Page = 1;
$TagsFollowing = $DB->query('SELECT * FROM '.$Prefix.'favorites force index(UsersFavorites) Where UserID=? and Type=2',array($CurUserID));
$TopicIDArray=array();
if($TagsFollowing)
	$TopicIDArray = $DB->column('SELECT TopicID FROM '.$Prefix.'posttags force index(TagsIndex) Where TagID in (?) ORDER BY TopicID DESC LIMIT '.($Page-1)*$Config['TopicsPerPage'].','.$Config['TopicsPerPage'],ArrayColumn($TagsFollowing, 'FavoriteID'));
array_unique($TopicIDArray);
$TopicsArray=array();
if($TopicIDArray)
	$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` FROM '.$Prefix.'topics force index(PRI) Where ID in (?) and IsDel=0 ORDER BY LastTime DESC',$TopicIDArray);

$DB->CloseConnection();
$PageTitle = $Lang['My_Following_Tags'];
$PageTitle .= $Page>1?' Page'.$Page:'';
$ContentFile = $TemplatePath.'favorite_tags.php';
include($TemplatePath.'layout.php');
?>