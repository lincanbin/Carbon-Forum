<?php
require(dirname(__FILE__) . '/common.php');
require(dirname(__FILE__) . '/language/' . ForumLanguage . '/statistics.php');

$StatisticsData   = $DB->query('SELECT * FROM  ' . $Prefix . 'statistics');
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
unset($StatisticsData);
$DataJsonString = json_encode($TotalTopicsStatisticsData);
//$StatisticsDataStringLength = strlen($DataJsonString);
//$DataArrayString = substr($DataJsonString, 1, $StatisticsDataStringLength -2);
//var_dump(json_encode($TotalTopicsStatisticsData));
$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Statistics'];
$ContentFile = $TemplatePath . 'statistics.php';
include($TemplatePath . 'layout.php');