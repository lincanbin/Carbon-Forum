<?php
require(dirname(__FILE__) . '/common.php');
require(dirname(__FILE__) . '/language/' . ForumLanguage . '/reset_password.php');
$AccessToken = base64_decode(Request('Get', 'access_token'));
$AccessTokenArray = $AccessToken?explode('|', $AccessToken):false;

if($_SERVER['REQUEST_METHOD'] == 'POST' && $AccessTokenArray){
	$UserName = $AccessToken[0];
	$CurUserExpirationTime = intval($AccessToken[1]);
	$CurUserCode = $AccessToken[2];
	$UserInfo = array();
	$UserInfo = $DB->row('SELECT * FROM ' . $Prefix . 'users Where UserName=:UserName', array(
		'UserName' => $UserName
	));
	if (!$UserInfo)
		AlertMsg('404 Not Found', '404 Not Found', 404);
}
$DB->CloseConnection();
$PageTitle    = 'reset password';
$ContentFile  = $TemplatePath . 'reset_password.php';
include($TemplatePath . 'layout.php');