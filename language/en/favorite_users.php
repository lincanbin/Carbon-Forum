<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'My_Following_Users' => 'My following users',
	'Created_Topic' => ' created topic',
	'Replied_Topic' => ' replied topic'
	
	));