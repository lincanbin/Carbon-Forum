<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Login_Expiration_Time' => 'Czas bez wylogowywanie',
	'Days' => 'Dni',
	'Password_Error' => 'Niepoprawne hasło',
	'User_Does_Not_Exist' => 'Użytkownik nie istnieje',
	'Verification_Code_Error' => 'Niepoprawny kod weryfikacyjny',
	'Forms_Can_Not_Be_Empty' => 'Pole nie może być puste'
	));