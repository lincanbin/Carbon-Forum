<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'My_Following_Users' => '我关注的用户',
	'Created_Topic' => '创建了主题',
	'Replied_Topic' => '回复了主题'
	
	));