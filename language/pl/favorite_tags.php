<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Last_Reply_From' => 'Ostatnia odpowiedź z',
	'My_Following_Tags' => 'Moje obserwowane tagi'
	));