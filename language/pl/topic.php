<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'People_Collection' => ' collector',
	'People_Have_Seen' => ' odwiedzeń strony',
	'Edit' => 'Edytuj',
	'Quote' => 'cytować',
	'Delete' => 'Usuń',
	'Recover' => 'Odzyskaj',
	'Permanently_Delete' => 'Usuń trwale',
	'Unlock' => 'Odblokuj',
	'Lock' => 'Zablokuj',
	'Sink' => 'Sink',
	'Rise' => 'Rise',
	'Unsubscribe' => 'Odsubskrybuj',
	'Collect' => 'Collect',
	'Replies' => ' Odpowiedzi',
	'Last_Updated_In' => 'Ostatnia aktualizacja: ',
	'Reply' => ' Odpowiedz ',
	'Requirements_For_Login' => 'Wymaga zalogowania się',
	'Topic_Has_Been_Locked' => 'Temat jest zablokowany.'
	));