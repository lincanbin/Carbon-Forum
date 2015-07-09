<?php
define('FullTableScanTopicLimit', 50000);//当全站主题数量小于该值将会使用更消耗资源的全表扫描搜索
require(dirname(__FILE__) . '/common.php');
require(dirname(__FILE__) . '/language/' . ForumLanguage . '/home.php');
$Page         = Request('Get', 'page');
$Keyword      = Request('Get', 'keyword');
$KeywordArray = explode(" ", $Keyword);
$KeywordNum   = count($KeywordArray);
if(!$KeywordNum){
	AlertMsg('404 Not Found', '404 Not Found', 404);
}
if ($Page < 0 || $Page == 1) {
	header('location: ' . $Config['WebsitePath'] . '/search/' . $Keyword);
	exit;
}
if ($Page == 0)
	$Page = 1;

//if($CurUserID && $Config['NumTopics'] <= FullTableScanTopicLimit){
if($Config['NumTopics'] <= FullTableScanTopicLimit){
	$QueryString = str_repeat('or Topic LIKE ? or Tags LIKE ? ', $KeywordNum-1);
	$SQLKeywordArray = array();
	foreach ($KeywordArray as $Value) {
		$SQLKeywordArray[] = '%'.$Value.'%';
		$SQLKeywordArray[] = '%'.$Value.'%';
	}
	$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` FROM ' . $Prefix . 'topics 
		WHERE Topic LIKE ? or Tags LIKE ? '.$QueryString.'
		ORDER BY LastTime DESC 
		LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . $Config['TopicsPerPage'], 
		$SQLKeywordArray
	);
}else{
	$QueryString = str_repeat('or Name LIKE ? ', $KeywordNum-1);
	$SQLKeywordArray = array();
	foreach ($KeywordArray as $Value) {
		$SQLKeywordArray[] = '%'.$Value.'%';
	}
	$TagIDList = $DB->column('SELECT ID FROM ' . $Prefix . 'tags 
		WHERE Name like ? '.$QueryString, 
		$SQLKeywordArray
	);
	if (!$TagIDList)
		AlertMsg('404 Not Found', '404 Not Found', 404);
	$TagIDArray = $DB->column('SELECT TopicID FROM ' . $Prefix . 'posttags 
		WHERE TagID in (?) 
		ORDER BY TopicID DESC 
		LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ',' . $Config['TopicsPerPage'], 
		$TagIDList);
	$TopicsArray = array();
	if($TagIDArray){
		$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` FROM ' . $Prefix . 'topics 
			force index(PRI) 
			WHERE ID in (?) and IsDel=0 
			ORDER BY LastTime DESC', 
			$TagIDArray);
	}
}
/*
if($Page == 1 && !$TopicsArray){
	AlertMsg('404 Not Found', '404 Not Found', 404);
}
*/
$DB->CloseConnection();
$PageTitle = $Lang['Search'].' '.$Keyword.' ';
$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$ContentFile  = $TemplatePath . 'search.php';
include($TemplatePath . 'layout.php');