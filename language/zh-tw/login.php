<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Login_Expiration_Time' => '登入到期時間',
	'Days' => '天',
	'Password_Error' => '密碼錯誤',
	'User_Does_Not_Exist' => '使用者不存在',
	'Verification_Code_Error' => '認證碼錯誤',
	'Forms_Can_Not_Be_Empty' => '使用者名稱/密碼/認證碼必填'
	));
