<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Topics_Statistics' => 'Tematy Statystyki',
	'Posts_Statistics' => 'Posty Statystyki',
	'Users_Statistics' => 'Statystyki użytkowników',
	'Tags_Statistics' => 'Tags Statistics',

	'TotalTopics_Statistics' => 'Ilość tematów',
	'DaysTopics_Statistics' => 'Codziennie nowy numer temat',

	'TotalPosts_Statistics' => 'Ilość postów',
	'DaysPosts_Statistics' => 'Codziennie po nowy numer',

	'TotalUsers_Statistics' => 'Łączna liczba użytkowników',
	'DaysUsers_Statistics' => 'Codziennie nowy numer użytkownika',

	'Topics_Number' => 'Topics number'
	)
);