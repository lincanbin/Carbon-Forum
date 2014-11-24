<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Topic_Has_Been_Locked' => '此帖已被锁定，禁止回复',
	'Too_Long' => '帖子内容长度不能超过{{MaxPostChars}}个字节',
	'Content_Empty' => '内容不能为空'
	));