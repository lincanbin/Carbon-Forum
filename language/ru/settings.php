<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Avatar_Settings' => 'Аватар',
	'Profile_Settings' => 'Профиль',
	'Account_Settings' => 'Учетная запись',
	'Security_Settings' => 'Безопасность',
	'Reset_Avatar' => 'Сменить аватар',
	'You_Can_Replace_Your_Avatar_Here' => 'Здесь вы можете сменить свой аватар',
	'Avatar_Image_Format_Support' => 'Поддерживаемые форматы jpg/jpeg/png/gif',
	'Max_Avatar_Size_Limit' => 'Аватар не может превышать 1Мб',
	'Upload_Avatar' => 'Загрузить аватар',

	'Do_Not_Modify' => 'Не выбран',
	'User_Sex' => 'Пол',
	'Sex_Unknown' => 'Неизвестно',
	'Sex_Male' => 'Мужской',
	'Sex_Female' => 'Женский',
	'Email' => 'Email',
	'Ensure_That_Email_Is_Correct' => 'Убедитесь, что адрес электронной почты является правильным',
	'Homepage' => 'Домашняя страница',
	'Introduction' => 'Подпись',
	'Save_Settings' => 'Сохранить настройки',
	'Connect_XXX_Account' => 'Подключите {{AppName}} аккаунт',
	'Current_Password' => 'Текущий пароль',
	'New_Password' => 'Новый пароль',
	'Confirm_New_Password' => 'Повторить пароль',
	'Change_Password' => 'Изменить пароль',

	'Avatar_Upload_Success' => 'Успех',
	'Avatar_Upload_Failure' => 'Отказ',
	'Avatar_Is_Oversize' => 'Аватар слишком большой',

	'Profile_Modified_Successfully' => 'Успех',
	'Profile_Do_Not_Modify' => 'Нет данных изменения',

	'Change_Password_Success' => 'Пароль был успешно изменен, пожалуйста, запомните новый пароль',
	'Change_Password_Failure' => 'Не удалось изменить пароль',
	'Password_Do_Not_Modify' => 'Новый пароль не может быть таким же, как оригинальный пароль. ',
	'Current_Password_Is_Uncorrect' => 'Текущий пароль некорректный',
	'Passwords_Inconsistent' => 'Пароли не совподают',
	'Forms_Can_Not_Be_Empty' => 'Формы, не могут быть пустыми'
	));
