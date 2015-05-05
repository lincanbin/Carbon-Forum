<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Unfollow' => '取消關注',
	'Follow' => '關注他',
	'Block_User' => '停權',
	'Unblock_User' => '復權',
	'Reset_Avatar' => '重置肖像',
	'Registered_In' => '申請於',
	'Topics_Number' => '討論串數',
	'Posts_Number' => '回覆數',
	'Homepage' => '網站',
	'Introduction' => '介紹',
	'Last_Activity_In' => '最後活動於',
	'Created_Topic' => '創建了討論串',
	'Replied_To_Topic' => '回覆了討論串'
	));