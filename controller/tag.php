<?php
require(LanguagePath . 'tag.php');
$TagName = htmlspecialchars(trim(Request('Get', 'name')));
$Page    = Request('Get', 'page');
$TagInfo = array();
if ($TagName)
	$TagInfo = $DB->row('SELECT * FROM ' . PREFIX . 'tags FORCE INDEX(TagName) 
		WHERE Name=:Name', 
		array(
			'Name' => $TagName
		)
	);
if (empty($TagInfo) || $TagInfo['TotalPosts'] == 0 || ($TagInfo['IsEnabled'] == 0 && $CurUserRole < 3))
	AlertMsg('404 Not Found', '404 Not Found', 404);
$TotalPage = ceil($TagInfo['TotalPosts'] / $Config['TopicsPerPage']);
if ($Page < 0 || $Page == 1) 
	Redirect('tag/' . $TagInfo['Name']);
if ($Page > $TotalPage) 
	Redirect('tag/' . $TagInfo['Name'] . '/page/' . $TotalPage);
if ($Page == 0)
	$Page = 1;
if ($Page <= 10)
	$TagIDArray = $DB->column('SELECT TopicID FROM ' . PREFIX . 'posttags FORCE INDEX(TagsIndex) 
		WHERE TagID=:TagID 
		ORDER BY TopicID DESC 
		LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . $Config['TopicsPerPage'], 
		array(
			'TagID' => $TagInfo['ID']
		)
	);
else
	$TagIDArray = $DB->column('SELECT TopicID FROM ' . PREFIX . 'posttags FORCE INDEX(TagsIndex) 
		WHERE TagID=:TagID 
		AND TopicID<=(SELECT TopicID FROM ' . PREFIX . 'posttags FORCE INDEX(TagsIndex) 
			WHERE TagID=:TagID2 
			ORDER BY TopicID DESC 
			LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',1) 
		ORDER BY TopicID DESC 
		LIMIT ' . $Config['TopicsPerPage'], 
		array(
			'TagID' => $TagInfo['ID'],
			'TagID2' => $TagInfo['ID']
		)
	);
$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
	FROM ' . PREFIX . 'topics FORCE INDEX(PRI) 
	WHERE ID in (?) AND IsDel=0 
	ORDER BY LastTime DESC', 
	$TagIDArray
);
if ($CurUserID)
	$IsFavorite = $DB->single("SELECT ID FROM " . PREFIX . "favorites 
		WHERE UserID=:UserID AND Type=2 AND FavoriteID=:FavoriteID", 
		array(
			'UserID' => $CurUserID,
			'FavoriteID' => $TagInfo['ID']
		)
	);
$DB->CloseConnection();
$PageTitle = $TagInfo['Name'];
$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$PageMetaDesc = $TagInfo['Name'] . ' - ' . htmlspecialchars(mb_substr(trim(strip_tags($TagInfo['Description'])), 0, 150, 'utf-8'));
$ContentFile  = $TemplatePath . 'tag.php';
include($TemplatePath . 'layout.php');