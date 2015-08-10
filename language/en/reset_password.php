<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Reset_Password' => 'Reset password',
	'Page_Has_Expired' => 'Page has expired',
	'New_Password' => 'New password',
	'Confirm_New_Password' => 'Confirm new password',
	'VerificationCode_Error' => 'Verification code error',
	'Passwords_Inconsistent' => 'The two passwords are inconsistent',
	'Forms_Can_Not_Be_Empty' => 'Forms Can Not Be Empty',
	'Reset_Password_Success' => 'Reset password success',
	'Reset_Password_Failure' => 'Reset password failed'
	));