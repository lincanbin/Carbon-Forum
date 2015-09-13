<?php
require(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/tag.php');
$Page      = intval(Request('Get', 'page'));
$TotalPage = ceil($Config['NumTags'] / $Config['TopicsPerPage']);
if ($Page < 0 || $Page == 1) {
	header('location: ' . $Config['WebsitePath'] . '/tags');
	exit;
}
if ($Page > $TotalPage) {
	header('location: ' . $Config['WebsitePath'] . 'tags/page/' . $TotalPage);
	exit;
}
if ($Page == 0)
	$Page = 1;
$TagsArray = array();
// UPDATE `carbon_tags` t SET t.Description=(SELECT d.Abstract FROM `carbon_dict` d WHERE d.Title = t.Name limit 1)
if (!$TagsArray) {
	$TagsArray = $DB->query('SELECT * 
		FROM ' . $Prefix . 'tags 
		WHERE IsEnabled=1 
		ORDER BY TotalPosts DESC 
		LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . $Config['TopicsPerPage']);

	if ($CurUserID){
		$IsFavoriteArray = array_flip($DB->column("SELECT FavoriteID FROM " . $Prefix . "favorites 
			Where UserID=".$CurUserID." and Type=2 and FavoriteID in (?)", 
			ArrayColumn($TagsArray, 'ID')
		));
		//var_dump($IsFavoriteArray);
	}
}
$DB->CloseConnection();

$PageTitle = $Page > 1 ? ' Page' . $Page . '-' : '';
$PageTitle .= $Lang['Hot_Tags'];
$ContentFile  = $TemplatePath . 'tags.php';
include($TemplatePath . 'layout.php');