<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Email' => '密保郵箱',
	'Submit' => '提交',
	'Forms_Can_Not_Be_Empty' => '用戶名/郵箱地址/驗證碼必填',
	'User_Does_Not_Exist' => '用戶不存在',
	'Email_Error' => '電子郵件輸入錯誤，該使用者郵箱位址為：{{UserMail}}',
	'Verification_Code_Error' => '驗證碼錯誤',
	'Email_Has_Been_Sent' => '請求成功，請登錄您的密保郵箱查收新郵件以重置密碼。',
	'Email_Could_Not_Be_Sent' => '密保郵件發送失敗',
	'Mail_Template_Subject' => '{{UserName}}, 密碼重置申請 - {{SiteName}}',
	'Mail_Template_Body' => '<p>{{UserName}}， 
		請在兩小時內點擊以下連結重置密碼: </p>
		<p><a href="{{ResetPasswordURL}}">{{ResetPasswordURL}}</a></p>',
	));