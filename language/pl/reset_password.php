<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Reset_Password' => 'Zresetuj hasło',
	'Page_Has_Expired' => 'Strona wygasła',
	'New_Password' => 'Nowe hasło',
	'Confirm_New_Password' => 'Potwierdź nowe hasło',
	'VerificationCode_Error' => 'Verification code error',
	'Passwords_Inconsistent' => 'Te dwa hasła są niespójne',
	'Forms_Can_Not_Be_Empty' => 'Formy nie może być pusty',
	'Reset_Password_Success' => 'Resetowanie hasła sukces',
	'Reset_Password_Failure' => 'Resetowania hasła nie powiodło się'
	));