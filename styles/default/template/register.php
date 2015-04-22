<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/"><?php echo $Config['SiteName']; ?></a> &raquo; <?php echo $Lang['Sign_Up']; ?>
	</div>
	<div class="main-box">
		<?php if($Message){ ?>
		<p class="red fs14" style="margin-left:60px;">
		› <?php echo $Message; ?> <br/></p>
		<?php } ?>
		<form action="?" method="post">
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
				<tbody>
					<tr>
						<td width="180" align="right"><?php echo $Lang['UserName']; ?></td>
						<td width="auto" align="left"><input type="text" name="UserName" class="sl w200" value="<?php echo htmlspecialchars($UserName); ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Email']; ?></td>
						<td width="auto" align="left"><input type="text" name="Email" class="sl w200" value="<?php echo htmlspecialchars($Email); ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Password']; ?></td>
						<td width="auto" align="left"><input type="password" name="Password" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Confirm_Password']; ?></td>
						<td width="auto" align="left"><input type="password" name="Password2" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Verification_Code']; ?></td>
						<td width="auto" align="left">
							<label><input type="text" name="VerifyCode" class="w100" onfocus="document.getElementById('Verification_Code_Img').src='<?php echo $Config['WebsitePath']; ?>/seccode.php';document.getElementById('Verification_Code_Img').style.display='inline';" value="" placeholder="<?php echo $Lang['Verification_Code']; ?>" /></label> 
							<img src="" id="Verification_Code_Img" style="cursor: pointer;display:none;" onclick="this.src+=''" align="middle" />
						</td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="<?php echo $Lang['Sign_Up']; ?>" name="submit" class="textbtn" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $Config['WebsitePath']; ?>/login"><?php echo $Lang['Log_In']; ?></a></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
	<!-- main-content end -->
	<!-- main-sider start -->
	<div class="main-sider">
	<?php include($TemplatePath.'sider.php'); ?>
	</div>
	<!-- main-sider end -->