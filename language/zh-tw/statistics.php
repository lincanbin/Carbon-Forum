<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Topics_Statistics' => '主題統計',
	'Posts_Statistics' => '帖子統計',
	'Users_Statistics' => '用戶統計',

	'TotalTopics_Statistics' => '總主題數統計',
	'DaysTopics_Statistics' => '每日新主題數統計',

	'TotalPosts_Statistics' => '總回帖數統計',
	'DaysPosts_Statistics' => '每日新回帖數統計',

	'TotalUsers_Statistics' => '總用戶數統計',
	'DaysUsers_Statistics' => '每日新用戶數統計'
	)
);