<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'People_Collection' => '人收藏',
	'People_Have_Seen' => '人看过',
	'Edit' => '编辑',
	'Quote' => '引用',
	'Delete' => '删除',
	'Recover' => '恢复',
	'Permanently_Delete' => '永久删除',
	'Unlock' => '解锁',
	'Lock' => '锁定',
	'Sink' => '下沉',
	'Rise' => '上浮',
	'Unsubscribe' => '取消收藏',
	'Collect' => '收藏',
	'Replies' => '个回复',
	'Last_Updated_In' => '最后更新于',
	'Reply' => ' 回 复 ',
	'Requirements_For_Login' => '登录后方可回帖',
	'Topic_Has_Been_Locked' => '此帖已被锁定，禁止回复',
	'Add_Tags' => '添加话题(按Enter添加)',
	'Edit_Tags' => '编辑话题',
	'Complete_Edit_Tags' => '完成'
	));