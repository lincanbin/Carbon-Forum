<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Deleted' => 'Deleted',
	'Recovered' => 'Recovered',
	'Failure_Recovery' => 'Failure Recovery',

	'Permanently_Deleted' => 'Permanently Deleted',
	'Failure_Permanent_Deletion' => 'Failure Permanent Deletion',

	'Sunk' => 'Sunk',
	'Risen' => 'Risen',

	'Lock' => 'Lock',
	'Unlock' => 'Unlock',

	'Block_User' => 'Block User',
	'Unblock_User' => 'Unblock User',

	'Follow' => 'Follow',
	'Unfollow' => 'Unfollow',

	'Unsubscribe' => 'Unsubscribe',
	'Collect' => 'Collect',

	'Do_Not_Modify' => 'Do Not Modify',
	'Edited' => 'Edited',
	'Failure_Edit' => 'Failure Edit'
	));