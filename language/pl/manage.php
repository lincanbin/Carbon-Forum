<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Deleted' => 'Usunięty',
	'Recovered' => 'Odzyskany',
	'Failure_Recovery' => 'Nieudane odzyskanie',

	'Permanently_Deleted' => 'Permamentnie usunięty',
	'Failure_Permanent_Deletion' => 'Nieudane permamentne usunięcie',

	'Sunk' => 'Sunk',
	'Risen' => 'Risen',

	'Lock' => 'Zablokuj',
	'Unlock' => 'Odblokuj',

	'Block_User' => 'Zablokuj tego użytkownika',
	'Unblock_User' => 'Odblokuj użytkownika',

	'Follow' => 'Śledź',
	'Unfollow' => 'Przestań śledzić',

	'Unsubscribe' => 'Odsubskrybuj',
	'Collect' => 'Subskrybuj',

	'Prohibited_Content' => 'Twój temat jest zabronione publikowanie',
	'Do_Not_Modify' => 'Nie modyfikuj',
	'Edited' => 'Edytowany',
	'Failure_Edit' => 'Wystąpił błąd podczas edycji',

	'Icon_Upload_Success' => 'Prześlij Sukces',
	'Icon_Upload_Failure' => 'Prześlij Niezastosowanie',
	'Icon_Is_Oversize' => 'Ikona nie może przekroczyć 1MiB',

	'Enable_Tag' => 'Włącz tag',
	'Disable_Tag' => 'Wyłącz tag',

	'Reset_Avatar_Successfully' => 'Zresetować awatar powodzeniem! '
	));