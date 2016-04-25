<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Posting_Too_Often' => 'Частые сообщения. Пожалуйста, повторите попытку позже. ',
	'Topic_Has_Been_Locked' => 'Тема заблокирована',
	'Too_Long' => 'Объем содержания не может превышать {{MaxPostChars}} символов',
	'Content_Empty' => 'Содержание не может быть пустым',
	'Prohibited_Content' => 'Ваша тема запрещена к публикации',
	));
