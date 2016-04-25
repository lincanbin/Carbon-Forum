<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Title' => 'Заголовок ',
	'Add_Tags' => 'Добавить тэг (нажать Enter)',
	'Submit' => ' Отправить ',
	'Posting_Too_Often' => 'Частые сообщения. Пожалуйста, повторите попытку позже. ',
	'Tags_Empty' => 'Тэги не могут быть пустым',
	'Prohibited_Content' => 'Ваша тема запрещена к публикации',
	'Too_Long' => 'Длина название не может превышать {{MaxTitleChars}} символов, объем содержания не может превышать {{MaxPostChars}} символов',
	'Title_Empty' => 'Название не может быть пустым',

	'Tags' => 'Тэги',
	'Content' => 'Содержание'
	));
