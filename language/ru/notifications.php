<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Notifications_Replied_To_Me' => 'Ответил мне',
	'Notifications_Mentioned_Me' => 'Напоминания мне',
	'Replied_To_Topic' => 'ответил в теме',
	'Mentioned_Me' => 'напоминания мне',
	));
