<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array (
  'Chat_With_SB' => 'Talk to {{UserName}}',
  'Message_Content' => 'Message content',
  'Send_Message' => 'send',
  'Inbox' => 'Inbox',
));