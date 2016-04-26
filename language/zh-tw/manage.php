<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Deleted' => '成功將討論串移動至回收站',
	'Recovered' => '成功復原討論串',
	'Failure_Recovery' => '復原失敗，請確認討論串是否在回收站內',

	'Permanently_Deleted' => '成功永久刪除',
	'Failure_Permanent_Deletion' => '請確認討論串是否在回收站內',

	'Sunk' => '下沉成功',
	'Risen' => '上浮成功',

	'Lock' => '鎖定',
	'Unlock' => '解鎖',

	'Block_User' => '停權',
	'Unblock_User' => '復權',

	'Follow' => '跟隨',
	'Unfollow' => '取消跟隨',

	'Unsubscribe' => '取消收藏',
	'Collect' => '收藏',

	'Prohibited_Content' => '你發表的主題含有被禁止發表的內容',
	'Do_Not_Modify' => '未修改',
	'Edited' => '編輯成功',
	'Failure_Edit' => '編輯失敗',

	'Icon_Upload_Success' => '標籤圖示上傳成功',
	'Icon_Upload_Failure' => '標籤圖示上傳失敗',
	'Icon_Is_Oversize' => '標籤圖示超過 1M，上傳失敗',

	'Enable_Tag' => '啟用標籤',
	'Disable_Tag' => '停用標籤',

	'Reset_Avatar_Successfully' => '重設肖像成功，請重新整理頁面。'
	));
