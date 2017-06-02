<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array (
  'Chat_With_SB' => '與 {{UserName}} 對話',
  'Message_Content' => '消息內容',
  'Send_Message' => '發送',
  'Inbox' => '私信箱',
));