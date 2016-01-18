<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Topics_Statistics' => '主題統計',
	'Posts_Statistics' => '貼文統計',
	'Users_Statistics' => '使用者統計',
	'Tags_Statistics' => '话题統計',

	'TotalTopics_Statistics' => '總主題數量統計',
	'DaysTopics_Statistics' => '每日新主題數量統計',

	'TotalPosts_Statistics' => '總回覆數量統計',
	'DaysPosts_Statistics' => '每日新回覆數量統計',

	'TotalUsers_Statistics' => '總使用者數量統計',
	'DaysUsers_Statistics' => '每日新使用者數量統計',

	'Topics_Number' => '主題數量'
	)
);
