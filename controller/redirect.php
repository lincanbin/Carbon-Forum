<?php
$CookiesPrefix = $Config['CookiePrefix'];
if ($CookiesPrefix) {
	$View        = Request('Get', 'view', 'desktop');
	$Callback    = Request('Get', 'callback', '/');
	SetCookies(array(
		'View' => $View == 'mobile' ? 'mobile' : 'desktop'
	), 30);
	header("HTTP/1.1 301 Moved Permanently");
	header("Status: 301 Moved Permanently");
	header("Location: " . $CurProtocol . ($View == 'mobile' ? $Config['MobileDomainName'] : $Config['MainDomainName']) . $Callback);
} else {
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
}