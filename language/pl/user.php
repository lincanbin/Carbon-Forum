<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Unfollow' => 'Przestań śledzić',
	'Follow' => 'Śledź',
	'Block_User' => 'Zablokuj tego użytkownika',
	'Unblock_User' => 'Odblokuj użytkownika',
	'Reset_Avatar' => 'Zresetować Avatar',
	'Registered_In' => 'Zarejestrowany',
	'Topics_Number' => 'Liczba tematów',
	'Posts_Number' => 'Liczba postów',
	'Homepage' => 'Strona',
	'Introduction' => 'O sobie',
	'Last_Activity_In' => 'Ostatnia aktywność',
	'Created_Topic' => 'Stworzył teamt',
	'Replied_To_Topic' => 'Odpowiedział w temacie'
	));