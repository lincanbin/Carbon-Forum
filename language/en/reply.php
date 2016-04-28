<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Posting_Too_Often' => 'Posting too often. Please try again later. ',
	'Topic_Has_Been_Locked' => 'Topic has been locked',
	'Too_Long' => 'Post content length can not exceed {{MaxPostChars}} bytes',
	'Content_Empty' => 'Content can not be empty',
	'Prohibited_Content' => 'Your topic is prohibited to publish'
	));