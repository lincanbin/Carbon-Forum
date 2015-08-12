<?php
require(dirname(__FILE__) . '/common.php');
require(dirname(__FILE__) . '/language/' . ForumLanguage . '/oauth.php');
$AppID  = intval(Request('Get', 'app_id'));
$Code   = Request('Get', 'code');
$State   = Request('Get', 'state');
$AppInfo = $DB->row('SELECT * FROM ' . $Prefix . 'app WHERE ID=:ID',
	array(
		'ID' => $AppID
	)
);
if(file_exists(dirname(__FILE__) . '/includes/Oauth.'.$AppInfo['AppName'].'.class.php')){
	require(dirname(__FILE__) . '/includes/Oauth.'.$AppInfo['AppName'].'.class.php');
}else{
	AlertMsg('404 Not Found', '404 Not Found', 404);
}
//如果不是认证服务器跳转回的回调页，则跳转回授权服务页
if (!$AppInfo || !$Code || $State || !isset($_SESSION[$Prefix . 'OauthState']) || $State != $_SESSION[$Prefix . 'OauthState']) {
	//生成State值防止CSRF
	session_start();
	$SendState = md5(uniqid(rand(), TRUE));
	$_SESSION[$Prefix . 'OauthState'] = $SendState;
	//默认跳转回首页，后面覆写此变量
	$AuthorizeURL = Oauth::AuthorizeURL('http://'.$_SERVER['HTTP_HOST'] . $Config['WebsitePath'], $AppInfo['AppKey'], $AppID, $SendState);
	header("HTTP/1.1 301 Moved Permanently");
	header("Status: 301 Moved Permanently");
	header("Location: " . $AuthorizeURL);
	exit();
}
//下面是回调页面的处理


$DB->CloseConnection();
$PageTitle   = 'Set your name';
$ContentFile = $TemplatePath . 'oauth.php';
include($TemplatePath . 'layout.php');