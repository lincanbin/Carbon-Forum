<?php
require(__DIR__ . '/common.php');
require(__DIR__ . '/language/' . ForumLanguage . '/oauth.php');

function CheckOpenID()
{
	global $DB, $Prefix, $AppID, $OauthObject, $TimeStamp, $SALT, $Config, $CurUserID, $Lang;
	$OauthUserID = $DB->single("SELECT UserID FROM " . $Prefix . "app_users 
		WHERE AppID=:AppID AND OpenID = :OpenID", array(
		'AppID' => $AppID,
		'OpenID' => $OauthObject->OpenID
	));
	// 当前openid已存在，直接登陆
	if ($OauthUserID) {
		$OauthUserInfo               = $DB->row("SELECT * FROM " . $Prefix . "users WHERE ID = :UserID", array(
			"UserID" => $OauthUserID
		));
		$TemporaryUserExpirationTime = 30 * 86400 + $TimeStamp; //默认保持30天登陆状态
		SetCookies(array(
			'UserID' => $OauthUserID,
			'UserExpirationTime' => $TemporaryUserExpirationTime,
			'UserCode' => md5($OauthUserInfo['Password'] . $OauthUserInfo['Salt'] . $TemporaryUserExpirationTime . $SALT)
		), 30);
		header('location: ' . $Config['WebsitePath'] . '/');
		exit();
	}elseif ($CurUserID) {
		// 如果已登陆，直接绑定当前账号
		//Insert App user
		if( $DB->query('INSERT INTO `' . $Prefix . 'app_users`
			 (`ID`, `AppID`, `OpenID`, `AppUserName`, `UserID`, `Time`) 
			VALUES (:ID, :AppID, :OpenID, :AppUserName, :UserID, :Time)', array(
			'ID' => null,
			'AppID' => $AppID,
			'OpenID' => $OauthObject->OpenID,
			'AppUserName' => htmlspecialchars($OauthObject->NickName),
			'UserID' => $CurUserID,
			'Time' => $TimeStamp
		))){
			AlertMsg($Lang['Binding_Success'], $Lang['Binding_Success']);
		}else{
			AlertMsg($Lang['Binding_Failure'], $Lang['Binding_Failure']);
		}
	}
}
$AppID   = intval(Request('Get', 'app_id'));
$AppInfo = $DB->row('SELECT * FROM ' . $Prefix . 'app WHERE ID=:ID', array(
	'ID' => $AppID
));
if (!file_exists(__DIR__ . '/includes/Oauth.' . $AppInfo['AppName'] . '.class.php') || !$AppInfo) {
	AlertMsg('404 Not Found', '404 Not Found', 404);
} else {
	require(__DIR__ . '/includes/Oauth.' . $AppInfo['AppName'] . '.class.php');
	$OauthObject = new Oauth($AppInfo['AppKey']);
}

