<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Notifications_Replied_To_Me' => '回覆我的',
	'Notifications_Mentioned_Me' => '提到我的',
	'Inbox' => '私信箱',
	'Replied_To_Topic' => '回覆了討論串',
	'Mentioned_Me' => '提到了我',
	));