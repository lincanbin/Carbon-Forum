<?php
date_default_timezone_set('Asia/Shanghai');//设置中国时区
$SALT = 'AuthorIsLinCanbin';//Salt，用于Cookie与Form，随便改
$Prefix = 'carbon_';
define('InternalAccess', true);
define('ForumLanguage', 'zh-cn');
/*模板文件使用
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
来防止模板文件被游客访问
*/
define('DBHost', '127.0.0.1');
define('DBName', 'carbon');
define('DBUser', 'root');
define('DBPassword', '');