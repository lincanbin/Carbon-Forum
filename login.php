<?php
include(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/login.php');
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!ReferCheck(Request('Post', 'FormHash'))) {
		AlertMsg($Lang['Error_Unknown_Referer'], $Lang['Error_Unknown_Referer'], 403);
	}
	$ReturnUrl  = htmlspecialchars(Request('Post', 'ReturnUrl'));
	$UserName   = strtolower(Request('Post', 'UserName'));
	$Password   = Request('Post', 'Password');
	$Expires    = min(intval(Request('Post', 'Expires', 30)), 30); //最多保持登陆30天
	$VerifyCode = intval(Request('Post', 'VerifyCode'));
	if ($UserName && $Password && $VerifyCode) {
		session_start();
		if (isset($_SESSION[$Prefix . 'VerificationCode']) && $VerifyCode === intval($_SESSION[$Prefix . 'VerificationCode'])) {
			$DBUser = $DB->row("SELECT ID,UserName,Salt,Password,UserRoleID,UserMail,UserIntro FROM " . $Prefix . "users WHERE UserName = :UserName", array(
				"UserName" => $UserName
			));
			if ($DBUser) {
				if (HashEquals($DBUser['Password'], md5($Password . $DBUser['Salt']))) {
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
				} else {
					$Error = $Lang['Password_Error'];
					$ErrorCode     = 101004;
				}
			} else {
				$Error = $Lang['User_Does_Not_Exist'];
				$ErrorCode     = 101003;
			}
		} else {
			$Error = $Lang['Verification_Code_Error'];
			$ErrorCode     = 101002;
		}
		unset($_SESSION[$Prefix . 'VerificationCode']);
	} else {
		$Error = $Lang['Forms_Can_Not_Be_Empty'];
		$ErrorCode     = 101001;
	}
}

$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Log_In'];
$ContentFile = $TemplatePath . 'login.php';
include($TemplatePath . 'layout.php');