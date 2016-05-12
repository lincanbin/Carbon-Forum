<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Avatar_Settings' => 'Avatar',
	'Profile_Settings' => 'Profile',
	'Account_Settings' => 'Account',
	'Security_Settings' => 'Security',
	'Reset_Avatar' => 'Reset Avatar',
	'You_Can_Replace_Your_Avatar_Here' => 'You can replace your avatar hsere',
	'Avatar_Image_Format_Support' => 'Avatar format supports jpg/jpeg/png/gif',
	'Max_Avatar_Size_Limit' => 'Avatar can not exceed 1MiB',
	'Upload_Avatar' => 'Upload avatar',

	'Do_Not_Modify' => 'Do not modify',
	'User_Sex' => 'Sex',
	'Sex_Unknown' => 'Unknown',
	'Sex_Male' => 'Male',
	'Sex_Female' => 'Female',
	'Email' => 'Email',
	'Ensure_That_Email_Is_Correct' => 'Ensure that email is correct',
	'Homepage' => 'Homepage',
	'Introduction' => 'Introduction',
	'Save_Settings' => 'Save Settings',
	'Connect_XXX_Account' => 'Connect {{AppName}} Account',
	'Current_Password' => 'Current Password',
	'New_Password' => 'New Password',
	'Confirm_New_Password' => 'Confirm New Password',
	'Change_Password' => 'Change Password',

	'Avatar_Upload_Success' => 'Success',
	'Avatar_Upload_Failure' => 'Failure',
	'Avatar_Is_Oversize' => 'Avatar is too large',

	'Profile_Modified_Successfully' => 'Success',
	'Profile_Do_Not_Modify' => 'No change data',

	'Change_Password_Success' => 'Password has been changed successfully, please remember the new password',
	'Change_Password_Failure' => 'Password change fails',
	'Password_Do_Not_Modify' => 'The new password can not be the same as the original password. ',
	'Current_Password_Is_Uncorrect' => 'Current password is uncorrect',
	'Passwords_Inconsistent' => 'The two new passwords are inconsistent',
	'Forms_Can_Not_Be_Empty' => 'Forms Can Not Be Empty'
	));