<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Login_Expiration_Time' => '登录有效期',
	'Days' => '天',
	'Password_Error' => '密码错误',
	'User_Does_Not_Exist' => '用户不存在',
	'Verification_Code_Error' => '验证码错误',
	'Forms_Can_Not_Be_Empty' => '用户名/密码/验证码必填'
	));