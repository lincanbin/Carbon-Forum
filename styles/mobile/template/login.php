<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<div id="header">

</div>
<div id="content">
<?php } ?>
<!-- main-content start -->
	<div data-title="<?php echo $PageTitle; ?>" id="Login" class="panel" selected="true">
<?php
if($error){
?>
		<script type="text/javascript">CarbonAlert("<?php echo $error; ?>");</script>>
<?php
}
?>
		<form action="?" method="post" onsubmit="JavaScript:this.Password.value=md5(this.Password.value);">
			<input type="hidden" value="<?php echo $ReturnUrl; ?>" name="ReturnUrl" />
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
				<tbody>
					<tr>
						<td width="30%" align="right"><?php echo $Lang['UserName']; ?></td>
						<td width="auto" align="left"><input type="text" name="UserName" class="sl w200" value="<?php echo htmlspecialchars($UserName); ?>" /></td>
					</tr>
					<tr>
						<td width="30%" align="right"><?php echo $Lang['Password']; ?></td>
						<td width="auto" align="left"><input type="password" name="Password" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td width="30%" align="right"><?php echo $Lang['Login_Expiration_Time']; ?></td>
						<td width="auto" align="left">
							<select name="Expires">
								<option value="30">30<?php echo $Lang['Days']; ?></option>
								<option value="14">14<?php echo $Lang['Days']; ?></option>
								<option value="7">7<?php echo $Lang['Days']; ?></option>
								<option value="1">1<?php echo $Lang['Days']; ?></option>
								<option value="0">0<?php echo $Lang['Days']; ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="30%" align="right"><?php echo $Lang['Verification_Code']; ?></td>
						<td width="auto" align="left">
							<label><input type="text" name="VerifyCode" class="w100" onclick="document.getElementById('Verification_Code_Img').src='<?php echo $Config['WebsitePath']; ?>/seccode.php';" value="" placeholder="<?php echo $Lang['Verification_Code']; ?>" /></label> <img src="" id="Verification_Code_Img" style="cursor: pointer;" onclick="this.src+=''" align="absmiddle" />
						</td>
					</tr>
					<tr>
						<td width="30%" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="<?php echo $Lang['Log_In']; ?>" name="submit" class="textbtn" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $Config['WebsitePath']; ?>/register"><?php echo $Lang['Sign_Up']; ?></a></td>
					</tr>
				</tbody>
			</table>
		</form>
	<!-- main-content end -->
<?php
if(!$IsAjax){
?>
	</div>
<!-- this is the default left side nav menu.  If you do not want any, do not include these -->
<nav>
	<ul class="list">
		<?php include($TemplatePath.'sider.php'); ?>
	</ul>
</nav>
<?php } ?>