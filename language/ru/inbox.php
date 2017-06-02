<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array (
  'Chat_With_SB' => 'Диалог с {{UserName}}',
  'Message_Content' => 'содержание сообщения',
  'Send_Message' => 'послать',
  'Inbox' => 'Частная почта',
));