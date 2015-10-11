<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Set_Your_Username' => '設定您的名稱',
	'This_User_Name_Already_Exists' => '此使用者名稱已被佔用，請換用其它名稱註冊。',
	'UserName_Error' => '使用者名稱不符合規則。使用者名稱為 4-20 個字元，不可全為數字，不可含有空白字元，可以包括字母、數字、中文、半形符號“_”、“-”',
	'Binding_Success' => '連結成功',
	'Binding_Failure' => '連結失敗，請重新嘗試'
	));
