<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Email' => '密保信箱',
	'Submit' => '送出',
	'Forms_Can_Not_Be_Empty' => '使用者名稱/信箱地址/驗證碼必填',
	'User_Does_Not_Exist' => '使用者不存在',
	'Email_Error' => '電子郵件輸入錯誤，該使用者信箱地址為：{{UserMail}}',
	'Verification_Code_Error' => '驗證碼錯誤',
	'Email_Has_Been_Sent' => '請求成功，請登入您的密保信箱查閱新郵件以重設密碼。',
	'Email_Could_Not_Be_Sent' => '密保郵件傳送失敗',
	'Mail_Template_Subject' => '{{UserName}}, 密碼重設申請 - {{SiteName}}',
	'Mail_Template_Body' => '<p>{{UserName}}， 
		請在兩小時內點選以下連結重設密碼: </p>
		<p><a href="{{ResetPasswordURL}}">{{ResetPasswordURL}}</a></p>',
	));
