<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Posting_Too_Often' => '您發言過於頻繁，請稍後再嘗試',
	'Topic_Has_Been_Locked' => '該討論串已被鎖定，您無權回覆',
	'Too_Long' => '回覆內容長度不能超過 {{MaxPostChars}} 個位元組',
	'Content_Empty' => '內容不能為空白',
	'Prohibited_Content' => '你發表的主題含有被禁止發表的內容'
	));
