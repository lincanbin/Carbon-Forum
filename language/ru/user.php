<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Unfollow' => 'Отписаться',
	'Follow' => 'Подписаться',
	'Block_User' => 'Заблокировать',
	'Unblock_User' => 'Разблокировать',
	'Reset_Avatar' => 'Сменить аватар',
	'Registered_In' => 'Зарегистрирован',
	'Topics_Number' => 'Количество тем',
	'Posts_Number' => 'Количество сообщений',
	'Homepage' => 'Домашняя страница',
	'Introduction' => 'Подпись',
	'Last_Activity_In' => 'Последний раз был',
	'Created_Topic' => 'Создал тему',
	'Replied_To_Topic' => 'Ответил в теме'
	));
