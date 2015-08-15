<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Set_Your_Username' => 'Set your username',
	'This_User_Name_Already_Exists' => 'This UserName Already Exists',
	'UserName_Error' => 'Username does not conform to the rules. User name must be 4 to 20 characters, not all numbers, can include letters, numbers, Chinese, half-size symbol "_", "-"',
	'Binding_Success' => 'Binding Success',
	'Binding_Failure' => 'Binding Failure'
	));