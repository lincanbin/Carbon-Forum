<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Reset_Password' => '重置密码',
	'Page_Has_Expired' => '页面已过期',
	'New_Password' => '新密码',
	'Confirm_New_Password' => '再次输入新密码',
	'VerificationCode_Error' => '验证码错误',
	'Passwords_Inconsistent' => '密码、重复密码 输入不一致',
	'Forms_Can_Not_Be_Empty' => '密码、重复密码、验证码 必填',
	'Reset_Password_Success' => '重置密码成功',
	'Reset_Password_Failure' => '重置密码失败'
	));