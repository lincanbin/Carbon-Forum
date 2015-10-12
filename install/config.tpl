<?php
//输出所有错误信息，调试用
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
//关闭错误报告，生产环境用
ini_set('display_errors', 'Off');

date_default_timezone_set('Asia/Shanghai');//设置中国时区
$SALT = 'AuthorIsLinCanbin';//Salt，用于Cookie与Form，随便改
$Prefix = 'carbon_';
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
define('DBName', '{{DBName}}');
define('DBUser', '{{DBUser}}');
define('DBPassword', '{{DBPassword}}');
//Sphinx Server
define('SearchServer', '{{SearchServer}}');
define('SearchPort', '{{SearchPort}}');
