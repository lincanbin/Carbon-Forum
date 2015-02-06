<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Posting_Too_Often' => 'Delegowanie zbyt często',
	'Topic_Has_Been_Locked' => 'Temat jest zablokowany',
	'Too_Long' => 'Długość posta nie może przekroczyć {{MaxPostChars}} bajtów',
	'Content_Empty' => 'Post musi mieć zawartość'
	));