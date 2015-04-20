<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Title' => 'Title ',
	'Add_Tags' => 'Add a tag (press Enter)',
	'Submit' => ' Submit ',
	'Posting_Too_Often' => 'Posting too often. Please try again later. ',
	'Tags_Empty' => 'Tags can not be empty',
	'Too_Long' => 'Title length can not exceed {{MaxTitleChars}} bytes, the content length can not exceed {{MaxPostChars}} bytes',
	'Title_Empty' => 'Title can not be empty',

	'Tags' => 'Tags',
	'Content' => 'Content'
	));