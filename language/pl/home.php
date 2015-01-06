<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Last_Reply_From' => 'Ostatnia odpowiedź z',
	'Website_Statistics' => 'Statystyki Strony',
	'Topics_Number' => 'Liczba Tematów',
	'Posts_Number' => 'Liczba Postów',
	'Tags_Number' => 'Liczba Tagów',
	'Users_Number' => 'Liczba Użytkowników'
	
	));