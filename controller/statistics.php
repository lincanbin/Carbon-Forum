<?php
require(LanguagePath . 'statistics.php');
//数据没问题的情况下不需要排序，拿出来顺序就是好的，并且顺序可以通过刷新缓存修复。
//因此这里不建索引，也不排序，节约资源。
$StatisticsData            = $DB->query('SELECT * FROM  ' . PREFIX . 'statistics');
$TotalForumStatisticsData = array();
foreach ($StatisticsData as $Key => $Value) {
	$TotalForumStatisticsData[] = array(
		$Value['DaysDate'],
		$Value['TotalTopics'],
		$Value['TotalPosts'],
		$Value['TotalUsers'],
		$Value['DaysTopics'],
		$Value['DaysPosts'],
		$Value['DaysUsers']
	);
}
$TotalForumStatisticsData[] = array(
	date('Y-m-d'),
	$Config['NumTopics'],
	$Config['NumPosts'],
	$Config['NumUsers'],
	$Config['DaysTopics'],
	$Config['DaysPosts'],
	$Config['DaysUsers']
);
unset($StatisticsData);
$DataJsonString = json_encode($TotalForumStatisticsData);
unset($TotalForumStatisticsData);

//话题统计的treemap
$TagsStatisticsData = array();
foreach ($DB->query('SELECT ID,Name,TotalPosts FROM ' . PREFIX . 'tags 
		WHERE IsEnabled=1 AND TotalPosts>0
		ORDER BY TotalPosts DESC 
		LIMIT ' . $Config['TopicsPerPage'] * 10) as $Key => $Value) {
	$TagsStatisticsData[] = array(
		'value' => $Value['TotalPosts'],
		'name' => $Value['Name']
	);
}
$TagsDataJsonString = json_encode($TagsStatisticsData);
unset($TagsStatisticsData);

$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Statistics'];
$ContentFile = $TemplatePath . 'statistics.php';
include($TemplatePath . 'layout.php');