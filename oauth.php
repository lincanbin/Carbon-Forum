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
if (!$AppInfo || !$Code || $State || !isset($_SESSION[$Prefix . 'OauthState']) || $State != $_SESSION[$Prefix . 'OauthState']) {
	//如果不是认证服务器跳转回的回调页，则跳转回授权服务页
	//生成State值防止CSRF
	session_start();
	$SendState = md5(uniqid(rand(), TRUE));
	$_SESSION[$Prefix . 'OauthState'] = $SendState;
	//默认跳转回首页，后面覆写此变量
	$AuthorizeURL = $Config['WebsitePath'].'/';

	if($AppInfo){
		switch ($AppInfo['AppName']) {
			case 'qq':
				//http://wiki.connect.qq.com/%E4%BD%BF%E7%94%A8authorization_code%E8%8E%B7%E5%8F%96access_token
				$RequestParameter = array(
					'response_type' => 'code',
					'client_id' => $AppInfo['AppKey'],
					'redirect_uri' => 'http://'.$_SERVER['HTTP_HOST'] . $Config['WebsitePath'].'/oauth-'.$AppID,
					'state' => $SendState,
					'scope' => 'get_user_info,get_info'

				);
				$AuthorizeURL = 'https://graph.qq.com/oauth2.0/authorize?'.http_build_query($RequestParameter);
				break;
			case 'weibo':
				//http://open.weibo.com/wiki/%E6%8E%88%E6%9D%83%E6%9C%BA%E5%88%B6%E8%AF%B4%E6%98%8E
				break;
			default:
				break;
		}
	}
	header("HTTP/1.1 301 Moved Permanently");
	header("Status: 301 Moved Permanently");
	header("Location: " . $AuthorizeURL);
	exit();
}

$DB->CloseConnection();
$PageTitle   = 'Set your name';
$ContentFile = $TemplatePath . 'oauth.php';
include($TemplatePath . 'layout.php');