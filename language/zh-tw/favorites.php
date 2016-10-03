<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'My_Favorites' => '我的收藏',
	'Unsubscribe' => '取消收藏',
	'Collected_In' => '收藏於'
	));