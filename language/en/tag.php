<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Tag' => 'Tag',
	'Last_Reply_From' => 'Last reply from',
	'Followers' => ' Followers',
	'Topics' => ' Topics',
	'Created_In' => 'Created in ',
	'Last_Updated_In' => 'Last update ',
	'Follow' => 'Follow',
	'Unfollow' => 'Unfollow',

	'Upload_A_New_Icon' => 'Upload a new icon',
	'Enable_Tag' => 'Enable tag',
	'Disable_Tag' => 'Disable tag',
	'Edit_Description' => 'Edit description',
	'Submit' => 'Submit',
	'Cancel' => 'Cancel',

	'Website_Statistics' => 'Website Statistics',
	'Topics_Number' => 'Topics Number',
	'Posts_Number' => 'Posts Number',
	'Tags_Number' => 'Tags Number',
	'Users_Number' => 'Users Number'
	
	));
