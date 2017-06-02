<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Inbox' => '私信箱',
	'Chat_With_SB' => '与 {{UserName}} 对话',
	'Message_Content' => '消息内容',
	'Send_Message' => '发送'
));