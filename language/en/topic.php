<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'People_Collection' => ' collector',
	'People_Have_Seen' => ' pageviews',
	'Edit' => 'Edit',
	'Delete' => 'Delete',
	'Recover' => 'Recover',
	'Permanently_Delete' => 'Permanently Delete',
	'Unlock' => 'Unlock',
	'Lock' => 'Lock',
	'Sink' => 'Sink',
	'Rise' => 'Rise',
	'Unsubscribe' => 'Unsubscribe',
	'Collect' => 'Collect',
	'Replies' => ' Replies',
	'Last_Updated_In' => 'Last updated in ',
	'Reply' => ' Reply ',
	'Requirements_For_Login' => 'Requirements for log in',
	'Topic_Has_Been_Locked' => 'Topic has been locked. Prohibit replying. '
	));