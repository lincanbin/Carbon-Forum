<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Last_Reply_From' => 'последний ответ от',
	'Website_Statistics' => 'Статистика сайта',
	'Topics_Number' => 'Всего тем',
	'Posts_Number' => 'Всего сообщений',
	'Tags_Number' => 'Всего тэгов',
	'Users_Number' => 'Всего участников'
	
	));
