<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Login_Expiration_Time' => 'Login Expiration Time',
	'Days' => 'Days',
	'Password_Error' => 'Password Error',
	'User_Does_Not_Exist' => 'User Does Not Exist',
	'Verification_Code_Error' => 'Verification Code Error',
	'Forms_Can_Not_Be_Empty' => 'Forms Can Not Be Empty'
	));