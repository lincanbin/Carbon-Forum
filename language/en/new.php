<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array (
  'Title' => 'Title ',
  'Add_Tags' => 'Add a tag (press Enter)',
  'Submit' => ' Submit ',
  'Posting_Too_Often' => 'Posting too often. Please try again later. ',
  'Tags_Empty' => 'Tags can\'t be empty',
  'Prohibited_Content' => 'Your topic is prohibited to publish',
  'Too_Long' => 'Title length can\'t exceed {{MaxTitleChars}} bytes, the content length can\'t exceed {{MaxPostChars}} bytes',
  'Title_Empty' => 'Title can\'t be empty',
  'Tags' => 'Tags',
  'Content' => 'Content',
  'Prohibited_New_Topic' => 'Administrators prohibit ordinary users from posting new topics',
));