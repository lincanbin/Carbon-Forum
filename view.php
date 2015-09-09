<?php
require(__DIR__ . "/config.php");
require(__DIR__ . "/includes/PDO.class.php");
$DB = new Db(DBHost, DBName, DBUser, DBPassword);
foreach ($DB->query('SELECT ConfigName,ConfigValue FROM ' . $Prefix . 'config WHERE ConfigName in (?)', array(
	'CookiePrefix',
	'WebsitePath'
)) as $ConfigArray) {
	$Config[$ConfigArray['ConfigName']] = $ConfigArray['ConfigValue'];
}
$DB->CloseConnection();
$CookiesPrefix = $Config['CookiePrefix'];
//$CookiesPrefix = isset($_GET['cookie_prefix']) ? $_GET['cookie_prefix'] : false;
if ($CookiesPrefix) {
	$View        = isset($_GET['view']) ? $_GET['view'] : 'desktop';
	$Callback    = isset($_GET['callback']) ? $_GET['callback'] : '/';
	$WebsitePath = $Config['WebsitePath'];
	setcookie($CookiesPrefix . 'View', $View == 'mobile' ? 'mobile' : 'desktop', $_SERVER['REQUEST_TIME'] + 86400 * 30, $WebsitePath . '/', null, false, true);
	header("HTTP/1.1 301 Moved Permanently");
	header("Status: 301 Moved Permanently");
	header("Location: http://" . $Callback);
} else {
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
}