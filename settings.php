<?php
include(dirname(__FILE__) . '/common.php');
require(dirname(__FILE__) . '/language/' . ForumLanguage . '/settings.php');
Auth(1);
$UploadAvatarMessage   = '';
$UpdateUserInfoMessage = '';
$ChangePasswordMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$Action = Request('POST', 'Action', false);
	switch ($Action) {
		case 'UploadAvatar':
			if ($_FILES['Avatar']['size'] && $_FILES['Avatar']['size'] < 1048576) {
				require(dirname(__FILE__) . "/includes/ImageResize.class.php");
				$UploadAvatar  = new ImageResize('Avatar');
				$LUploadResult = $UploadAvatar->Resize(256, 'upload/avatar/large/' . $CurUserID . '.png', 80);
				$MUploadResult = $UploadAvatar->Resize(48, 'upload/avatar/middle/' . $CurUserID . '.png', 90);
				$SUploadResult = $UploadAvatar->Resize(24, 'upload/avatar/small/' . $CurUserID . '.png', 90);
				if ($LUploadResult && $MUploadResult && $SUploadResult) {
					$UploadAvatarMessage = $Lang['Avatar_Upload_Success'];
				} else {
					$UploadAvatarMessage = $Lang['Avatar_Upload_Failure'];
				}
				
			} else {
				$UploadAvatarMessage = $Lang['Avatar_Is_Oversize'];
			}
			break;
		
		case 'UpdateUserInfo':
			$CurUserInfo['UserSex']      = intval(Request('POST', 'UserSex', 0));
			$CurUserInfo['UserMail']     = IsEmail(Request('POST', 'UserMail', $CurUserInfo['UserMail'])) ? Request('POST', 'UserMail', $CurUserInfo['UserMail']) : $CurUserInfo['UserMail'];
			$CurUserInfo['UserHomepage'] = CharCV(Request('POST', 'UserHomepage', $CurUserInfo['UserHomepage']));
			$CurUserInfo['UserIntro']    = CharCV(Request('POST', 'UserIntro', $CurUserInfo['UserIntro']));
			$UpdateUserInfoResult        = UpdateUserInfo(array(
				'UserSex' => $CurUserInfo['UserSex'],
				'UserMail' => $CurUserInfo['UserMail'],
				'UserHomepage' => $CurUserInfo['UserHomepage'],
				'UserIntro' => $CurUserInfo['UserIntro']
			));
			if ($UpdateUserInfoResult) {
				$UpdateUserInfoMessage = $Lang['Profile_Modified_Successfully'];
			} else {
				$UpdateUserInfoMessage = $Lang['Profile_Do_Not_Modify'];
			}
			
			break;
		
		case 'ChangePassword':
			$OriginalPassword = trim($_POST['OriginalPassword']);
			$NewPassword      = trim($_POST['NewPassword']);
			$NewPassword2     = trim($_POST['NewPassword2']);
			if ($OriginalPassword && $NewPassword && $NewPassword2) {
				if ($NewPassword == $NewPassword2) {
					if (md5(md5($OriginalPassword) . $CurUserInfo['Salt']) == $CurUserInfo['Password']) {
						if ($OriginalPassword != $NewPassword) {
							//$NewSalt = mt_rand(100000,999999);
							//修改Salt会导致密码问题出错
							$NewSalt         = $CurUserInfo['Salt'];
							$NewPasswordHash = md5(md5($NewPassword) . $NewSalt);
							if (UpdateUserInfo(array(
								'Salt' => $NewSalt,
								'Password' => $NewPasswordHash
							))) {
								$TemporaryUserExpirationTime = 30 * 86400 + $TimeStamp;//默认保持30天登陆状态
								SetCookies(array(
									'UserExpirationTime' => $TemporaryUserExpirationTime,
									'UserCode' => md5($NewPasswordHash . $NewSalt . $TemporaryUserExpirationTime . $SALT)
								), 30);
								$CurUserInfo['Salt']     = $NewSalt;
								$CurUserInfo['Password'] = $NewPasswordHash;
								$ChangePasswordMessage   = $Lang['Change_Password_Success'];
							} else {
								$ChangePasswordMessage = $Lang['Change_Password_Failure'];
							}
						} else {
							$ChangePasswordMessage = $Lang['Password_Do_Not_Modify'];
						}
					} else {
						$ChangePasswordMessage = $Lang['Current_Password_Is_Uncorrect'];
					}
				} else {
					$ChangePasswordMessage = $Lang['Passwords_Inconsistent'];
				}
			} else {
				$ChangePasswordMessage = $Lang['Forms_Can_Not_Be_Empty'];
			}
			break;
		
		default:
			# code...
			break;
	}
}
$DB->CloseConnection();
// 页面变量
$PageTitle   = $Lang['Settings'];
$ContentFile = $TemplatePath . 'settings.php';
include($TemplatePath . 'layout.php');