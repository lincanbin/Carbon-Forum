<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'My_Favorites' => 'Ulubione',
	'Unsubscribe' => 'Odsubskrybuj',
	'Collected_In' => 'Zebrane w '
	));