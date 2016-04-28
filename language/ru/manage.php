<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Deleted' => 'Удалены',
	'Recovered' => 'Восстановленные',
	'Failure_Recovery' => 'Восстановление после сбоя',

	'Permanently_Deleted' => 'Окончательно удалены',
	'Failure_Permanent_Deletion' => 'Отказано окончательно удалять',

	'Sunk' => 'Опустить',
	'Risen' => 'Поднять',

	'Lock' => 'Закрыть',
	'Unlock' => 'Открыть',

	'Block_User' => 'Заблокировать пользователя',
	'Unblock_User' => 'Разблокировать пользователя',

	'Follow' => 'Подписаться',
	'Unfollow' => 'Отписаться',

	'Unsubscribe' => 'Отписаться',
	'Collect' => 'Подписки',

	'Prohibited_Content' => 'Ваша тема запрещена к публикации',
	'Do_Not_Modify' => 'Не выбрано',
	'Edited' => 'Редактировать',
	'Failure_Edit' => 'Отказано редактировать',

	'Icon_Upload_Success' => 'Успешно загружено',
	'Icon_Upload_Failure' => 'Неудачная загрузка',
	'Icon_Is_Oversize' => 'Икона не может превышать 1Мб',

	'Enable_Tag' => 'Включить тэг',
	'Disable_Tag' => 'Отключить тэг',

	'Reset_Avatar_Successfully' => 'Успешная смена аватара! '
	));
