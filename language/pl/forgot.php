<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Email' => 'E-mail',
	'Submit' => 'przedkładać',
	'Forms_Can_Not_Be_Empty' => 'Formy nie może być pusty',
	'User_Does_Not_Exist' => 'Użytkownik nie istnieje',
	'Email_Error' => 'Błędy wejściowe e-mail, użytkownik \'s adres e-mail jest: {{UserMail}}',
	'Verification_Code_Error' => 'Kod weryfikacyjny błędów',
	'Email_Has_Been_Sent' => 'Zaloguj się e-mail, aby zresetować hasło.',
	'Email_Could_Not_Be_Sent' => 'E-mail nie może zostać wysłany.',
	'Mail_Template_Subject' => '{{UserName}}, Zresetuj swoje hasło - {{SiteName}}',
	'Mail_Template_Body' => '<p>{{UserName}}, 
		kliknij hiperłącze, aby zresetować hasło w dwóch godzin: </p>
		<p><a href="{{ResetPasswordURL}}">{{ResetPasswordURL}}</a></p>',
	));