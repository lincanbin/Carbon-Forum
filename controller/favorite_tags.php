<?php
require(LanguagePath . 'favorite_tags.php');
Auth(1);
$Page = Request('Get', 'page');
if ($Page < 0 || $Page == 1) 
	Redirect('tags/following');
if ($Page == 0)
	$Page = 1;
$TagsFollowing = $DB->query('SELECT * FROM ' . PREFIX . 'favorites force index(UsersFavorites) Where UserID=? and Type=2', array(
	$CurUserID
));
$TopicsArray = array();
if ($TagsFollowing)
	$TopicsArray = $DB->query('SELECT `t`.`ID`, `t`.`Topic`, `t`.`Tags`, `t`.`UserID`, `t`.`UserName`, `t`.`LastName`, `t`.`LastTime`, `t`.`Replies`, 
`pt`.`TopicID`  
            FROM ' . PREFIX . 'posttags pt force index(TagsIndex) 
            LEFT JOIN ' . PREFIX . 'topics t ON `t`.`ID` = `pt`.`TopicID`
            WHERE pt.TagID in (?) AND t.IsDel = 0  
            ORDER BY pt.TopicID DESC 
            LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ', ' . ($Config['TopicsPerPage'] + 1),
        ArrayColumn($TagsFollowing, 'FavoriteID'));

if (count($TopicsArray) > $Config['TopicsPerPage']) {
    $IsLastPage = false;
    array_pop($TopicsArray);
} else {
    $IsLastPage = true;
}

$DB->closeConnection();
$PageTitle = $Lang['My_Following_Tags'];
$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$ContentFile = $TemplatePath . 'favorite_tags.php';
include($TemplatePath . 'layout.php');