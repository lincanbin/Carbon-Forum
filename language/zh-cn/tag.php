<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Tag' => '话题',
	'Last_Reply_From' => '最后回复来自',
	'Followers' => '人收藏',
	'Topics' => '个主题',
	'Created_In' => '话题创建于',
	'Last_Updated_In' => '最后更新于',
	'Follow' => '关注本话题',
	'Unfollow' => '取消关注本话题',

	'Upload_A_New_Icon' => '更新图标',
	'Enable_Tag' => '启用标签',
	'Disable_Tag' => '禁用标签',
	'Edit_Description' => '编辑描述',
	'Submit' => '提交',
	'Cancel' => '取消',

	'Website_Statistics' => '站内统计',
	'Topics_Number' => '主题数量',
	'Posts_Number' => '回帖数量',
	'Tags_Number' => '话题数量',
	'Users_Number' => '用户数量'
	
	));