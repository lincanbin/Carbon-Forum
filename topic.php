<?php
require(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/topic.php');
$ID   = intval($_GET['id']);
$Page = intval(Request('Get', 'page'));
if ($MCache) {
	$Topic = $MCache->get(MemCachePrefix . 'Topic_' . $ID);
	if (!$Topic) {
		$Topic = $DB->row("SELECT * FROM " . $Prefix . "topics 
			FORCE INDEX(PRI) 
			WHERE ID=:ID", array(
			'ID' => $ID
		));
		$MCache->set(MemCachePrefix . 'Topic_' . $ID, $Topic, 86400);
	}
} else {
	$Topic = $DB->row("SELECT * FROM " . $Prefix . "topics 
		FORCE INDEX(PRI) 
		WHERE ID=:ID", array(
		'ID' => $ID
	));
}

if (!$Topic || ($Topic['IsDel'] && $CurUserRole < 3)) {
	AlertMsg('404 Not Found', '404 Not Found', 404);
}
$TotalPage = ceil(($Topic['Replies'] + 1) / $Config['PostsPerPage']);
if (($Page < 0 || $Page == 1) && !$IsApp) {
	header('location: ' . $Config['WebsitePath'] . '/t/' . $ID);
	exit;
}
if ($Page > $TotalPage) {
	header('location: ' . $Config['WebsitePath'] . '/t/' . $ID . '-' . $TotalPage);
	exit;
}
if ($Page == 0)
	$Page = 1;
$PostsArray = $DB->query("SELECT `ID`, `TopicID`,`UserID`, `UserName`, `Content`, `PostTime`, `IsDel` 
	FROM " . $Prefix . "posts 
	FORCE INDEX(TopicID) 
	WHERE TopicID=:id 
	ORDER BY PostTime ASC 
	LIMIT " . ($Page - 1) * $Config['PostsPerPage'] . "," . $Config['PostsPerPage'], array(
	"id" => $ID
));
if ($CurUserID) {
	$IsFavorite = intval($DB->single("SELECT ID 
		FROM " . $Prefix . "favorites 
		WHERE UserID=:UserID and Type=1 AND FavoriteID=:FavoriteID", array(
		'UserID' => $CurUserID,
		'FavoriteID' => $ID
	)));
}
//更新浏览量
if ($MCache) {
	$TopicViews = $MCache->get(MemCachePrefix . 'Topic_Views_' . $ID);
	//十天内攒满100次点击，Update一次数据库数据
	if ($TopicViews && ($TopicViews - $Topic['Views']) >= 100) {
		$DB->query("UPDATE " . $Prefix . "topics 
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
	$MCache->set(MemCachePrefix . 'Topic_Views_' . $ID, $Topic['Views'], 864000);
} else {
	$DB->query("UPDATE " . $Prefix . "topics 
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