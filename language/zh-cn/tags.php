<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Followers' => '人收藏',
	'Topics' => '个主题',
	'Follow' => '关注本话题',
	'Unfollow' => '取消关注本话题'
	));