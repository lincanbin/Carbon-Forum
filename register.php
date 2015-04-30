<?php
include(dirname(__FILE__) . '/common.php');
require(dirname(__FILE__) . '/language/' . ForumLanguage . '/register.php');
$UserName   = '';
$Email      = '';
$Password   = '';
$Password2  = '';
$VerifyCode = '';
$Message    = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!ReferCheck($_POST['FormHash'])) {
		AlertMsg($Lang['Error_Unknown_Referer'], $Lang['Error_Unknown_Referer'], 403);
	}
	$UserName   = strtolower(Request('Post', 'UserName'));
	$Email      = strtolower(Request('Post', 'Email'));
	$Password   = Request('Post', 'Password');
	$Password2  = Request('Post', 'Password2');
	$VerifyCode = intval(Request('Post', 'VerifyCode'));
	if ($UserName && $Email && $Password && $Password2 && $VerifyCode) {
		if ($Password === $Password2) {
			if (IsName($UserName)) {
				if (IsEmail($Email)) {
					error_reporting(0);
					session_start();
					if (isset($_SESSION[$Prefix . 'VerificationCode']) && $VerifyCode === intval($_SESSION[$Prefix . 'VerificationCode'])) {
						$UserExist = $DB->single("SELECT ID FROM " . $Prefix . "users WHERE UserName = :UserName", array(
							'UserName' => $UserName
						));
						if (!$UserExist) {
							$NewUserSalt     = mt_rand(100000, 999999);
							$NewUserPassword = md5(md5($Password) . $NewUserSalt);
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
							
							$NewTopicResult = $DB->query('INSERT INTO `' . $Prefix . 'users`(`ID`, `UserName`, `Salt`, `Password`, `UserMail`, `UserHomepage`, `PasswordQuestion`, `PasswordAnswer`, `UserSex`, `NumFavUsers`, `NumFavTags`, `NumFavTopics`, `NewMessage`, `Topics`, `Replies`, `Followers`, `DelTopic`, `GoodTopic`, `UserPhoto`, `UserMobile`, `UserLastIP`, `UserRegTime`, `LastLoginTime`, `LastPostTime`, `BlackLists`, `UserFriend`, `UserInfo`, `UserIntro`, `UserIM`, `UserRoleID`, `UserAccountStatus`, `Birthday`) VALUES (:ID, :UserName, :Salt, :Password, :UserMail, :UserHomepage, :PasswordQuestion, :PasswordAnswer, :UserSex, :NumFavUsers, :NumFavTags, :NumFavTopics, :NewMessage, :Topics, :Replies, :Followers, :DelTopic, :GoodTopic, :UserPhoto, :UserMobile, :UserLastIP, :UserRegTime, :LastLoginTime, :LastPostTime, :BlackLists, :UserFriend, :UserInfo, :UserIntro, :UserIM, :UserRoleID, :UserAccountStatus, :Birthday)', $NewUserData);
							$CurUserID      = $DB->lastInsertId();
							//更新全站统计数据
							$NewConfig      = array(
								"NumUsers" => $Config["NumUsers"] + 1
							);
							UpdateConfig($NewConfig);
							$TemporaryUserExpirationTime = 30 * 86400 + $TimeStamp;//默认保持30天登陆状态
							SetCookies(array(
								'UserID' => $CurUserID,
								'UserExpirationTime' => $TemporaryUserExpirationTime,
								'UserCode' => md5($NewUserPassword . $NewUserSalt . $TemporaryUserExpirationTime . $SALT)
							), 30);
							if ($CurUserID == 1) {
								$DB->query("UPDATE `" . $Prefix . "users` SET UserRoleID=5 WHERE `ID`=?", array(
									$CurUserID
								));
							}
							if(extension_loaded('gd')){
								require(dirname(__FILE__) . "/includes/MaterialDesign.Avatars.class.php");
								$Avatar = new MDAvtars(mb_substr($UserName, 0, 1, "UTF-8"), 256);
								$Avatar->Save('upload/avatar/large/' . $CurUserID . '.png', 256);
								$Avatar->Save('upload/avatar/middle/' . $CurUserID . '.png', 48);
								$Avatar->Save('upload/avatar/small/' . $CurUserID . '.png', 24);
								$Avatar->Free();
							}
							header('location: ' . $Config['WebsitePath'] . '/');
						} else {
							$Message = $Lang['This_User_Name_Already_Exists'];
						}
					} else {
						$Message = $Lang['VerificationCode_Error'];
					}
					unset($_SESSION[$Prefix . 'VerificationCode']);
				} else {
					$Message = $Lang['Email_Error'];
				}
			} else {
				$Message = $Lang['UserName_Error'];
			}
		} else {
			$Message = $Lang['Passwords_Inconsistent'];
		}
	} else {
		$Message = $Lang['Forms_Can_Not_Be_Empty'];
	}
}

$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Sign_Up'];
$ContentFile = $TemplatePath . 'register.php';
include($TemplatePath . 'layout.php');