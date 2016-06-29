<?php
if (!defined('InternalAccess')) exit('{"Status": 0, "ErrorCode": "403", "ErrorMessage": "403"}');
if($Error){
?>{
	"Status": 0,
	"ErrorCode": <?php echo $ErrorCode;?>,
	"ErrorMessage": "<?php echo $Error;?>"
}
<?php
}else{
?>
{
	"Status": 1,
	"UserID": <?php echo $CurUserID; ?>,
	"UserExpirationTime" : <?php echo $TemporaryUserExpirationTime; ?>,
	"UserCode" : "<?php echo md5($NewUserPassword . $NewUserSalt . $TemporaryUserExpirationTime . SALT); ?>",
	"UserInfo" : <?php 
	unset($NewUserData['Password']);
	unset($NewUserData['Salt']);
	unset($NewUserData['PasswordQuestion']);
	unset($NewUserData['PasswordAnswer']);
	$NewUserData['ID'] = $CurUserID;
	echo json_encode($NewUserData);
	?>
}
<?php
}
?>