<?php
include(dirname(__FILE__) . '/common.php');

$UserName = '';
$Email = '';
$Password = '';
$Password2 = '';
$VerifyCode = '';
$Message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(!ReferCheck($_POST['FormHash']))
	{
		AlertMsg('来源错误','来源错误(unknown referer)',403);
	}
	$UserName = strtolower(Request('Post','UserName'));
	$Email = strtolower(Request('Post','Email'));
	$Password = Request('Post','Password');
	$Password2 = Request('Post','Password2');
	$VerifyCode = intval(Request('Post','VerifyCode'));
	if ($UserName && $Email && $Password && $Password2 && $VerifyCode) {
		if ($Password === $Password2) {
			if (IsName($UserName)) {
				if(IsEmail($Email)){
					error_reporting(0);
					session_start();
					if ($VerifyCode === intval($_SESSION['code'])) {
						$UserExist = $DB->single("SELECT ID FROM ".$Prefix."users WHERE UserName = :UserName", array('UserName'=>$UserName));
						if (!$UserExist) {
							$NewUserSalt = mt_rand(100000,999999);
							$NewUserPassword = md5(md5($Password).$NewUserSalt);
							$NewUserData = array(
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

							$NewTopicResult = $DB->query('INSERT INTO `'.$Prefix.'users`(`ID`, `UserName`, `Salt`, `Password`, `UserMail`, `UserHomepage`, `PasswordQuestion`, `PasswordAnswer`, `UserSex`, `NumFavUsers`, `NumFavTags`, `NumFavTopics`, `NewMessage`, `Topics`, `Replies`, `DelTopic`, `GoodTopic`, `UserPhoto`, `UserMobile`, `UserLastIP`, `UserRegTime`, `LastLoginTime`, `LastPostTime`, `BlackLists`, `UserFriend`, `UserInfo`, `UserIntro`, `UserIM`, `UserRoleID`, `UserAccountStatus`, `Birthday`) VALUES (:ID, :UserName, :Salt, :Password, :UserMail, :UserHomepage, :PasswordQuestion, :PasswordAnswer, :UserSex, :NumFavUsers, :NumFavTags, :NumFavTopics, :NewMessage, :Topics, :Replies, :DelTopic, :GoodTopic, :UserPhoto, :UserMobile, :UserLastIP, :UserRegTime, :LastLoginTime, :LastPostTime, :BlackLists, :UserFriend, :UserInfo, :UserIntro, :UserIM, :UserRoleID, :UserAccountStatus, :Birthday)',$NewUserData);
							$CurUserID = $DB->lastInsertId();
							//更新全站统计数据
							$NewConfig = array(
								"NumUsers" => $Config["NumUsers"]+1
								);
							UpdateConfig($NewConfig);
							SetCookies(array('UserID' => $CurUserID, 'UserCode' => md5($NewUserPassword.$NewUserSalt.$Style.$SALT)), 30);
							if($CurUserID == 1)
							{
								$DB->query("UPDATE `".$Prefix."users` SET UserRoleID=5 WHERE `ID`=?",array($CurUserID));
							}
							header('location: '.$Config['WebsitePath'].'/');
						} else {
							$Message = '这名字太火了，已经被抢注了，换一个吧！';
						}
					} else {
						$Message = '验证码输入不对';
					}
				}else{
					$Message = '电子邮箱不符合规则，电子邮箱正确格式为abc@domain.com';
				}
			} else {
				$Message = '用户名不符合规则。用户名为4-20个字符，不可全为数字，可以包括字母、数字、中文、半角符号“_”、“-”与“.”';
			}
		} else {
			$Message = '密码、重复密码 输入不一致';
		}
	} else {
		$Message = '用户名、密码、重复密码、验证码 必填';
	}
}

$DB->CloseConnection();
// 页面变量
$PageTitle = '注 册';
$ContentFile = $TemplatePath.'register.php';
include($TemplatePath.'layout.php');
?>
