<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<script>
$(document).ready(function(){
	$("#settings").easyResponsiveTabs({
		type: 'default', //Types: default, vertical, accordion           
		width: 'auto', //auto or any custom width
		fit: true,   // 100% fits in a container
		closed: false, // Close the panels on start, the options 'accordion' and 'tabs' keep them closed in there respective view types
		activate: function() {}  // Callback function, gets called if tab is switched
	});
});
</script>
<div class="main-content">
	<div id="settings" class="tab-container">
		<ul class='resp-tabs-list'>
			<li><?php echo $Lang['Avatar_Settings']; ?></li>
			<li><?php echo $Lang['Profile_Settings']; ?></li>
			<li><?php echo $Lang['Security_Settings']; ?></li>
		</ul>
		<div class="resp-tabs-container main-box">
			<div>
				<div>
					<div class="float-left w300 img-center">
						<img id="CurAvatar" src="<?php echo $Config['WebsitePath']; ?>/upload/avatar/large/<?php echo $CurUserID; ?>.png?cache=<?php echo $TimeStamp; ?>" alt="<?php echo $CurUserName; ?>" />
						<p><a href="###" onclick="javascript:Manage(<?php echo $CurUserID; ?>, 3, 'ResetAvatar', true, this);"><?php echo $Lang['Reset_Avatar']; ?></a></p>
					</div>
					
					<div class="float-right w300">
						<p class="grey">
							<?php echo $Lang['You_Can_Replace_Your_Avatar_Here']; ?>
						</p>
						<hr /><br />
						<form method="post" enctype="multipart/form-data" action="<?php echo $Config['WebsitePath']; ?>/settings#settings1">
							<input type="hidden" name="Action" value="UploadAvatar">
								<span class="red"><?php echo $UploadAvatarMessage; ?></span>
								<br />
								<input type="file" id="Avatar" name="Avatar" accept="image/*" />
								<br />
							<hr /><br />
							<div class="grey"><?php echo $Lang['Max_Avatar_Size_Limit']; ?></div>
							<br />
							<input type="submit" value="<?php echo $Lang['Upload_Avatar']; ?>" name="submit" class="textbtn" />
						</form>
					</div>
					<div class="c"></div>
				</div>
			</div>
			<div>
				<p class="red text-center"><?php echo $UpdateUserInfoMessage; ?></p>
				<form method="post" action="<?php echo $Config['WebsitePath']; ?>/settings#settings2">
				<input type="hidden" name="Action" value="UpdateUserInfo" />
				<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
					<tbody>
					<tr>
						<td width="180" align="right"><?php echo $Lang['UserName']; ?></td>
						<td width="auto" align="left"><?php echo $CurUserName; ?></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['User_Sex']; ?></td>
						<td width="auto" align="left">
							<select name="UserSex">
								<option value="<?php echo $CurUserInfo['UserSex']; ?>"><?php echo $Lang['Do_Not_Modify']; ?></option>
								<option value="0"><?php echo $Lang['Sex_Unknown']; ?></option>
								<option value="1"><?php echo $Lang['Sex_Male']; ?></option>
								<option value="2"><?php echo $Lang['Sex_Female']; ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Email']; ?></td>
						<td width="auto" align="left"><input type="text" class="w300" name="UserMail" value="<?php echo $CurUserInfo['UserMail']; ?>" /> <br /><?php echo $Lang['Ensure_That_Email_Is_Correct']; ?></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Homepage']; ?></td>
						<td width="auto" align="left"><input type="text" class="w300" name="UserHomepage" value="<?php echo $CurUserInfo['UserHomepage']; ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Introduction']; ?></td>
						<td width="auto" align="left"><textarea class="w300 h160" name="UserIntro"><?php echo $CurUserInfo['UserIntro']; ?></textarea></td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="<?php echo $Lang['Save_Settings']; ?>" name="submit" class="textbtn" /></td>
					</tr>
					
				</tbody></table>
				</form>
			</div>
			<div>
				<p class="red text-center"><?php echo $ChangePasswordMessage; ?></p>
				<form method="post" action="<?php echo $Config['WebsitePath']; ?>/settings#settings3">
				<input type="hidden" name="Action" value="ChangePassword" />
				<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
					<tbody>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Current_Password']; ?></td>
						<td width="auto" align="left"><input type="password" class="w300" name="OriginalPassword" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['New_Password']; ?></td>
						<td width="auto" align="left"><input type="password" class="w300" name="NewPassword" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Confirm_New_Password']; ?></td>
						<td width="auto" align="left"><input type="password" class="w300" name="NewPassword2" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="<?php echo $Lang['Change_Password']; ?>" name="submit" class="textbtn" /></td>
					</tr>
					
				</tbody></table>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
	<?php include($TemplatePath.'sider.php'); ?>
</div>
<!-- main-sider end -->