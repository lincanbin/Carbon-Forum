<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Set_Your_Username' => 'Укажите ваше имя пользователя',
	'This_User_Name_Already_Exists' => 'Такое имя пользователя уже существует',
	'UserName_Error' => 'Имя пользователя не соответствуют правилам. Имя пользователя должно быть от 4 до 20 символов, при этом не состоящим из одних цифр, может состоять из букв и цифр, а также символов «_», «-»',
	'Binding_Success' => 'Успешно привязан',
	'Binding_Failure' => 'Отказано в привязке'
	));
