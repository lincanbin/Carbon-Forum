<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/"><?php echo $Config['SiteName']; ?></a> &raquo; <?php echo $Lang['Log_In']; ?>
	</div>
	<div class="main-box">
		<?php if($error){ ?>
		<p class="red fs12" style="margin-left:60px;">
		› <?php echo $error; ?> <br/></p>
		<?php } ?>
		<form action="?" method="post">
			<input type="hidden" value="<?php echo $ReturnUrl; ?>" name="ReturnUrl" />
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
				<tbody>
					<tr>
						<td width="180" align="right"><?php echo $Lang['UserName']; ?></td>
						<td width="auto" align="left"><input type="text" name="UserName" class="sl w200" value="<?php echo htmlspecialchars($UserName); ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Password']; ?></td>
						<td width="auto" align="left"><input type="password" name="Password" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Login_Expiration_Time']; ?></td>
						<td width="auto" align="left">
							<select name="Expires">
								<option value="365">365<?php echo $Lang['Days']; ?></option>
								<option value="30">30<?php echo $Lang['Days']; ?></option>
								<option value="7">7<?php echo $Lang['Days']; ?></option>
								<option value="1">1<?php echo $Lang['Days']; ?></option>
								<option value="0">0<?php echo $Lang['Days']; ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Verification_Code']; ?></td>
						<td width="auto" align="left">
							<input type="text" name="VerifyCode" class="sl w100" value="" /> <img src="<?php echo $Config['WebsitePath']; ?>/seccode.php" align="absmiddle" />
						</td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="<?php echo $Lang['Log_In']; ?>" name="submit" class="textbtn" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $Config['WebsitePath']; ?>/register"><?php echo $Lang['Sign_Up']; ?></a></td>
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