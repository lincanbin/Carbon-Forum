<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Reset_Password' => '重置密碼',
	'Page_Has_Expired' => '頁面已過期',
	'New_Password' => '新密碼',
	'Confirm_New_Password' => '再次輸入新密碼',
	'VerificationCode_Error' => '驗證碼錯誤',
	'Passwords_Inconsistent' => '密碼、重複密碼 輸入不一致',
	'Forms_Can_Not_Be_Empty' => '密碼、重複密碼、驗證碼 必填',
	'Reset_Password_Success' => '重置密碼成功',
	'Reset_Password_Failure' => '重置密碼失敗'
	));