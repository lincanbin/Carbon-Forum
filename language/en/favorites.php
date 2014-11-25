<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'My_Favorites' => 'My favorites',
	'Unsubscribe' => 'Unsubscribe',
	'Collected_In' => 'Collected in '
	));