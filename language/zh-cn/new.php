<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Title' => '标题',
	'Add_Tags' => '添加话题(按Enter添加)',
	'Submit' => ' 发 布 ',
	'Prohibited_New_Topic' => '管理员禁止普通用户发布新主题',
	'Posting_Too_Often' => '您发帖过于频繁，请稍后再尝试',
	'Tags_Empty' => '标签不能为空',
	'Prohibited_Content' => '你发表的主题含有被禁止发表的内容',
	'Too_Long' => '标题长度不能超过{{MaxTitleChars}}个字节，内容长度不能超过{{MaxPostChars}}个字节',
	'Title_Empty' => '标题不能为空',

	'Tags' => '标签',
	'Content' => '内容'
	));