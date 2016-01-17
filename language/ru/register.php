<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Email' => 'Email',
	'Confirm_Password' => 'Подтвердите пароль',
	'This_User_Name_Already_Exists' => 'Такое имя пользователя уже существует',
	'VerificationCode_Error' => 'Ошибка кода подтверждения',
	'Email_Error' => 'E-mail не соответствуют правилам. Пример правильного формата адреса электронной почты "abc@domain.com"',
	'UserName_Error' => 'Имя пользователя не соответствуют правилам. Имя пользователя должно быть от 4 до 20 символов, при этом не состоящим из одних цифр, может состоять из букв и цифр, а также символов «_», «-»',
	'Forms_Can_Not_Be_Empty' => 'Формы, не могут быть пустыми'
	));