$Code    = Request('Get', 'code');
$State   = Request('Get', 'state');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	//如果不是认证服务器跳转回的回调页，则跳转回授权服务页
	if (!$Code || !$State || empty($_SESSION[$Prefix . 'OauthState']) || $State != $_SESSION[$Prefix . 'OauthState']) {
		//生成State值防止CSRF
		$SendState                        = md5(uniqid(rand(), TRUE));
		$_SESSION[$Prefix . 'OauthState'] = $SendState;
		// 授权地址
		$AuthorizeURL = Oauth::AuthorizeURL('http://' . $_SERVER['HTTP_HOST'] . $Config['WebsitePath'], $AppID, $AppInfo['AppKey'], $SendState);
		header("HTTP/1.1 301 Moved Permanently");
		header("Status: 301 Moved Permanently");
		header("Location: " . $AuthorizeURL);
		exit();
	}
	
	$Message = '';
	//下面是回调页面的处理
	if (!$OauthObject->GetAccessToken('http://' . $_SERVER['HTTP_HOST'] . $Config['WebsitePath'], $AppID, $AppInfo['AppSecret'], $Code)) {
		AlertMsg('400 Bad Request', '400 Bad Request', 400);
	}
	if (!$OauthObject->GetOpenID()) {
		AlertMsg('400 Bad Request', '400 Bad Request', 400);
	}
	// 非Post页，储存AccessToken
	$_SESSION[$Prefix . 'OauthAccessToken'] = $OauthObject->AccessToken;
	// 释放session防止阻塞
	session_write_close();
	
	$OauthUserID = $DB->single("SELECT UserID FROM " . $Prefix . "app_users 
		WHERE AppID=:AppID AND OpenID = :OpenID", array(
		'AppID' => $AppID,
		'OpenID' => $OauthObject->OpenID
	));
	$OauthObject->GetUserInfo();
	CheckOpenID();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!ReferCheck(Request('Post', 'FormHash')) || empty($_SESSION[$Prefix . 'OauthAccessToken']) || !$State || empty($_SESSION[$Prefix . 'OauthState']) || $State != $_SESSION[$Prefix . 'OauthState']) {
		AlertMsg($Lang['Error_Unknown_Referer'], $Lang['Error_Unknown_Referer'], 403);
	}
	// 读入Access Token
	$OauthObject->AccessToken = $_SESSION[$Prefix . 'OauthAccessToken'];
	// 释放session防止阻塞
	session_write_close();
	if (!$OauthObject->GetOpenID()) {
		AlertMsg('400 Bad Request', '400 Bad Request', 400);
	}
	$OauthUserInfo = $OauthObject->GetUserInfo();
	CheckOpenID();
	$UserName = strtolower(Request('Post', 'UserName'));
	if ($UserName && IsName($UserName)) {
		$UserExist = $DB->single("SELECT ID FROM " . $Prefix . "users WHERE UserName = :UserName", array(
			'UserName' => $UserName
		));
		if (!$UserExist) {
			$NewUserSalt     = mt_rand(100000, 999999);
			$NewUserPassword = 'zzz' . substr(md5(md5(mt_rand(1000000000, 2147483647)) . $NewUserSalt), 0, -3);
			$NewUserData     = array(
				'ID' => null,
				'UserName' => $UserName,
				'Salt' => $NewUserSalt,
				'Password' => $NewUserPassword,
				'UserMail' => '',
				'UserHomepage' => '',
				'PasswordQuestion' => '',
				'PasswordAnswer' => '',
				'UserSex' => 0,
				'NumFavUsers' => 0,
				'NumFavTags' => 0,
				'NumFavTopics' => 0,
				'NewMessage' => 0,
				'Topics' => 0,
				'Replies' => 0,
				'Followers' => 0,
				'DelTopic' => 0,
				'GoodTopic' => 0,
				'UserPhoto' => '',
				'UserMobile' => '',
				'UserLastIP' => $CurIP,
				'UserRegTime' => $TimeStamp,
				'LastLoginTime' => $TimeStamp,
				'LastPostTime' => $TimeStamp,
				'BlackLists' => '',
				'UserFriend' => '',
				'UserInfo' => '',
				'UserIntro' => '',
				'UserIM' => '',
				'UserRoleID' => 1,
				'UserAccountStatus' => 1,
				'Birthday' => date("Y-m-d", $TimeStamp)
			);
			
			$DB->query('INSERT INTO `' . $Prefix . 'users`
				(`ID`, `UserName`, `Salt`, `Password`, `UserMail`, `UserHomepage`, `PasswordQuestion`, `PasswordAnswer`, `UserSex`, `NumFavUsers`, `NumFavTags`, `NumFavTopics`, `NewMessage`, `Topics`, `Replies`, `Followers`, `DelTopic`, `GoodTopic`, `UserPhoto`, `UserMobile`, `UserLastIP`, `UserRegTime`, `LastLoginTime`, `LastPostTime`, `BlackLists`, `UserFriend`, `UserInfo`, `UserIntro`, `UserIM`, `UserRoleID`, `UserAccountStatus`, `Birthday`) 
				VALUES (:ID, :UserName, :Salt, :Password, :UserMail, :UserHomepage, :PasswordQuestion, :PasswordAnswer, :UserSex, :NumFavUsers, :NumFavTags, :NumFavTopics, :NewMessage, :Topics, :Replies, :Followers, :DelTopic, :GoodTopic, :UserPhoto, :UserMobile, :UserLastIP, :UserRegTime, :LastLoginTime, :LastPostTime, :BlackLists, :UserFriend, :UserInfo, :UserIntro, :UserIM, :UserRoleID, :UserAccountStatus, :Birthday)', $NewUserData);
			$CurUserID = $DB->lastInsertId();
			//Insert App user
			$DB->query('INSERT INTO `' . $Prefix . 'app_users`
				 (`ID`, `AppID`, `OpenID`, `AppUserName`, `UserID`, `Time`) 
				VALUES (:ID, :AppID, :OpenID, :AppUserName, :UserID, :Time)', array(
				'ID' => null,
				'AppID' => $AppID,
				'OpenID' => $OauthObject->OpenID,
				'AppUserName' => htmlspecialchars($OauthObject->NickName),
				'UserID' => $CurUserID,
				'Time' => $TimeStamp
			));
			//var_dump(htmlspecialchars($OauthObject->NickName));
			//更新全站统计数据
			$NewConfig = array(
				"NumUsers" => $Config["NumUsers"] + 1,
				"DaysUsers" => $Config["DaysUsers"] + 1
			);
			UpdateConfig($NewConfig);
			// 设置登录状态
			$TemporaryUserExpirationTime = 30 * 86400 + $TimeStamp; //默认保持30天登陆状态
			SetCookies(array(
				'UserID' => $CurUserID,
				'UserExpirationTime' => $TemporaryUserExpirationTime,
				'UserCode' => md5($NewUserPassword . $NewUserSalt . $TemporaryUserExpirationTime . $SALT)
			), 30);
			if ($OauthUserInfo) {
				//获取并缩放头像
				require(__DIR__ . "/includes/ImageResize.class.php");
				$UploadAvatar  = new ImageResize('String', URL::Get($OauthObject->AvatarURL));
				$LUploadResult = $UploadAvatar->Resize(256, 'upload/avatar/large/' . $CurUserID . '.png', 80);
				$MUploadResult = $UploadAvatar->Resize(48, 'upload/avatar/middle/' . $CurUserID . '.png', 90);
				$SUploadResult = $UploadAvatar->Resize(24, 'upload/avatar/small/' . $CurUserID . '.png', 90);
			}else{
				if(extension_loaded('gd')){
					require(__DIR__ . "/includes/MaterialDesign.Avatars.class.php");
					$Avatar = new MDAvtars(mb_substr($UserName, 0, 1, "UTF-8"), 256);
					$Avatar->Save('upload/avatar/large/' . $CurUserID . '.png', 256);
					$Avatar->Save('upload/avatar/middle/' . $CurUserID . '.png', 48);
					$Avatar->Save('upload/avatar/small/' . $CurUserID . '.png', 24);
					$Avatar->Free();
				}
			}
			header('location: ' . $Config['WebsitePath'] .  '/');
			exit();
		} else {
			$Message = $Lang['This_User_Name_Already_Exists'];
		}
	} else {
		$Message = $Lang['UserName_Error'];
	}
}

$DB->CloseConnection();
$PageTitle   = $Lang['Set_Your_Username'];
$ContentFile = $TemplatePath . 'oauth.php';
include($TemplatePath . 'layout.php');