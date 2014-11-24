<?php
$SALT = 'AuthorIsLinCanbin';//Salt，用于Cookie与Form，随便改
$Prefix = 'carbon_';
define('InternalAccess', true);
define('ForumLanguage', '{{Language}}');
/*模板文件使用
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
来防止模板文件被游客访问
*/
define('DBHost', '{{DBHost}}');
define('DBName', '{{DBName}}');
define('DBUser', '{{DBUser}}');
define('DBPassword', '{{DBPassword}}');
?>