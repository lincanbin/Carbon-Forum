<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Tag' => 'Тэг',
	'Last_Reply_From' => 'последний ответ от',
	'Followers' => ' подписчиков',
	'Topics' => ' тем',
	'Created_In' => 'Создан ',
	'Last_Updated_In' => 'Последнее обновление ',
	'Follow' => 'Подписаться',
	'Unfollow' => 'Отписаться',

	'Upload_A_New_Icon' => 'Загрузить новую иконку',
	'Enable_Tag' => 'Включить тег',
	'Disable_Tag' => 'Отключить тег',
	'Edit_Description' => 'Редактировать описание',
	'Submit' => 'Принять',
	'Cancel' => 'Отменить',

	'Website_Statistics' => 'Статистика форума',
	'Topics_Number' => 'Количество тем',
	'Posts_Number' => 'Количество сообщений',
	'Tags_Number' => 'Количество тэгов',
	'Users_Number' => 'Количество пользователей'
	
	));
