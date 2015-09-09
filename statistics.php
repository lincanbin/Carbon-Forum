<?php
require(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/statistics.php');
//数据没问题的情况下不需要排序，拿出来顺序就是好的，并且顺序可以通过刷新缓存修复
$StatisticsData            = $DB->query('SELECT * FROM  ' . $Prefix . 'statistics');
$TotalTopicsStatisticsData = array();
foreach ($StatisticsData as $Key => $Value) {
	$TotalTopicsStatisticsData[] = array(
		$Value['DaysDate'],
		$Value['TotalTopics'],
		$Value['TotalPosts'],
		$Value['TotalUsers'],
		$Value['DaysTopics'],
		$Value['DaysPosts'],
		$Value['DaysUsers']
	);
}
$TotalTopicsStatisticsData[] = array(
	date('Y-m-d'),
	$Config['NumTopics'],
	$Config['NumPosts'],
	$Config['NumUsers'],
	$Config['DaysTopics'],
	$Config['DaysPosts'],
	$Config['DaysUsers']
);
unset($StatisticsData);
$DataJsonString = json_encode($TotalTopicsStatisticsData);
unset($TotalTopicsStatisticsData);

$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Statistics'];
$ContentFile = $TemplatePath . 'statistics.php';
include($TemplatePath . 'layout.php');