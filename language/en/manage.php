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

	'Follow' => 'Follow',
	'Unfollow' => 'Unfollow',

	'Unsubscribe' => 'Unsubscribe',
	'Collect' => 'Collect'
	));