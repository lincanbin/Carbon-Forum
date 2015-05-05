<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Deleted' => '成功將討論串移動至回收站',
	'Recovered' => '成功還原討論串',
	'Failure_Recovery' => '恢復失敗，請確認討論串是否在回收站內',

	'Permanently_Deleted' => '成功永久刪除',
	'Failure_Permanent_Deletion' => '請確認討論串是否在回收站內',

	'Sunk' => '下沉成功',
	'Risen' => '上浮成功',

	'Lock' => '鎖定',
	'Unlock' => '解鎖',

	'Block_User' => '停權',
	'Unblock_User' => '復權',

	'Follow' => '關注',
	'Unfollow' => '取消關注',

	'Unsubscribe' => '取消收藏',
	'Collect' => '收藏',

	'Do_Not_Modify' => '未修改',
	'Edited' => '編輯成功',
	'Failure_Edit' => '編輯失敗',

	'Reset_Avatar_Successfully' => '重置肖像成功，請刷新頁面。'
	));