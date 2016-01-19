<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Topics_Statistics' => 'Тем',
	'Posts_Statistics' => 'Сообщений',
	'Users_Statistics' => 'Пользователей',
	'Tags_Statistics' => 'Тэгов',

	'TotalTopics_Statistics' => 'Всего тем',
	'DaysTopics_Statistics' => 'Новых тем за день ',

	'TotalPosts_Statistics' => 'Всего сообщений',
	'DaysPosts_Statistics' => 'Новых сообщений за день ',

	'TotalUsers_Statistics' => 'Всего пользователей',
	'DaysUsers_Statistics' => 'Новых пользователей за день',

	'Topics_Number' => 'Количество тем'
	)
);
