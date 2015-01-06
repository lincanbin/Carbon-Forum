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
	'Last_Updated_In' => 'Last updated in ',
	'Follow' => 'Follow',
	'Unfollow' => 'Unfollow',
	'Website_Statistics' => 'Website Statistics',
	'Topics_Number' => 'Topics Number',
	'Posts_Number' => 'Posts Number',
	'Tags_Number' => 'Tags Number',
	'Users_Number' => 'Users Number'
	
	));