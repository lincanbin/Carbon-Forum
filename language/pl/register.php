<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Email' => 'Email',
	'Confirm_Password' => 'Powtórz Hasło',
	'This_User_Name_Already_Exists' => 'Taki użytkownik już istnieje',
	'VerificationCode_Error' => 'Kod z obrazka jest niepoprawny',
	'Email_Error' => 'Niepoprawny format adresu e-mail',
	'UserName_Error' => 'Nazwa Użytkownika nie spełnia wymagań. Musi mieć od 4 do 20 znaków, nie same cyfry, może zawierać litery, cyfry, symbole "_", "-"',
	'Passwords_Inconsistent' => 'Hasła do siebie nie pasują',
	'Forms_Can_Not_Be_Empty' => 'Pola nie mogą być puste'
	));