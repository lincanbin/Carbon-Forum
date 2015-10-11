<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'My_Following_Users' => '我跟隨的使用者',
	'Created_Topic' => '新開了討論串',
	'Replied_Topic' => '回覆了討論串'
	
	));
