<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Email' => 'Email',
	'Submit' => 'Принять',
	'Forms_Can_Not_Be_Empty' => 'Формы не могут быть пустым',
	'User_Does_Not_Exist' => 'Пользователь не существует',
	'Email_Error' => 'Ошибки ввода электронной почты, имя пользователя или электронный адрес: {{UserMail}}',
	'Verification_Code_Error' => 'Ошибка проверочного кода',
	'Email_Has_Been_Sent' => 'Пожалуйста, войдите на свою почту для сброса пароля. ',
	'Email_Could_Not_Be_Sent' => 'Письмо не может быть отправлено. ',
	'Mail_Template_Subject' => '{{UserName}}, сброс пароля - {{SiteName}}',
	'Mail_Template_Body' => '<p>{{UserName}}, 
		пожалуйста, нажмите на гиперссылку восстановить пароль за два часа: </p>
		<p><a href="{{ResetPasswordURL}}">{{ResetPasswordURL}}</a></p>',
	));
