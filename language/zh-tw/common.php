<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Language' => 'zh-cmn-Hant',
	'RolesDict' => array('遊客','申請會員','VIP會員','版主','超級版主','管理員'),
	'Error_Message' => '錯誤訊息',
	'Error_Unknown_Referer' => '來源錯誤(unknown referer)',
	'Error_Insufficient_Permissions' => '此頁面僅 {{RoleDict}} 可見，您的權限不足。',
	'Error_Account_navailable' => '您的帳號正在審核或者封禁中，請聯絡管理員確認之。',
	'Time_Days_Ago' => '天前',
	'Time_Hours_Ago' => '小時前',
	'Time_Minutes_Ago' => '分鐘前',
	'Time_Seconds_Ago' => '秒前',
	'Time_Just_Now' => '剛剛',
	'Page_Previous' => '上一頁',
	'Page_Next' => '下一頁',
	'New_Message' => '({{NewMessage}} 條訊息)',
	'UserName' => '使用者名稱',
	'Password' => '密碼',
	'Verification_Code' =>'認證碼',
	'Log_In' => ' 登 入 ',
	'Sign_Up' => '現在申請',
	'User_Panel' => '個人資料頁',
	'Log_Out' => '登出',
	'Information_Bar' =>'訊息列',
	'Home' => '首頁',
	'Search' => '搜尋',
	'Create_New_Topic' => '新開討論串',
	'Tags_Followed' => '關注的標籤',
	'Favorite_Topics' => '收藏的討論串',
	'Users_Followed' => '關注的使用者',
	'Notifications' => '知會函',
	'Settings' => '個人設定',
	'System_Settings' => '系統設定',
	'Desktop_Version' => '桌面模式',
	'Mobile_Version' => '行動模式'
));
