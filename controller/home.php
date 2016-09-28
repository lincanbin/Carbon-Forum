<?php
require(LanguagePath . 'home.php');
$Page      = intval(Request('Request', 'page'));
$TotalPage = ceil($Config['NumTopics'] / $Config['TopicsPerPage']);
if (($Page < 0 || $Page == 1) && !$IsApp) 
	Redirect();
if ($Page > $TotalPage) 
	Redirect('page/' . $TotalPage);
if ($Page == 0)
	$Page = 1;
$TopicsArray = array();
if ($MCache && $Page == 1) {
	$TopicsArray = $MCache->get(MemCachePrefix . 'Homepage');
}
if (!$TopicsArray) {
	if ($Page <= 10) {
		$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
			FROM ' . PREFIX . 'topics force index(LastTime) 
			WHERE IsDel=0 
			ORDER BY LastTime DESC 
			LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . $Config['TopicsPerPage']);
		if ($MCache && $Page == 1) {
			$MCache->set(MemCachePrefix . 'Homepage', $TopicsArray, 600);
		}
	} else {
		$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
			FROM ' . PREFIX . 'topics force index(LastTime) 
			WHERE LastTime<=(SELECT LastTime 
					FROM ' . PREFIX . 'topics force index(LastTime) 
					WHERE IsDel=0 
					ORDER BY LastTime DESC 
					LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ', 1) 
				and IsDel=0 
			ORDER BY LastTime DESC 
			LIMIT ' . $Config['TopicsPerPage']);
	}
}
$DB->CloseConnection();
$PageTitle = $Page > 1 ? ' Page' . $Page . '-' : '';
$PageTitle .= $Config['SiteName'];
$PageMetaDesc = htmlspecialchars(mb_substr($Config['SiteDesc'], 0, 150, 'utf-8'));
$ContentFile  = $TemplatePath . 'home.php';
include($TemplatePath . 'layout.php');