<?php
require(LanguagePath . 'register.php');
$UserName   = '';
$Email      = '';
$Password   = '';
$VerifyCode = '';
$Error    = '';
$ErrorCode     = 104000;
if ($_SERVER['REQUEST_METHOD'] == 'POST' || $IsApp) {
	if (!ReferCheck(Request('Post', 'FormHash'))) {
		AlertMsg($Lang['Error_Unknown_Referer'], $Lang['Error_Unknown_Referer'], 403);
	}
	$UserName   = strtolower(Request('Post', 'UserName'));
	$Email      = strtolower(Request('Post', 'Email'));
	$Password   = Request('Post', 'Password');
	$VerifyCode = intval(Request('Post', 'VerifyCode'));
	do{
		if ($Config['CloseRegistration'] === 'true') {
			$Error     = $Lang['Prohibit_Registration'];
			$ErrorCode = 104006;
			break;
		}


		if (!($UserName && $Email && $Password && $VerifyCode)) {
			$Error = $Lang['Forms_Can_Not_Be_Empty'];
			$ErrorCode = 104001;
			break;
		}


		if (!IsName($UserName)) {
			$Error = $Lang['UserName_Error'];
			$ErrorCode = 104002;
			break;
		}


		if (!IsEmail($Email)) {
			$Error = $Lang['Email_Error'];
			$ErrorCode = 104003;
			break;
		}


		session_start();
		$TempVerificationCode = "";
		if (isset($_SESSION[PREFIX . 'VerificationCode'])) {
			$TempVerificationCode = intval($_SESSION[PREFIX . 'VerificationCode']);
			unset($_SESSION[PREFIX . 'VerificationCode']);
		} elseif (DEBUG_MODE === true) {
			$TempVerificationCode = 1234;
		} else {
			$Error = $Lang['VerificationCode_Error'];
			$ErrorCode     = 104004;
			break;
		}
		session_write_close();
		if ($VerifyCode !== $TempVerificationCode) {
			$Error = $Lang['VerificationCode_Error'];
			$ErrorCode     = 104004;
			break;
		}


		$UserExist = $DB->single("SELECT ID FROM " . PREFIX . "users WHERE UserName = :UserName", array(
			'UserName' => $UserName
		));
		if ($UserExist) {
			$Error = $Lang['This_User_Name_Already_Exists'];
			$ErrorCode = 104005;
			break;
		}

		$NewUserSalt     = mt_rand(100000, 999999);
		$NewUserPassword = md5($Password . $NewUserSalt);
		$NewUserData     = array(
			'ID' => null,
			'UserName' => $UserName,
			'Salt' => $NewUserSalt,
			'Password' => $NewUserPassword,
			'UserMail' => $Email,
			'UserHomepage' => '',
			'PasswordQuestion' => '',
			'PasswordAnswer' => '',
			'UserSex' => 0,
			'NumFavUsers' => 0,
			'NumFavTags' => 0,
			'NumFavTopics' => 0,
			'NewReply' => 0,
			'NewMention' => 0,
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
			'LastPostTime' => $TimeStamp + intval($Config['FreezingTime']),
			'BlackLists' => '',
			'UserFriend' => '',
			'UserInfo' => '',
			'UserIntro' => '',
			'UserIM' => '',
			'UserRoleID' => 1,
			'UserAccountStatus' => 1,
			'Birthday' => date("Y-m-d", $TimeStamp)
		);
		
		$DB->query('INSERT INTO `' . PREFIX . 'users`
			(
				`ID`, `UserName`, `Salt`, `Password`, `UserMail`, 
				`UserHomepage`, `PasswordQuestion`, `PasswordAnswer`, 
				`UserSex`, `NumFavUsers`, `NumFavTags`, `NumFavTopics`, 
				`NewReply`, `NewMention`, `NewMessage`, `Topics`, `Replies`, `Followers`, 
				`DelTopic`, `GoodTopic`, `UserPhoto`, `UserMobile`, 
				`UserLastIP`, `UserRegTime`, `LastLoginTime`, `LastPostTime`, 
				`BlackLists`, `UserFriend`, `UserInfo`, `UserIntro`, `UserIM`, 
				`UserRoleID`, `UserAccountStatus`, `Birthday`
			) 
			VALUES 
			(
				:ID, :UserName, :Salt, :Password, :UserMail, 
				:UserHomepage, :PasswordQuestion, :PasswordAnswer, 
				:UserSex, :NumFavUsers, :NumFavTags, :NumFavTopics, 
				:NewReply, :NewMention, :NewMessage, :Topics, :Replies, :Followers, 
				:DelTopic, :GoodTopic, :UserPhoto, :UserMobile, 
				:UserLastIP, :UserRegTime, :LastLoginTime, :LastPostTime, 
				:BlackLists, :UserFriend, :UserInfo, :UserIntro, :UserIM, 
				:UserRoleID, :UserAccountStatus, :Birthday
			)', $NewUserData);
		$CurUserID      = $DB->lastInsertId();
		//更新全站统计数据
		$NewConfig      = array(
			"NumUsers" => $Config["NumUsers"] + 1,
			"DaysUsers" => $Config["DaysUsers"] + 1
		);
		UpdateConfig($NewConfig);
		$TemporaryUserExpirationTime = 30 * 86400 + $TimeStamp;//默认保持30天登陆状态
		if ($CurUserID == 1) {
			$DB->query("UPDATE `" . PREFIX . "users` SET UserRoleID=5 WHERE `ID`=?", array(
				$CurUserID
			));
		}
		if (extension_loaded('gd')) {
			require(LibraryPath . "MaterialDesign.Avatars.class.php");
			$Avatar = new MDAvtars(mb_substr($UserName, 0, 1, "UTF-8"), 256);
			$Avatar->Save(__DIR__ . '/../upload/avatar/large/' . $CurUserID . '.png', 256);
			$Avatar->Save(__DIR__ . '/../upload/avatar/middle/' . $CurUserID . '.png', 48);
			$Avatar->Save(__DIR__ . '/../upload/avatar/small/' . $CurUserID . '.png', 24);
			$Avatar->Free();
		}
		if (!$IsApp) {
			SetCookies(array(
				'UserID' => $CurUserID,
				'UserExpirationTime' => $TemporaryUserExpirationTime,
				'UserCode' => md5($NewUserPassword . $NewUserSalt . $TemporaryUserExpirationTime . SALT)
			), 30);
			Redirect('', 'registered');
		}
	}while(false);
}

$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Sign_Up'];
$ContentFile = $TemplatePath . 'register.php';
include($TemplatePath . 'layout.php');