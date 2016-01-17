<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'People_Collection' => ' коллекция',
	'People_Have_Seen' => ' просмотры',
	'Edit' => 'Редактировать',
	'Quote' => 'Цитата',
	'Delete' => 'Удалить',
	'Recover' => 'Восстановить',
	'Permanently_Delete' => 'Полное удаление',
	'Unlock' => 'Открыть',
	'Lock' => 'Закрыть',
	'Sink' => 'Опустить',
	'Rise' => 'Поднять',
	'Unsubscribe' => 'Отписаться',
	'Collect' => 'Подписаться',
	'Replies' => ' Ответов',
	'Last_Updated_In' => 'Последнее обновление ',
	'Reply' => ' Ответить ',
	'Requirements_For_Login' => 'Требуется вход в систему',
	'Topic_Has_Been_Locked' => 'Эта тема закрыта, вы не можете редактировать и оставлять сообщения в ней.',
	'Add_Tags' => 'Добавьте тэг (нажмите Enter)',
	'Edit_Tags' => 'Редактировать Тэги',
	'Complete_Edit_Tags' => 'Завершить'
	));
