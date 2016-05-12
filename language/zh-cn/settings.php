<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Avatar_Settings' => '头像设置',
	'Profile_Settings' => '资料设置',
	'Account_Settings' => '账号设置',
	'Security_Settings' => '安全设置',
	'Reset_Avatar' => '重置头像',
	'You_Can_Replace_Your_Avatar_Here' => '你可以在这里修改你的头像',
	'Avatar_Image_Format_Support' => '支持 jpg / jpeg / png / gif',
	'Max_Avatar_Size_Limit' => '文件大小不得超过1MiB',
	'Upload_Avatar' => '上传头像',

	'Do_Not_Modify' => '不修改',
	'User_Sex' => '用户性别',
	'Sex_Unknown' => '不明确',
	'Sex_Male' => '男',
	'Sex_Female' => '女',
	'Email' => '电子邮箱',
	'Ensure_That_Email_Is_Correct' => '不公开，仅供取回密码，务必正确填写且记住。',
	'Homepage' => '个人主页',
	'Introduction' => '个人简介',
	'Save_Settings' => '保存设置',
	'Connect_XXX_Account' => '绑定{{AppName}}账号',
	'Current_Password' => '当前密码',
	'New_Password' => '新密码',
	'Confirm_New_Password' => '再次输入新密码',
	'Change_Password' => '更改密码',

	'Avatar_Upload_Success' => '头像上传成功',
	'Avatar_Upload_Failure' => '头像上传失败',
	'Avatar_Is_Oversize' => '头像超过1M，上传失败',

	'Profile_Modified_Successfully' => '资料修改成功',
	'Profile_Do_Not_Modify' => '资料无改动',

	'Change_Password_Success' => '密码已成功更改，请记住新密码',
	'Change_Password_Failure' => '密码更改失败',
	'Password_Do_Not_Modify' => '输入的新密码不能与原密码相同',
	'Current_Password_Is_Uncorrect' => '输入的当前密码不正确',
	'Passwords_Inconsistent' => '新密码、重复新密码不一致',
	'Forms_Can_Not_Be_Empty' => '请填写完整：当前密码、新密码、重复新密码'
	));