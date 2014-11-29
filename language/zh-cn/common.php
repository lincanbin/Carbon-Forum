<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if (empty($Lang) || !is_array($Lang))
	$Lang = array();

$Lang = array_merge($Lang, array(
	'Language' => 'www',
	'RolesDict' => array('游客','注册会员','VIP会员','版主','超级版主','管理员'),
	'Error_Message' => '错误信息',
	'Error_Unknown_Referer' => '来源错误(unknown referer)',
	'Error_Insufficient_Permissions' => '此页面仅 {{RoleDict}} 可见，您的权限不足。',
	'Error_Account_navailable' => '您的账号正在审核或者封禁中，请联系管理员确认！',
	'Time_Days_Ago' => '天前',
	'Time_Hours_Ago' => '小时前',
	'Time_Minutes_Ago' => '分钟前',
	'Time_Seconds_Ago' => '秒前',
	'Time_Just_Now' => '刚刚',
	'Page_Previous' => '上一页',
	'Page_Next' => '下一页',
	'New_Message' => '({{NewMessage}}条消息)',
	'UserName' => '用户名',
	'Password' => '密码',
	'Verification_Code' =>'验证码',
	'Log_In' => ' 登 陆 ',
	'Sign_Up' => '现在注册',
	'User_Panel' => '用户面板',
	'Log_Out' => '退出',
	'Information_Bar' =>'信息栏',
	'Home' => '首页',
	'Create_New_Topic' => '发新帖',
	'Tags_Followed' => '关注的话题',
	'Favorite_Topics' => '收藏的帖子',
	'Users_Followed' => '关注的用户',
	'Notifications' => '消息提醒',
	'Settings' => '个人设置',
	'System_Settings' => '系统设置'
));