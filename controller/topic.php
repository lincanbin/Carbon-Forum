<?php
require(LanguagePath . 'topic.php');
$ID   = intval(Request('Request', 'id'));
$Page = intval(Request('Request', 'page'));

$Topic = $MCache ? $MCache->get(MemCachePrefix . 'Topic_' . $ID) : array();
if (empty($Topic)) {
	$Topic = $DB->row("SELECT * FROM " . PREFIX . "topics 
		FORCE INDEX(PRI) 
		WHERE ID=:ID", array(
		'ID' => $ID
	));
	if ($MCache) {
		$MCache->set(MemCachePrefix . 'Topic_' . $ID, $Topic, 86400);
	}
}

if (!$Topic || ($Topic['IsDel'] && $CurUserRole < 3)) {
	AlertMsg('404 Not Found', '404 Not Found', 404);
}
$TotalPage = ceil(($Topic['Replies'] + 1) / $Config['PostsPerPage']);
if (($Page < 0 || $Page == 1) && !$IsApp)
	Redirect('t/' . $ID);
if ($Page > $TotalPage) 
	Redirect('t/' . $ID . '-' . $TotalPage);
if ($Page == 0)
	$Page = 1;
$PostsArray = $DB->query("SELECT `ID`, `TopicID`,`UserID`, `UserName`, `Content`, `PostTime`, `IsDel` 
	FROM " . PREFIX . "posts 
	FORCE INDEX(TopicID) 
	WHERE TopicID=:id 
	ORDER BY PostTime ASC 
	LIMIT " . ($Page - 1) * $Config['PostsPerPage'] . "," . $Config['PostsPerPage'], array(
	"id" => $ID
));
if ($CurUserID) {
	$IsFavorite = intval($DB->single("SELECT ID 
		FROM " . PREFIX . "favorites 
		WHERE UserID=:UserID and Type=1 AND FavoriteID=:FavoriteID", array(
		'UserID' => $CurUserID,
		'FavoriteID' => $ID
	)));
}
//更新浏览量
if ($MCache) {
	$TopicViews = $MCache->get(MemCachePrefix . 'Topic_Views_' . $ID);
	//30天内攒满200次点击，Update一次数据库数据
	if ($TopicViews && ($TopicViews - $Topic['Views']) >= 200) {
		$DB->query("UPDATE " . PREFIX . "topics 
			FORCE INDEX(PRI) 
			SET Views = :Views,LastViewedTime = :LastViewedTime Where ID=:ID", array(
			'Views' => $TopicViews + 1,
			"LastViewedTime" => $TimeStamp,
			"ID" => $ID
		));
		//清理主题缓存
		$MCache->delete(MemCachePrefix . 'Topic_' . $ID);
	}
	$Topic['Views'] = (($TopicViews) ? $TopicViews : $Topic['Views']) + 1;
	$MCache->set(MemCachePrefix . 'Topic_Views_' . $ID, $Topic['Views'], 86400 * 30);
} else {
	$DB->query("UPDATE " . PREFIX . "topics 
		FORCE INDEX(PRI) 
		SET Views = Views+1,LastViewedTime = :LastViewedTime Where ID=:ID", array(
		"LastViewedTime" => $TimeStamp,
		"ID" => $ID
	));
}
//当回复内容与欲回复内容会同页时，不显示引用按钮
if ($Page != $TotalPage || ($Topic['Replies'] + 1) % $Config['PostsPerPage'] == 0) {
	$EnableQuote = true;
} else {
	$EnableQuote = false;
}
$DB->CloseConnection();
$PageTitle = $Topic['Topic'];
$PageTitle .= $Page > 1 ? ' Page' . $Page : '';
$PageMetaDesc    = htmlspecialchars(mb_substr(trim(strip_tags($PostsArray[0]['Content'])), 0, 150, 'utf-8'));
$PageMetaKeyword = str_replace('|', ',', $Topic['Tags']);
$ContentFile     = $TemplatePath . 'topic.php';
include($TemplatePath . 'layout.php');