<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Notifications_Replied_To_Me' => 'Odpowiedział Ci',
	'Notifications_Mentioned_Me' => 'Wspomniano o Tobie',
	'Replied_To_Topic' => 'odpowiedział w temacie',
	'Mentioned_Me' => 'wspomniał o Tobie',
	));