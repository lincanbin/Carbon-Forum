<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Topics_Statistics' => '主题统计',
	'Posts_Statistics' => '帖子统计',
	'Users_Statistics' => '用户统计',
	'Tags_Statistics' => '话题统计',

	'TotalTopics_Statistics' => '总主题数统计',
	'DaysTopics_Statistics' => '每日新主题数统计',

	'TotalPosts_Statistics' => '总回帖数统计',
	'DaysPosts_Statistics' => '每日新回帖数统计',

	'TotalUsers_Statistics' => '总用户数统计',
	'DaysUsers_Statistics' => '每日新用户数统计',

	'Topics_Number' => '主题数量'
	)
);