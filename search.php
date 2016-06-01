<?php
define('FullTableScanTopicLimit', 50000); //当全站主题数量小于该值将会使用更消耗资源的全表扫描搜索
require(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/home.php');
include __DIR__ . '/includes/SearchClient.class.php';

$Page         = Request('Get', 'page');
$Keyword      = Request('Get', 'keyword');
$KeywordArray = explode(" ", $Keyword);
$Error        = '';

if (!$KeywordArray) {
	AlertMsg('404 Not Found', '404 Not Found', 404);
}
if ($Page < 0 || $Page == 1)
	Redirect('search/' . $Keyword);
if ($Page == 0)
	$Page = 1;

$SQLKeywordArray = array();//查询关键字数组
//关键词预处理
foreach ($KeywordArray as $Key => $KeywordToken) {
	preg_match('/user:(.*)/i', $KeywordToken, $SearchUserName);
	$UserNameQueryString = '';
	if (!empty($SearchUserName[1]) && IsName($SearchUserName[1])){
		$UserNameQueryString = 'UserName = ? AND';
		$SQLKeywordArray[] = $SearchUserName[1];
		//var_dump($SearchUserName);
		unset($KeywordArray[$Key]);
		break;
	}
}
ksort($KeywordArray);
//var_dump($KeywordArray);

$KeywordNum   = count($KeywordArray);
if (!$KeywordNum) {
	$KeywordArray[] = '';
}
//如果定义了搜索服务器，就走搜索服务
if (defined('SearchServer') && SearchServer) {
	try {
		$finds = SearchClient::searchLike($Keyword, 'PostsIndexes' //关键字及索引
			, ($Page - 1) * $Config['TopicsPerPage'], $Config['TopicsPerPage']
			, "" //过滤条件
			, 'PostTime desc' //排序规则
		);
		if (!empty($finds)) {
			$num     = $finds[1];
			$postIds = isset($finds[0]['id']) ? $finds[0]['id'] : null;
			if (count($postIds) > 0) {
				$TopicsArray = $DB->query('SELECT t.`ID`, `Topic`, `Tags`, t.`UserID`, t.`UserName`, t.`LastName`, `LastTime`, `Replies` 
					, p.Content, p.ID as pID, p.PostTime 
					FROM ' . PREFIX . 'topics  t, ' . PREFIX . 'posts p 
					WHERE t.ID=p.TopicID and p.ID in (?) and t.IsDel=0 
					ORDER BY p.PostTime DESC', $postIds);
				foreach ($TopicsArray as &$row) {
					$excerpts          = SearchClient::callProxy('buildExcerpts', array(
						array(
							$row['Topic'],
							$row['Content']
						),
						'PostsIndexes',
						$Keyword,
						array(
							"before_match" => '<span class="search-keyword">',
							"after_match" => "</span>"
						)
					));
					$row['MinContent'] = $excerpts[1];
				}
			}
		}
	}
	catch (Exception $e) {
		$Error = $e->getMessage();
	}
} else {
	//if($CurUserID && $Config['NumTopics'] <= FullTableScanTopicLimit){
	if ($Config['NumTopics'] <= FullTableScanTopicLimit) {
		$QueryString     = str_repeat('or Topic LIKE ? or Tags LIKE ? ', $KeywordNum - 1);
		foreach ($KeywordArray as $Value) {
			$SQLKeywordArray[] = '%' . $Value . '%';
			$SQLKeywordArray[] = '%' . $Value . '%';
		}
		$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` FROM ' . PREFIX . 'topics 
			WHERE ' . $UserNameQueryString . ' (Topic LIKE ? or Tags LIKE ? ' . $QueryString . ')
			ORDER BY LastTime DESC 
			LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ', ' . $Config['TopicsPerPage'], $SQLKeywordArray);
	} else {
		$QueryString     = str_repeat('or Name LIKE ? ', $KeywordNum - 1);
		$SQLKeywordArray = array();
		foreach ($KeywordArray as $Value) {
			$SQLKeywordArray[] = '%' . $Value . '%';
		}
		$TagIDList = $DB->column('SELECT ID FROM ' . PREFIX . 'tags 
			WHERE Name like ? ' . $QueryString, $SQLKeywordArray);
		if (!$TagIDList)
			AlertMsg('404 Not Found', '404 Not Found');
		$TagIDArray  = $DB->column('SELECT TopicID FROM ' . PREFIX . 'posttags 
			WHERE TagID in (?) 
			ORDER BY TopicID DESC 
			LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ', ' . $Config['TopicsPerPage'], $TagIDList);
		$TopicsArray = array();
		if ($TagIDArray) {
			$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` FROM ' . PREFIX . 'topics 
				force index(PRI) 
				WHERE ID in (?) and IsDel=0 
				ORDER BY LastTime DESC', $TagIDArray);
		}
	}
}
/*
if($Page == 1 && !$TopicsArray){
AlertMsg('404 Not Found', '404 Not Found', 404);
}
*/
$DB->CloseConnection();
$PageTitle = $Lang['Search'] . ' ' . $Keyword . ' ';
$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$ContentFile = $TemplatePath . 'search.php';
include($TemplatePath . 'layout.php');
