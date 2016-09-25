<?php
require(LanguagePath . 'home.php');
$Page      = intval(Request('Request', 'page'));
if (($Page < 0 || $Page == 1) && !$IsApp)
    Redirect('recycle-bin');
if ($Page == 0)
    $Page = 1;
$TopicsArray = array();

if (!$TopicsArray) {
    if ($Page <= 50) {
        $TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
			FROM ' . PREFIX . 'topics force index(LastTime) 
			WHERE IsDel=1 
			ORDER BY LastTime DESC 
			LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . ($Config['TopicsPerPage'] + 1));
    } else {
        $TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` 
			FROM ' . PREFIX . 'topics force index(LastTime) 
			WHERE LastTime<=(SELECT LastTime 
					FROM ' . PREFIX . 'topics force index(LastTime) 
					WHERE IsDel=1 
					ORDER BY LastTime DESC 
					LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ', 1) 
				and IsDel=0 
			ORDER BY LastTime DESC 
			LIMIT ' . ($Config['TopicsPerPage'] + 1));
    }
}
$DB->CloseConnection();

if (count($TopicsArray) > $Config['TopicsPerPage']) {
    $IsLastPage = false;
    array_pop($TopicsArray);
} else {
    $IsLastPage = true;
}

$PageTitle = $Page > 1 ? ' Page' . $Page . '-' : '';
$PageTitle .= $Lang['Recycle_Bin'];
$ContentFile  = $TemplatePath . 'recycle_bin.php';
include($TemplatePath . 'layout.php');