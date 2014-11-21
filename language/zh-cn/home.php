<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Last_Reply_From' => '最后回复来自',
	'Website_Statistics' => '站内统计',
	'Topics_Number' => '主题数量',
	'Posts_Number' => '回帖数量',
	'Tags_Number' => '话题数量',
	'Users_Number' => '用户数量'
	
	));