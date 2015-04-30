<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Unfollow' => 'Unfollow',
	'Follow' => 'Follow',
	'Block_User' => 'Block User',
	'Unblock_User' => 'Unblock User',
	'Reset_Avatar' => 'Reset Avatar',
	'Registered_In' => 'Registered in',
	'Topics_Number' => 'Topics Number',
	'Posts_Number' => 'Posts Number',
	'Homepage' => 'Homepage',
	'Introduction' => 'Introduction',
	'Last_Activity_In' => 'Last activity in',
	'Created_Topic' => 'Created topic',
	'Replied_To_Topic' => 'Replied to topic'
	));