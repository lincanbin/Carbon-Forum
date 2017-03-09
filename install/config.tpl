<?php
// set automatically the time zone
date_default_timezone_set('Asia/Shanghai');

define('DEBUG_MODE', false);

// Salt for Cookie and Form
// Free to modify
define('SALT', 'AuthorIsLinCanbin');
define('PREFIX', 'carbon_');
define('InternalAccess', true);
/*模板文件使用
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
来防止模板文件被游客访问
*/
define('ForumLanguage', '{{Language}}');
//MemCache(d) / Redis
define('EnableMemcache', {{EnableMemcache}});
define('MemCacheHost', 'localhost');
define('MemCachePort', 11211);//Memcache: 11211, Redis: 6379
define('MemCachePrefix', '{{MemCachePrefix}}');
//Database
define('DBHost', '{{DBHost}}');
define('DBPort', '3306');
define('DBName', '{{DBName}}');
define('DBUser', '{{DBUser}}');
define('DBPassword', '{{DBPassword}}');
//Sphinx Server
define('SearchServer', '{{SearchServer}}');
define('SearchPort', '{{SearchPort}}');

define('LanguagePath', __DIR__ . '/language/' . ForumLanguage . '/');
define('LibraryPath', __DIR__ . '/library/');
define('ServicePath', __DIR__ . '/service/');

// API checking data
// List<Map<String APIKey, String APISecret>>
// Free to modify
$APISignature = array();
$APISignature['12450'] = 'b40484df0ad979d8ba7708d24c301c38';

if (DEBUG_MODE) {
	//Enable error report
	error_reporting(E_ALL); 
	ini_set('display_errors', 'On');
} else {
	//Disable error report
	ini_set('display_errors', 'Off');
}