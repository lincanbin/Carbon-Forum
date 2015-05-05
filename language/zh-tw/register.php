<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Email' => '電郵地址',
	'Confirm_Password' => '再次輸入密碼',
	'This_User_Name_Already_Exists' => '該使用者名稱已被佔用，請換用其它名稱註冊。',
	'VerificationCode_Error' => '認證碼錯誤',
	'Email_Error' => '電郵地址格式分析失敗，請確認其格式為abc@domain.com',
	'UserName_Error' => '使用者名稱不符合規則。使用者名為4-20個字元，不可全為數字，可以包括字母、數字、中文、半形符號“_”、“-”與“.”',
	'Passwords_Inconsistent' => '密碼確認失敗，請確認它和您輸入的密碼完全一致',
	'Forms_Can_Not_Be_Empty' => '使用者名稱、密碼、密碼確認、認證碼，均為必填條目'
	));