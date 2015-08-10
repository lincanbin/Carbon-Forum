<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Email' => '密保邮箱',
	'Submit' => '提交',
	'Forms_Can_Not_Be_Empty' => '用户名/邮箱地址/验证码必填',
	'User_Does_Not_Exist' => '用户不存在',
	'Email_Error' => '电子邮件输入错误，该用户邮箱地址为：{{UserMail}}',
	'Verification_Code_Error' => '验证码错误',
	'Email_Has_Been_Sent' => '请求成功，请登录您的密保邮箱查收新邮件以重置密码。',
	'Email_Could_Not_Be_Sent' => '密保邮件发送失败',
	'Mail_Template_Subject' => '{{UserName}}, 密码重置申请 - {{SiteName}}',
	'Mail_Template_Body' => '<p>{{UserName}}， 
		请在两小时内点击以下链接重置密码: </p>
		<p><a href="{{ResetPasswordURL}}">{{ResetPasswordURL}}</a></p>',
	));