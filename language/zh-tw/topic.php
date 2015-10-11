<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'People_Collection' => '人收藏',
	'People_Have_Seen' => '人看過',
	'Edit' => '編輯',
	'Quote' => '徵引',
	'Delete' => '刪除',
	'Recover' => '恢復',
	'Permanently_Delete' => '永久刪除',
	'Unlock' => '解鎖',
	'Lock' => '鎖定',
	'Sink' => '下沉',
	'Rise' => '上浮',
	'Unsubscribe' => '取消收藏',
	'Collect' => '收藏',
	'Replies' => '條回覆',
	'Last_Updated_In' => '最後更新於',
	'Reply' => ' 回 覆 ',
	'Requirements_For_Login' => '需登入，才可以回覆',
	'Topic_Has_Been_Locked' => '該討論串已被鎖定，您無權回覆',
	'Add_Tags' => '加入新話題(按Enter添加)',
	'Edit_Tags' => '編輯話題',
	'Complete_Edit_Tags' => '完成'
	));
