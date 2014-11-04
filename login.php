<?php
include(dirname(__FILE__) . '/common.php');

$error = '';
$UserName = '';
$ReturnUrl = array_key_exists('HTTP_REFERER',$_SERVER) ? $_SERVER["HTTP_REFERER"] : '';

if(array_key_exists('logout',$_GET))
{
	SetCookies(array('UserID' => '', 'UserCode' => ''), 1);
	if($ReturnUrl){
		header('location: '.$ReturnUrl);
		exit('logout');
	}else{
		header('location: '.$Config['WebsitePath'].'/');
		exit('logout');
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!ReferCheck($_POST['FormHash'])) {
		AlertMsg('登陆失败','来源错误(unknown referer)',403);
	}
	$ReturnUrl = trim($_POST["ReturnUrl"]);
	$UserName = strtolower(trim($_POST["UserName"]));
	$Password = trim($_POST["Password"]);
	$Expires = intval(trim($_POST["Expires"]));
	$VerifyCode = intval(trim($_POST["VerifyCode"]));
	if($UserName && $Password && $VerifyCode)
	{
		session_start();
		if($VerifyCode == intval($_SESSION['code']))
		{
			$DBUser =$DB->row("SELECT ID,UserName,Salt,Password FROM ".$Prefix."users WHERE UserName = :UserName", array("UserName"=>$UserName));
			if($DBUser){
				if(md5(md5($Password).$DBUser['Salt']) == $DBUser['Password'])
				{
					$DB->query("UPDATE ".$Prefix."users SET LastLoginTime = :LastLoginTime,UserLastIP = :UserLastIP WHERE ID = :ID",array("LastLoginTime"=>$TimeStamp,"UserLastIP"=>CurIP(),"ID"=>$DBUser['ID'])); 
					SetCookies(array('UserID' => $DBUser['ID'], 'UserCode' => md5($DBUser['Password'].$DBUser['Salt'].$Style.$SALT)), $Expires);
					if($ReturnUrl){
						header('location: '.$ReturnUrl);
						exit('logined');
					}else{
						header('location: '.$Config['WebsitePath'].'/');
						exit('logined');
					}
				}else{
					$error = '密码错误';
				}
			}else{
				$error = '用户不存在';
			}
		}else{
			$error = '验证码错误！';
		}
	}else{
		$error = '用户名/密码/验证码必填';
	}
}
$DB->CloseConnection();
// 页面变量
$PageTitle = '登 录';
$ContentFile = $TemplatePath.'login.php';
include($TemplatePath.'layout.php');
?>