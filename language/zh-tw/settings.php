<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Avatar_Settings' => '肖像設定',
	'Profile_Settings' => '個資設定',
	'Account_Settings' => '帳號設定',
	'Security_Settings' => '安全設定',
	'Reset_Avatar' => '重設肖像',
	'You_Can_Replace_Your_Avatar_Here' => '你可以在這裡更換你的肖像',
	'Avatar_Image_Format_Support' => '可用的肖像格式為「jpg/jpeg/png/gif」',
	'Max_Avatar_Size_Limit' => '肖像大小不得超過 1MiB',
	'Upload_Avatar' => '上傳肖像',

	'Do_Not_Modify' => '不修改',
	'User_Sex' => '使用者性別',
	'Sex_Unknown' => '無可奉告',
	'Sex_Male' => '男',
	'Sex_Female' => '女',
	'Email' => '信箱地址',
	'Ensure_That_Email_Is_Correct' => '不公開，僅供取回密碼，務必正確填寫暨牢記之。',
	'Homepage' => '個人主頁',
	'Introduction' => '個人簡介',
	'Save_Settings' => '儲存設定',
	'Connect_XXX_Account' => '連結 {{AppName}} 帳號',
	'Current_Password' => '當前密碼',
	'New_Password' => '新密碼',
	'Confirm_New_Password' => '再次輸入新密碼',
	'Change_Password' => '更換密碼',

	'Avatar_Upload_Success' => '肖像上傳成功',
	'Avatar_Upload_Failure' => '肖像上傳失敗',
	'Avatar_Is_Oversize' => '肖像超過 1M，上傳失敗',

	'Profile_Modified_Successfully' => '個資修改成功',
	'Profile_Do_Not_Modify' => '個資無改動',

	'Change_Password_Success' => '密碼已成功更換，請記住新密碼',
	'Change_Password_Failure' => '密碼更換失敗',
	'Password_Do_Not_Modify' => '輸入的新密碼不得與原密碼相同',
	'Current_Password_Is_Uncorrect' => '輸入的當前密碼不正確',
	'Passwords_Inconsistent' => '新密碼及其密碼確認不一致',
	'Forms_Can_Not_Be_Empty' => '請填寫完整：當前密碼、新密碼、新密碼確認'
	));
