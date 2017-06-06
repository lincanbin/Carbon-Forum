<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array (
  'Email' => '信箱地址',
  'Confirm_Password' => '再次輸入密碼',
  'This_User_Name_Already_Exists' => '該使用者名稱已被佔用，請換用其它名稱註冊。',
  'VerificationCode_Error' => '認證碼錯誤',
  'Email_Error' => '信箱地址格式分析失敗，請確認其格式為 abc@domain.com',
  'UserName_Error' => '使用者名稱不符合規則。使用者名稱為 4-20 個字元，不可全為數字，不可含有空白字元，可以包括字母、數字、中文、半形符號“_”、“-”',
  'Forms_Can_Not_Be_Empty' => '使用者名稱、密碼、認證碼，均為必填欄位',
  'Prohibit_Registration' => '管理員已經禁止註冊',
));