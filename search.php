<?php
require(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/home.php');
include __DIR__ . '/includes/SearchClient.class.php';

$Page         = Request('Get', 'page');
$Keyword      = Request('Get', 'keyword');
$KeywordArray = array_unique(explode(" ", $Keyword));
$Error        = '';

if (!$KeywordArray) {
	AlertMsg('404 Not Found', '404 Not Found', 404);
}
if ($Page < 0 || $Page == 1)
	Redirect('search/' . $Keyword);
if ($Page == 0)
	$Page = 1;

$SQLKeywordArray = array();//查询关键字参数数组
$AdvancedSearch = false;
$NormalQuery = array();//普通查询条件，用OR连接
$AdvancedQuery = array();//高级查询条件，用AND连接
//关键词预处理
foreach ($KeywordArray as $Key => $KeywordToken) {
	//匹配用户名限制条件
	preg_match('/user:(.*)/i', $KeywordToken, $SearchUserName);
	if (!empty($SearchUserName[1]) && IsName($SearchUserName[1])){
		$AdvancedQuery[] = 'UserName = :user';
		$SQLKeywordArray['user'] = $SearchUserName[1];
		//var_dump($SearchUserName);
		$AdvancedSearch = true;
	} else {
		$ParamName = substr(md5($KeywordToken), 0, 8);
		$NormalQuery[] = 'Topic LIKE :topic' . $ParamName . ' or Tags LIKE :tag' . $ParamName . '';
		$SQLKeywordArray['topic' . $ParamName] = '%' . $KeywordToken . '%';
		$SQLKeywordArray['tag' . $ParamName] = '%' . $KeywordToken . '%';
	}

}
$SearchCondition = array();
$Temp = implode(' AND ', $AdvancedQuery);
if (!empty($Temp)) {
	$SearchCondition[] = $Temp;
}
$Temp = implode(' OR ', $NormalQuery);
if (!empty($Temp)) {
	$SearchCondition[] = '(' . $Temp . ')';
}
unset($Temp);
$SearchConditionQuery = implode(' AND ', $SearchCondition);

//如果定义了搜索服务器，并且不是高级搜索，就走搜索服务
if (defined('SearchServer') && SearchServer && !$AdvancedSearch) {
	try {
		$finds = SearchClient::searchLike($Keyword, 'PostsIndexes' //关键字及索引
			, ($Page - 1) * $Config['TopicsPerPage'], $Config['TopicsPerPage']
			, "" //过滤条件
			, 'PostTime desc' //排序规则
		);
		if (!empty($finds)) {
			$num     = $finds[1];
			$PostIdList = isset($finds[0]['id']) ? $finds[0]['id'] : null;
			if (count($PostIdList) > 0) {
				$TopicsArray = $DB->query('SELECT t.`ID`, `Topic`, `Tags`, t.`UserID`, t.`UserName`, t.`LastName`, `LastTime`, `Replies` 
					, p.Content, p.ID as pID, p.PostTime 
					FROM ' . PREFIX . 'topics  t, ' . PREFIX . 'posts p 
					WHERE t.ID=p.TopicID and p.ID in (?) and t.IsDel=0 
					ORDER BY p.PostTime DESC', $PostIdList);
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
		$TopicsArray = $DB->query('SELECT `ID`, `Topic`, `Tags`, `UserID`, `UserName`, `LastName`, `LastTime`, `Replies` FROM ' . PREFIX . 'topics 
			WHERE ' . $SearchConditionQuery . ' 
			ORDER BY LastTime DESC 
			LIMIT ' . ($Page - 1) * $Config['TopicsPerPage'] . ', ' . $Config['TopicsPerPage'], $SQLKeywordArray);
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
