<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Reset_Password' => 'Сброс пароля',
	'Page_Has_Expired' => 'Истек срок действия страницы',
	'New_Password' => 'Новый пароль',
	'Confirm_New_Password' => 'Подтвердите новый пароль',
	'VerificationCode_Error' => 'Ошибка проверочного кода',
	'Passwords_Inconsistent' => 'Пароли не совподают',
	'Forms_Can_Not_Be_Empty' => 'Формы, не могут быть пустыми',
	'Reset_Password_Success' => 'Пароль успешно сброшен',
	'Reset_Password_Failure' => 'Неудача сброса пароля'
	));
