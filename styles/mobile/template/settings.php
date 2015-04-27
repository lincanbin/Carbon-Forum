<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<div id="header">
	<a id="menubadge" onclick="JavaScript:af.ui.toggleSideMenu()" class="menuButton"></a>
</div>
<div id="content">
<?php } ?>
	<div data-title="<?php echo $PageTitle; ?>" id="Settings" class="panel" selected="true">

		<div class="formGroupHead"><?php echo $Lang['Avatar_Settings']; ?></div>
<?php
if($UploadAvatarMessage){
?>
			<script type="text/javascript">CarbonAlert("<?php echo $UploadAvatarMessage; ?>");</script>
<?php
}
?>
			<form method="post" enctype="multipart/form-data" action="<?php echo $Config['WebsitePath']; ?>/settings">
				<div class="input-group">
					<input type="hidden" name="Action" value="UploadAvatar">
					<img id="CurAvatar" style="width:100%;" src="<?php echo $Config['WebsitePath']; ?>/upload/avatar/large/<?php echo $CurUserID; ?>.png?cache=<?php echo $TimeStamp; ?>" alt="<?php echo $CurUserName; ?>" />
					<p><?php echo $Lang['You_Can_Replace_Your_Avatar_Here']; ?></p>
					<p></p>
					<input type="file" accept="image/*" id="Avatar" name="Avatar">
					<p></p>
					<p><?php echo $Lang['Max_Avatar_Size_Limit']; ?></p>
					<p></p>
					<input type="submit" class="button" value="<?php echo $Lang['Upload_Avatar']; ?>" name="submit" />
				</div>
			</form>



		<div class="formGroupHead"><?php echo $Lang['Profile_Settings']; ?></div>
<?php
if($UpdateUserInfoMessage){
?>
				<script type="text/javascript">CarbonAlert("<?php echo $UpdateUserInfoMessage; ?>");</script>
<?php
}
?>
				<form method="post" action="<?php echo $Config['WebsitePath']; ?>/settings">
					<div class="input-group">
						<input type="hidden" name="Action" value="UpdateUserInfo" />
						<?php echo $Lang['User_Sex']; ?>
						<select name="UserSex">
							<option value="<?php echo $CurUserInfo['UserSex']; ?>"><?php echo $Lang['Do_Not_Modify']; ?></option>
							<option value="0"><?php echo $Lang['Sex_Unknown']; ?></option>
							<option value="1"><?php echo $Lang['Sex_Male']; ?></option>
							<option value="2"><?php echo $Lang['Sex_Female']; ?></option>
						</select>

						<input type="text" placeholder="<?php echo $Lang['Email']; ?>" name="UserMail" value="<?php echo $CurUserInfo['UserMail']; ?>" />
						<br /><?php echo $Lang['Ensure_That_Email_Is_Correct']; ?>
						<input type="text" placeholder="<?php echo $Lang['Homepage']; ?>" name="UserHomepage" value="<?php echo $CurUserInfo['UserHomepage']; ?>" />
						<textarea class="w300 h160" placeholder="<?php echo $Lang['Introduction']; ?>"><?php echo $CurUserInfo['UserIntro']; ?></textarea>
						<input type="submit" value="<?php echo $Lang['Save_Settings']; ?>" name="submit" class="button" />
					</div>
				</form>




		<div class="formGroupHead"><?php echo $Lang['Security_Settings']; ?></div>
<?php
if($ChangePasswordMessage){
?>
			<script type="text/javascript">CarbonAlert("<?php echo $ChangePasswordMessage; ?>");</script>
<?php
}
?>
			<form method="post" action="<?php echo $Config['WebsitePath']; ?>/settings">
				<div class="input-group">
					<input type="hidden" name="Action" value="ChangePassword" />
					<input type="password" placeholder="<?php echo $Lang['Current_Password']; ?>" name="OriginalPassword" value="" />
					<input type="password" placeholder="<?php echo $Lang['New_Password']; ?>" name="NewPassword" value="" />
					<input type="password" placeholder="<?php echo $Lang['Confirm_New_Password']; ?>" name="NewPassword2" value="" />
					<input type="submit" value="<?php echo $Lang['Change_Password']; ?>" name="submit" class="button" />
				</div>
			</form>
<?php
if(!$IsAjax){
?>
	</div>
<nav>
	<ul class="list">
		<?php include($TemplatePath.'sider.php'); ?>
	</ul>
</nav>
<?php } ?>