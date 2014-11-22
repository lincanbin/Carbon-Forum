<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Avatar_Settings' => '头像设置',
	'Profile_Settings' => '资料设置',
	'Security_Settings' => '安全设置',
	'You_Can_Replace_Your_Avatar_Here' => '你可以在这里修改你的头像<br /><br />头像格式支持jpg/jpeg/png/gif',
	'Max_Avatar_Size_Limit' => '头像最大不得超过1MiB',
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
	'Current_Password' => '当前密码',
	'New_Password' => '新密码',
	'Confirm_New_Password' => '再次输入新密码',
	'Change_Password' => '更改密码',


	'This_User_Name_Already_Exists' => '这名字太火了，已经被抢注了，换一个吧！',
	'VerificationCode_Error' => '验证码错误',
	'Email_Error' => '电子邮箱不符合规则，电子邮箱正确格式为abc@domain.com',
	'UserName_Error' => '用户名不符合规则。用户名为4-20个字符，不可全为数字，可以包括字母、数字、中文、半角符号“_”、“-”与“.”',
	'Passwords_Inconsistent' => '密码、重复密码 输入不一致',
	'Forms_Can_Not_Be_Empty' => '用户名、密码、重复密码、验证码 必填'
	));