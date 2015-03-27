<?php
include(dirname(__FILE__) . '/common.php');
require(dirname(__FILE__) . '/language/' . ForumLanguage . '/login.php');
$error     = '';
$UserName  = '';
$ReturnUrl = array_key_exists('HTTP_REFERER', $_SERVER) ? $_SERVER["HTTP_REFERER"] : '';

if (array_key_exists('logout', $_GET)) {
	SetCookies(array(
		'UserID' => '',
		'UserCode' => ''
	), 1);
	if ($ReturnUrl) {
		header('location: ' . $ReturnUrl);
		exit('logout');
	} else {
		header('location: ' . $Config['WebsitePath'] . '/');
		exit('logout');
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!ReferCheck($_POST['FormHash'])) {
		AlertMsg($Lang['Error_Unknown_Referer'], $Lang['Error_Unknown_Referer'], 403);
	}
	$ReturnUrl  = trim($_POST["ReturnUrl"]);
	$UserName   = strtolower(trim($_POST["UserName"]));
	$Password   = trim($_POST["Password"]);
	$Expires    = intval(trim($_POST["Expires"]));
	$VerifyCode = intval(trim($_POST["VerifyCode"]));
	if ($UserName && $Password && $VerifyCode) {
		session_start();
		if (isset($_SESSION[$Prefix . 'VerificationCode']) && $VerifyCode == intval($_SESSION[$Prefix . 'VerificationCode'])) {
			$DBUser = $DB->row("SELECT ID,UserName,Salt,Password FROM " . $Prefix . "users WHERE UserName = :UserName", array(
				"UserName" => $UserName
			));
			if ($DBUser) {
				if (md5(md5($Password) . $DBUser['Salt']) == $DBUser['Password']) {
					UpdateUserInfo(array(
						'LastLoginTime' => $TimeStamp,
						'UserLastIP' => CurIP()
					), $DBUser['ID']);
					SetCookies(array(
						'UserID' => $DBUser['ID'],
						'UserCode' => md5($DBUser['Password'] . $DBUser['Salt'] . $Style . $SALT)
					), $Expires);
					if ($ReturnUrl) {
						header('location: ' . $ReturnUrl);
						exit('logined');
					} else {
						header('location: ' . $Config['WebsitePath'] . '/');
						exit('logined');
					}
				} else {
					$error = $Lang['Password_Error'];
				}
			} else {
				$error = $Lang['User_Does_Not_Exist'];
			}
		} else {
			$error = $Lang['Verification_Code_Error'];
		}
		unset($_SESSION[$Prefix . 'VerificationCode']);
	} else {
		$error = $Lang['Forms_Can_Not_Be_Empty'];
	}
}

$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Log_In'];
$ContentFile = $TemplatePath . 'login.php';
include($TemplatePath . 'layout.php');