<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Followers' => '人收藏',
	'Topics' => '個主題',
	'Follow' => '跟隨此話題',
	'Unfollow' => '取消跟隨此話題'
	));
