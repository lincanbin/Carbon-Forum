<?php
include(dirname(__FILE__) . '/common.php');

$UrlPathArray = explode("/", str_replace($Config['WebsitePath'],'',$_SERVER['REQUEST_URI']));
if($UrlPathArray[1] == "upload" && $UrlPathArray[2] == "avatar" && $UrlPathArray[3])
{
	$DefaultAvatar = fopen('upload/avatar/'.$UrlPathArray[3].'/default.png', "rb");
	header("HTTP/1.0 200 OK");
	header("Status: 200 OK");
	header("Accept-Ranges: bytes");
	header("Content-Type: image/png");
	echo stream_get_contents($DefaultAvatar);
}else{
	echo '404';
}
//var_dump($REQUEST_URI);
?>