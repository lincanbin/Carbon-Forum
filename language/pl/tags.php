<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Followers' => 'Następni',
	'Topics' => 'Tematy',
	'Follow' => 'Śledź',
	'Unfollow' => 'Nie obserwuj'
	));