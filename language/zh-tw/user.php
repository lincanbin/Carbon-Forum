<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array (
  'Unfollow' => '取消跟隨',
  'Follow' => '跟隨他/她',
  'Block_User' => '停權',
  'Unblock_User' => '復權',
  'Reset_Avatar' => '重設肖像',
  'Registered_In' => '帳號申請於',
  'Topics_Number' => '討論串數',
  'Posts_Number' => '回覆數',
  'Homepage' => '網站',
  'Introduction' => '介紹',
  'Last_Activity_In' => '最後活動於',
  'Created_Topic' => '建立了討論串',
  'Replied_To_Topic' => '回覆了討論串',
  'Send_Message' => '發送私信',
));