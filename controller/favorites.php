<?php
require(LanguagePath . 'favorites.php');
Auth(1);
$Page      = Request('Get', 'page');
$TotalPage = ceil($CurUserInfo['NumFavTopics'] / $Config['TopicsPerPage']);
if ($Page < 0 || $Page == 1) 
	Redirect('favorites');
if ($Page > $TotalPage)
	Redirect('favorites/page/' . $TotalPage);
if ($Page == 0)
	$Page = 1;
if ($Page <= 10)
	$TopicsArray = $DB->query('SELECT * FROM ' . PREFIX . 'favorites force index(UsersFavorites) Where UserID=? and Type=1 ORDER BY DateCreated DESC LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . $Config['TopicsPerPage'], array(
		$CurUserID
	));
$DB->CloseConnection();
$PageTitle = $Lang['My_Favorites'];
$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$ContentFile = $TemplatePath . 'favorites.php';
include($TemplatePath . 'layout.php');