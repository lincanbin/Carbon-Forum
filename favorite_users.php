<?php
require(dirname(__FILE__)."/common.php");
require(dirname(__FILE__).'/language/'.ForumLanguage.'/favorite_users.php');
Auth(1);
$Page = intval(Request('Get', 'page'));
if($Page<0 || $Page==1){
	header('location: '.$Config['WebsitePath'].'/users/following');
	exit;
}
if($Page == 0) $Page = 1;
$UsersFollowing = array();
$UsersFollowing = $DB->query('SELECT * FROM '.$Prefix.'favorites force index(UsersFavorites) Where UserID=? and Type=3',array($CurUserID));
$PostsArray = array();
if($UsersFollowing)
	$PostsArray = $DB->query('SELECT * FROM '.$Prefix.'posts force index(UserPosts) Where UserName in (?) and IsDel=0 ORDER BY PostTime DESC LIMIT '.($Page-1)*$Config['PostsPerPage'].','.$Config['PostsPerPage'], ArrayColumn($UsersFollowing, 'Title'));
$DB->CloseConnection();
$PageTitle = $Lang['My_Following_Users'];
$PageTitle .= $Page>1?' Page'.$Page:'';
$ContentFile = $TemplatePath.'favorite_users.php';
include($TemplatePath.'layout.php');
?>