<?php
$CookiesPrefix = isset($_GET['cookie_prefix']) ? $_GET['cookie_prefix'] : false;
if($CookiesPrefix){
	$View = isset($_GET['view']) ? $_GET['view'] : 'desktop';
	$Callback = isset($_GET['callback']) ? $_GET['callback'] : '/';
	$WebsitePath = isset($_GET['website_path']) ? $_GET['website_path'] : '';
	setcookie($CookiesPrefix . 'View', $View == 'mobile' ? 'mobile' : 'desktop', $_SERVER['REQUEST_TIME'] + 86400 * 30, $WebsitePath . '/', null, false, true);
	header("HTTP/1.1 301 Moved Permanently");
	header("Status: 301 Moved Permanently");
	header("Location: http://". $Callback);
}else{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
}