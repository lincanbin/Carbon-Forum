<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Notifications_Replied_To_Me' => 'Replied To Me',
	'Notifications_Mentioned_Me' => 'Mentioned Me',
	'Inbox' => 'Inbox',
	'Replied_To_Topic' => 'replied to topic',
	'Mentioned_Me' => 'mentioned Me',
	));