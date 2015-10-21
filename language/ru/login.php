<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Login_Expiration_Time' => 'Время сессии',
	'Days' => 'Дней',
	'Password_Error' => 'Ошибка пароля',
	'User_Does_Not_Exist' => 'Пользователь не существует',
	'Verification_Code_Error' => 'Ошибка проверочного кода',
	'Forms_Can_Not_Be_Empty' => 'Формы не могут быть пустым'
	));
