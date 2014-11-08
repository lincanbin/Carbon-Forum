<?php
include(dirname(__FILE__) . '/common.php');
Auth(1);
$UploadAvatarMessage = '';
$UpdateUserInfoMessage = '';
$ChangePasswordMessage = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$Action = Request('POST','Action',false);
	switch ($Action) {
		case 'UploadAvatar':
			if($_FILES['Avatar']['size'] && $_FILES['Avatar']['size'] < 1048576){
				require(dirname(__FILE__)."/includes/ImageResize.class.php");
				$UploadAvatar = new ImageResize('Avatar');
				$LUploadResult = $UploadAvatar->Resize(256, 'upload/avatar/large/'.$CurUserID.'.png', 80);
				$MUploadResult = $UploadAvatar->Resize(48, 'upload/avatar/middle/'.$CurUserID.'.png', 90);
				$SUploadResult = $UploadAvatar->Resize(24, 'upload/avatar/small/'.$CurUserID.'.png', 90);
				if($LUploadResult && $MUploadResult && $SUploadResult){
					$UploadAvatarMessage = '头像上传成功';
				}else{
					$UploadAvatarMessage = '头像上传失败';
				}
				
			}else{
				$UploadAvatarMessage = '头像超过1M，上传失败';
			}
			break;

		case 'UpdateUserInfo':
			$CurUserInfo['UserSex'] = intval(Request('POST','UserSex',0));
			$CurUserInfo['UserMail'] = IsEmail(Request('POST','UserMail',$CurUserInfo['UserMail'])) ? Request('POST','UserMail',$CurUserInfo['UserMail']) : $CurUserInfo['UserMail'];
			$CurUserInfo['UserHomepage'] = CharCV(Request('POST','UserHomepage',$CurUserInfo['UserHomepage']));
			$CurUserInfo['UserIntro'] = CharCV(Request('POST','UserIntro',$CurUserInfo['UserIntro']));
			$UpdateUserInfoResult = $DB->query("UPDATE `".$Prefix."users` SET UserSex=:UserSex, UserMail=:UserMail, UserHomepage=:UserHomepage, UserIntro=:UserIntro  WHERE `ID`=:CurUserID",array(
					'UserSex' => $CurUserInfo['UserSex'],
					'UserMail' => $CurUserInfo['UserMail'],
					'UserHomepage' => $CurUserInfo['UserHomepage'],
					'UserIntro' => $CurUserInfo['UserIntro'],
					'CurUserID' => $CurUserID
					));
			if($UpdateUserInfoResult){
				$UpdateUserInfoMessage = '资料修改成功';
			}else{
				$UpdateUserInfoMessage = '资料无改动';
			}
			
			break;

		case 'ChangePassword':
			$OriginalPassword = trim($_POST['OriginalPassword']);
			$NewPassword = trim($_POST['NewPassword']);
			$NewPassword2 = trim($_POST['NewPassword2']);
			if($OriginalPassword && $NewPassword && $NewPassword2){
				if($NewPassword == $NewPassword2){
					if(md5(md5($OriginalPassword).$CurUserInfo['Salt']) == $CurUserInfo['Password']){
						if($OriginalPassword != $NewPassword){
							//$NewSalt = mt_rand(100000,999999);
							//修改Salt会导致密码问题出错
							$NewSalt = $CurUserInfo['Salt'];
							$NewPasswordHash = md5(md5($NewPassword).$NewSalt);
							if($DB->query("UPDATE `".$Prefix."users` SET Salt=?,Password=? WHERE `ID`=?",array($NewSalt, $NewPasswordHash, $CurUserID))){
								SetCookies(array('UserCode' => md5($NewPasswordHash.$Style.$SALT)));
								$CurUserInfo['Salt'] = $NewSalt;
								$CurUserInfo['Password'] = $NewPasswordHash;
								$ChangePasswordMessage = '密码已成功更改，请记住新密码';
							}else{
								$ChangePasswordMessage = '密码更改失败';
							}
						}else{
							$ChangePasswordMessage = '输入的新密码不能与原密码相同';
						}
					}else{
						$ChangePasswordMessage = '输入的当前密码不正确';
					}
				}else{
					$ChangePasswordMessage = '新密码、重复新密码不一致';
				}
			}else{
				$ChangePasswordMessage = '请填写完整，当前当前密码、新密码、重复新密码';
			}
			break;
		
		default:
			# code...
			break;
	}
}
$DB->CloseConnection();
// 页面变量
$PageTitle = '个人设置';
$ContentFile = $TemplatePath.'settings.php';
include($TemplatePath.'layout.php');
?>