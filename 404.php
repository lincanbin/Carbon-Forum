<?php
require_once(__DIR__."/config.php");//Timezone
//For IIS ISAPI_Rewrite
$RequestURI = isset($_SERVER['HTTP_X_REWRITE_URL']) ? $_SERVER['HTTP_X_REWRITE_URL'] : $_SERVER["REQUEST_URI"];
if(preg_match('/upload\/avatar\/(large|middle|small)\/[0-9]+.png/i', $RequestURI, $AvatarSize))
{	
	//header('Location: default.png');
	header("HTTP/1.0 200 OK");
	header("Status: 200 OK");
	$DefaultAvatar = fopen('upload/avatar/'.$AvatarSize[1].'/default.png', "rb");
	header("Accept-Ranges: bytes");
	header("Content-Type: image/png");
	header('Expires: '.gmdate ("D, d M Y H:i:s",strtotime("+7 day"))." GMT");
	//Fri, 07 Nov 2014 18:15:11 GMT
	echo stream_get_contents($DefaultAvatar);
	fclose($DefaultAvatar);

}else{
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	echo 'Error: 404 NOT FOUND';
}