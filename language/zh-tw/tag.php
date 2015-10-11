<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Tag' => '標籤',
	'Last_Reply_From' => '最後回覆來自',
	'Followers' => '人收藏',
	'Topics' => '個討論串',
	'Created_In' => '標籤建立於',
	'Last_Updated_In' => '最後更新於',
	'Follow' => '跟隨此標籤',
	'Unfollow' => '取消跟隨此標籤',

	'Upload_A_New_Icon' => '更新圖示',
	'Enable_Tag' => '啟用標籤',
	'Disable_Tag' => '停用標籤',
	'Edit_Description' => '編輯描述',
	'Submit' => '送出',
	'Cancel' => '取消',

	'Website_Statistics' => '站內統計',
	'Topics_Number' => '討論串數量',
	'Posts_Number' => '回覆數量',
	'Tags_Number' => '標籤數量',
	'Users_Number' => '使用者數量'
	
	));
