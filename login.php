<?php
error_reporting(0); //Disable Error Reporting Safety
include(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/login.php');
if(!array_key_exists('HTTP_MOD_REWRITE', $_SERVER)) die ("Please Enable Mod Rewrite before passing"); //Check for Mod Rewrite
if (file_exists("install")) die ("Please delete the install folder before passing."); //Make sure install does not exist for safety
$Error     = '';
$ErrorCode     = 101000;
$UserName  = '';
$ReturnUrl = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER["HTTP_REFERER"]) : '';

if (isset($_GET['logout']) && $_GET['logout'] == $CurUserCode) {
	SetCookies(array(
		'UserID' => '',
		'CurUserExpirationTime' => '',
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' || $IsApp) {
	if (!ReferCheck(Request('Post', 'FormHash'))) {
		AlertMsg($Lang['Error_Unknown_Referer'], $Lang['Error_Unknown_Referer'], 403);
	}
	$ReturnUrl  = htmlspecialchars(Request('Post', 'ReturnUrl'));
	$UserName   = strtolower(Request('Post', 'UserName'));
	$Password   = Request('Post', 'Password');
	$Expires    = min(intval(Request('Post', 'Expires', 30)), 30); //最多保持登陆30天
	$VerifyCode = intval(Request('Post', 'VerifyCode'));
	do{
		if (!$UserName || !$Password || !$VerifyCode) {
			$Error = $Lang['Forms_Can_Not_Be_Empty'];
			$ErrorCode     = 101001;
			break;
		}

		session_start();
		$TempVerificationCode = "";
		if(isset($_SESSION[$Prefix . 'VerificationCode'])){
			$TempVerificationCode = intval($_SESSION[$Prefix . 'VerificationCode']);
			unset($_SESSION[$Prefix . 'VerificationCode']);
		}else{
			$Error = $Lang['Verification_Code_Error'];
			$ErrorCode     = 101002;
			break;
		}
		session_write_close();
		if ($VerifyCode !== $TempVerificationCode) {
			$Error = $Lang['Verification_Code_Error'];
			$ErrorCode     = 101002;
			break;
		}


		$DBUser = $DB->row("SELECT ID,UserName,Salt,Password,UserRoleID,UserMail,UserIntro FROM " . $Prefix . "users WHERE UserName = :UserName", array(
			"UserName" => $UserName
		));
		if (!$DBUser) {
			$Error = $Lang['User_Does_Not_Exist'];
			$ErrorCode     = 101003;
			break;
		}

		if (!HashEquals($DBUser['Password'], md5($Password . $DBUser['Salt']))) {
			$Error = $Lang['Password_Error'];
			$ErrorCode     = 101004;
			break;
		}

		UpdateUserInfo(array(
			'LastLoginTime' => $TimeStamp,
			'UserLastIP' => CurIP()
		), $DBUser['ID']);
		$TemporaryUserExpirationTime = $Expires * 86400 + $TimeStamp;
		if( !$IsApp ){
			SetCookies(array(
				'UserID' => $DBUser['ID'],
				'UserExpirationTime' => $TemporaryUserExpirationTime,
				'UserCode' => md5($DBUser['Password'] . $DBUser['Salt'] . $TemporaryUserExpirationTime . $SALT)
			), $Expires);
			if ( $ReturnUrl ) {
				header('location: ' . $ReturnUrl);
				exit('logined');
			} else {
				header('location: ' . $Config['WebsitePath'] . '/');
				exit('logined');
			}
		}
	}while(false);
}

$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Log_In'];
$ContentFile = $TemplatePath . 'login.php';
include($TemplatePath . 'layout.php');
