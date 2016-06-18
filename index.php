<?php
require(__DIR__ . '/common.php');
//var_dump($_SERVER);
//$MicroTime = explode(' ', microtime());
//echo number_format(($MicroTime[1] + $MicroTime[0] - $StartTime), 6) * 1000;
//echo '<br />';
/*
RewriteRule ^dashboard$ dashboard.php [L,QSA]
RewriteRule ^favorites(/page/([0-9]*))?$ favorites.php?page=$2 [L,QSA]
RewriteRule ^forgot$ forgot.php [L,QSA]
RewriteRule ^goto/([0-9]+)-([0-9]+)$ goto.php?topic_id=$1&post_id=$2 [L,QSA]
RewriteRule ^json/([0-9a-z_\-]+)$ json.php?action=$1 [L,QSA]
RewriteRule ^login$ login.php [L,QSA]
RewriteRule ^manage$ manage.php [L,QSA]
RewriteRule ^new$ new.php [L,QSA]
RewriteRule ^notifications$ notifications.php [L,QSA]
RewriteRule ^oauth-([0-9]+)$ oauth.php?app_id=$1 [L,QSA]
RewriteRule ^page/([0-9]+)$ index.php?page=$1 [L,QSA]
RewriteRule ^register$ register.php [L,QSA]
RewriteRule ^reply$ reply.php [L,QSA]
RewriteRule ^reset_password/(.*?)$ reset_password.php?access_token=$1 [L,QSA]
RewriteRule ^robots.txt$ robots.php [L,QSA]
RewriteRule ^search.xml$ open_search.php [L,QSA]
RewriteRule ^search/(.*?)(/page/([0-9]*))?$ search.php?keyword=$1&page=$3 [L]
RewriteRule ^settings$ settings.php [L,QSA]
RewriteRule ^sitemap-(topics|pages|tags|users|index)(-([0-9]+))?.xml$ sitemap.php?action=$1&page=$3 [L,QSA]
RewriteRule ^statistics$ statistics.php [L,QSA]
RewriteRule ^t/([0-9]+)(-([0-9]*))?$ topic.php?id=$1&page=$3 [L,QSA]
RewriteRule ^tag/(.*?)(/page/([0-9]*))?$ tag.php?name=$1&page=$3 [L]
RewriteRule ^tags/following(/page/([0-9]*))?$ favorite_tags.php?page=$2 [L,QSA]
RewriteRule ^tags(/page/([0-9]*))?$ tags.php?page=$2 [L,QSA]
RewriteRule ^u/(.*?)$ user.php?username=$1 [L]
RewriteRule ^users/following(/page/([0-9]*))?$ favorite_users.php?page=$2 [L,QSA]
RewriteRule ^upload_controller$ upload_controller.php [L,QSA]
RewriteRule ^view-(desktop|mobile)$ view.php?view=$1 [L,QSA]
*/
$HTTPMethod = $_SERVER['REQUEST_METHOD'];
if ($Config['WebsitePath']) {
	$WebsitePathPosition = strpos($RequestURI, $Config['WebsitePath']);
	if ($WebsitePathPosition !== 1) {
		exit('WebsitePath Error!');
	} else {
		$ShortRequestURI = substr($RequestURI, strlen($Config['WebsitePath']));
	}
} else {
	$ShortRequestURI = $RequestURI;
}
$Routes = array();
//var_dump($ShortRequestURI);
//这里是Routes Start
$Routes['GET']['/']                                                                        = 'home';
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
$Routes['POST']['/upload_controller']                                                      = 'upload_controller';
$Routes['GET']['/view-(?<view>desktop|mobile)']                                            = 'view';

//这里是Routes End
foreach ($Routes as $Method => $SubRoutes) {
	if ($Method === $HTTPMethod) {
		$ParametersVariableName = '_' . $Method;
		foreach ($SubRoutes as $URL => $Controller) {
			if (preg_match("#^" . $URL . "$#i", $RequestURI, $Parameters)) {
				//var_dump($Parameters);
				foreach ($Parameters as $Key => $Value) {
					if (!is_int($Key)) {
						$$ParametersVariableName[$Key] = $Value;
					}
				}
				//$MicroTime = explode(' ', microtime());
				//echo number_format(($MicroTime[1] + $MicroTime[0] - $StartTime), 6) * 1000;
				require(__DIR__ . '/controller/' . $Controller . '.php');
				break 2;
			}
		}
		break;
	}
}