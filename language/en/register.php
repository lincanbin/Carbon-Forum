<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array (
  'Email' => 'Email',
  'Confirm_Password' => 'Confirm Password',
  'This_User_Name_Already_Exists' => 'This UserName Already Exists',
  'VerificationCode_Error' => 'Verification Code Error',
  'Email_Error' => 'E-mail does not comply with the rules, e-mail address in the correct format for abc@domain.com',
  'UserName_Error' => 'Username does not conform to the rules. User name must be 4 to 20 characters, not all numbers, can include letters, numbers, Chinese, half-size symbol "_", "-"',
  'Forms_Can_Not_Be_Empty' => 'Forms Can Not Be Empty',
  'Prohibit_Registration' => 'The administrator has disabled registration',
));