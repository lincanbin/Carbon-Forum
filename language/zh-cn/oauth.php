<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Set_Your_Username' => '设置你的用户名',
	'This_User_Name_Already_Exists' => '这名字太火了，已经被抢注了，换一个吧！',
	'UserName_Error' => '用户名不符合规则。用户名为4-20个字符，不可全为数字，可以包括字母、数字、中文、半角符号“_”、“-”',
	'Binding_Success' => '绑定成功',
	'Binding_Failure' => '绑定失败，请重新尝试'
	));