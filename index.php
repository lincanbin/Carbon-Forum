<?php
require(__DIR__ . '/common.php');

$HTTPMethod = $_SERVER['REQUEST_METHOD'];
if (!in_array($HTTPMethod, array('GET', 'POST', 'HEAD', 'PUT', 'DELETE', 'OPTIONS'))) {
	exit('Unsupport HTTP method');
}
if ($Config['WebsitePath']) {
	$WebsitePathPosition = strpos($RequestURI, $Config['WebsitePath']);
	if ($WebsitePathPosition !== 0) {
		exit('WebsitePath Error!');
	} else {
		$ShortRequestURI = substr($RequestURI, strlen($Config['WebsitePath']));
	}
} else {
	$ShortRequestURI = $RequestURI;
}
$NotFound = true;
$HTTPParameters = array();
if (in_array($HTTPMethod, array('PUT', 'DELETE', 'OPTIONS'))) {
	parse_str(file_get_contents('php://input'), $HTTPParameters);
}
$Routes = array();

//Support HTTP Method: GET / POST / PUT / DELETE / OPTIONS
//这里是Routes Start

$Routes['GET']['/']                                                                        = 'home';
$Routes['POST']['/']                                                                       = 'home'; //Delete later
$Routes['GET']['/dashboard']                                                               = 'dashboard';
$Routes['POST']['/dashboard']                                                              = 'dashboard';
$Routes['GET']['/favorites(/page/(?<page>[0-9]+))?']                                       = 'favorites';
$Routes['GET']['/forgot']                                                                  = 'forgot';
$Routes['POST']['/forgot']                                                                 = 'forgot';
$Routes['GET']['/goto/(?<topic_id>[0-9]+)-(?<post_id>[0-9]+)']                             = 'goto';
$Routes['POST']['/json/(?<action>[0-9a-z_\-]+)']                                           = 'json';
$Routes['GET']['/json/(?<action>[0-9a-z_\-]+)']                                            = 'json';
$Routes['GET']['/login']                                                                   = 'login';
$Routes['POST']['/login']                                                                  = 'login';
$Routes['POST']['/manage']                                                                 = 'manage';
$Routes['GET']['/new']                                                                     = 'new';
$Routes['POST']['/new']                                                                    = 'new';
$Routes['GET']['/notifications']                                                           = 'notifications';
$Routes['GET']['/oauth-(?<app_id>[0-9]+)']                                                 = 'oauth';
$Routes['POST']['/oauth-(?<app_id>[0-9]+)']                                                = 'oauth';
$Routes['GET']['/page/(?<page>[0-9]+)']                                                    = 'home';
$Routes['POST']['/page/(?<page>[0-9]+)']                                                   = 'home'; //Delete later
$Routes['GET']['/register']                                                                = 'register';
$Routes['POST']['/register']                                                               = 'register';
$Routes['GET']['/reply']                                                                   = 'reply';
$Routes['POST']['/reply']                                                                  = 'reply';
$Routes['GET']['/reset_password/(?<access_token>.*?)']                                     = 'reset_password';
$Routes['POST']['/reset_password/(?<access_token>.*?)']                                    = 'reset_password';
$Routes['GET']['/robots.txt']                                                              = 'robots';
$Routes['GET']['/search.xml']                                                              = 'open_search';
$Routes['GET']['/search/(?<keyword>.*?)(/page/(?<page>[0-9]*))?']                          = 'search';
$Routes['GET']['/settings']                                                                = 'settings';
$Routes['POST']['/settings']                                                               = 'settings';
$Routes['GET']['/sitemap-(?<action>topics|pages|tags|users|index)(-(?<page>[0-9]+))?.xml'] = 'sitemap';
$Routes['GET']['/statistics']                                                              = 'statistics';
$Routes['GET']['/t/(?<id>[0-9]+)(-(?<page>[0-9]*))?']                                      = 'topic';
$Routes['POST']['/t/(?<id>[0-9]+)(-(?<page>[0-9]*))?']                                     = 'topic'; //Delete later
$Routes['GET']['/tag/(?<name>.*?)(/page/(?<page>[0-9]*))?']                                = 'tag';
$Routes['GET']['/tags/following(/page/(?<page>[0-9]*))?']                                  = 'favorite_tags';
$Routes['GET']['/tags(/page/(?<page>[0-9]*))?']                                            = 'tags';
$Routes['GET']['/u/(?<username>.*?)']                                                      = 'user';
$Routes['GET']['/users/following(/page/(?<page>[0-9]*))?']                                 = 'favorite_users';
$Routes['GET']['/upload_controller']                                                      = 'upload_controller';
$Routes['POST']['/upload_controller']                                                      = 'upload_controller';
$Routes['GET']['/view-(?<view>desktop|mobile)']                                            = 'view';

//这里是Routes End


foreach ($Routes as $Method => $SubRoutes) {
	if ($Method === $HTTPMethod) {
		$ParametersVariableName = '_' . $Method;
		foreach ($SubRoutes as $URL => $Controller) {
			if (preg_match("#^" . $URL . "$#i", $ShortRequestURI, $Parameters)) {
				$NotFound = false;
				$Parameters = array_merge($Parameters, $HTTPParameters);
				//var_dump($Parameters);
				foreach ($Parameters as $Key => $Value) {
					if (!is_int($Key)) {
						$$ParametersVariableName[$Key] = urldecode($Value);
						$_REQUEST[$Key] = urldecode($Value);
					}
				}
				//$MicroTime = explode(' ', microtime());
				//echo number_format(($MicroTime[1] + $MicroTime[0] - $StartTime), 6) * 1000;
				$UrlPath = $Controller;
				break 2;
			}
		}
		break;
	}
}

if ($NotFound === true) {
	require(__DIR__ . '/404.php');
	exit();
}

if ($Config['MobileDomainName'] && $_SERVER['HTTP_HOST'] != $Config['MobileDomainName'] && $CurView == 'mobile' && !$IsApp && $UrlPath != 'view') {
	//如果是手机，则跳转到移动版
	header("HTTP/1.1 302 Moved Temporarily");
	header("Status: 302 Moved Temporarily");
	header('Location: ' . $CurProtocol . $Config['MobileDomainName'] . $RequestURI);
	exit();
}

require(__DIR__ . '/controller/' . $UrlPath . '.php');