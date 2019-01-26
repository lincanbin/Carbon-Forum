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
	$TopicsArray = $DB->query('SELECT `t`.`ID`, `t`.`Topic`, `t`.`Tags`, `t`.`UserID`, `t`.`UserName`, `t`.`LastName`, `t`.`LastTime`, `t`.`Replies`, 
`pt`.`TopicID` 
		FROM ' . PREFIX . 'posttags pt FORCE INDEX(TagsIndex) 
		LEFT JOIN ' . PREFIX . 'topics t ON `t`.`ID` = `pt`.`TopicID`
		WHERE pt.TagID = :TagID AND t.IsDel = 0 
		ORDER BY pt.TopicID DESC 
		LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . $Config['TopicsPerPage'], 
		array(
			'TagID' => $TagInfo['ID']
		)
	);
else
	$TopicsArray = $DB->query('SELECT `t`.`ID`, `t`.`Topic`, `t`.`Tags`, `t`.`UserID`, `t`.`UserName`, `t`.`LastName`, `t`.`LastTime`, `t`.`Replies`, 
`pt`.`TopicID` 
		FROM ' . PREFIX . 'posttags pt FORCE INDEX(TagsIndex) 
		LEFT JOIN ' . PREFIX . 'topics t ON `t`.`ID` = `pt`.`TopicID`
		WHERE pt.TagID = :TagID AND t.IsDel = 0 
		AND pt.TopicID <= (SELECT `pt`.`TopicID` FROM ' . PREFIX . 'posttags pt FORCE INDEX(TagsIndex) 
			LEFT JOIN ' . PREFIX . 'topics t ON `t`.`ID` = `pt`.`TopicID`
			WHERE pt.TagID = :TagID2 AND t.IsDel = 0 
			ORDER BY pt.TopicID DESC 
			LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ', 1) 
		ORDER BY pt.TopicID DESC 
		LIMIT ' . $Config['TopicsPerPage'], 
		array(
			'TagID' => $TagInfo['ID'],
			'TagID2' => $TagInfo['ID']
		)
	);

if ($CurUserID)
	$IsFavorite = $DB->single("SELECT ID FROM " . PREFIX . "favorites 
		WHERE UserID=:UserID AND Type=2 AND FavoriteID=:FavoriteID", 
		array(
			'UserID' => $CurUserID,
			'FavoriteID' => $TagInfo['ID']
		)
	);
$DB->closeConnection();
$PageTitle = $TagInfo['Name'];
$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$PageMetaDesc = $TagInfo['Name'] . ' - ' . htmlspecialchars(mb_substr(trim(strip_tags($TagInfo['Description'])), 0, 150, 'utf-8'));
$ContentFile  = $TemplatePath . 'tag.php';
include($TemplatePath . 'layout.php');