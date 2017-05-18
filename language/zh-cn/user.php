<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Unfollow' => '取消关注',
	'Follow' => '关注他',
	'Send_Message' => '发送私信',
	'Block_User' => '封禁用户',
	'Unblock_User' => '解禁用户',
	'Reset_Avatar' => '重置头像',
	'Registered_In' => '注册于',
	'Topics_Number' => '主题数',
	'Posts_Number' => '回贴数',
	'Homepage' => '网站',
	'Introduction' => '介绍',
	'Last_Activity_In' => '最后活动于',
	'Created_Topic' => '创建了主题',
	'Replied_To_Topic' => '回复了主题'
	));