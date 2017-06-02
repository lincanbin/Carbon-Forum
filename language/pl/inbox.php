<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array (
  'Chat_With_SB' => 'Dialog z {{UserName}}',
  'Message_Content' => 'treść wiadomości',
  'Send_Message' => 'wysłać',
  'Inbox' => 'Prywatna poczta',
));