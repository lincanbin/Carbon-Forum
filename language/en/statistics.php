<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Topics_Statistics' => 'Topics Statistics',
	'Posts_Statistics' => 'Posts Statistics',
	'Users_Statistics' => 'Users Statistics',
	'Tags_Statistics' => 'Tags Statistics',

	'TotalTopics_Statistics' => 'Total number of topics',
	'DaysTopics_Statistics' => 'Daily new topic number',

	'TotalPosts_Statistics' => 'Total number of posts',
	'DaysPosts_Statistics' => 'Daily new post number',

	'TotalUsers_Statistics' => 'Total number of users',
	'DaysUsers_Statistics' => 'Daily new user number',

	'Topics_Number' => 'Topics number'
	)
);