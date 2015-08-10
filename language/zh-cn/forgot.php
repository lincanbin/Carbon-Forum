<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Email' => '电子邮箱',
	'Submit' => '提交',
	'Forms_Can_Not_Be_Empty' => '用户名/邮箱地址/验证码必填',
	'User_Does_Not_Exist' => '用户不存在',
	'Email_Error' => '电子邮件输入错误，该用户邮箱地址为：{{UserMail}}',
	'Verification_Code_Error' => '验证码错误',
	'Email_Has_Been_Sent' => 'Please sign in your e-mail to reset your password. ',
	'Email_Could_Not_Be_Sent' => 'Email could not be sent.',
	'Mail_Template_Subject' => '{{UserName}}, reset your password - {{SiteName}}',
	'Mail_Template_Body' => '<p>{{UserName}}, 
		please click the hyperlink to reset your password in two hours: </p>
		<p><a href="{{ResetPasswordURL}}">{{ResetPasswordURL}}</a></p>',
	));