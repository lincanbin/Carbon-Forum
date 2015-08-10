<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Email' => 'Email',
	'Submit' => 'Submit',
	'Forms_Can_Not_Be_Empty' => 'Forms Can Not Be Empty',
	'User_Does_Not_Exist' => 'User does not exist',
	'Email_Error' => 'Email input errors, the user\'s e-mail address is: {{UserMail}}',
	'Verification_Code_Error' => 'Verification code error',
	'Email_Has_Been_Sent' => 'Please sign in your e-mail to reset your password. ',
	'Email_Could_Not_Be_Sent' => 'Email could not be sent. ',
	'Mail_Template_Subject' => '{{UserName}}, reset your password - {{SiteName}}',
	'Mail_Template_Body' => '<p>{{UserName}}, 
		please click the hyperlink to reset your password in two hours: </p>
		<p><a href="{{ResetPasswordURL}}">{{ResetPasswordURL}}</a></p>',
	));