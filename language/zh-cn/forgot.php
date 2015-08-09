<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Forgot_Password' => '忘记密码',
	'Email' => '电子邮箱',
	'Submit' => '提交',
	'Forms_Can_Not_Be_Empty' => '用户名/邮箱地址/验证码必填',
	'User_Does_Not_Exist' => '用户不存在',
	'Email_Error' => '电子邮件输入错误',
	'Verification_Code_Error' => '验证码错误',
	'Email_Has_Been_Sent' => 'Email has been sent. ',
	'Email_Could_Not_Be_Sent' => 'Email could not be sent.',
	'Mail_Template_Subject' => '{{UserName}}, reset your password - {{SiteName}}',
	'Mail_Template_Body' => '<p>{{UserName}}, 
		please click here to reset your password: </p>
		<p><a href="{{ResetPasswordURL}}">{{ResetPasswordURL}}</a></p>',
	));