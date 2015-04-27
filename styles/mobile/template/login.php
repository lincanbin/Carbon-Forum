<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<div id="header">
	<a id="menubadge" onclick="JavaScript:af.ui.toggleSideMenu()" class="menuButton"></a>
</div>
<div id="content">
<?php } ?>
	<div data-title="<?php echo $PageTitle; ?>" id="Login" class="panel" selected="true">
<?php
if($error){
?>
		<script type="text/javascript">CarbonAlert("<?php echo $error; ?>");</script>
<?php
}
?>
		<br />
		<form action="?" method="post" onsubmit="JavaScript:this.Password.value=md5(this.Password.value);">
			<div class="input-group">
				<input type="hidden" value="<?php echo $ReturnUrl; ?>" name="ReturnUrl" />
				<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />

				<input type="text" name="UserName" id="UserName" placeholder="<?php echo $Lang['UserName']; ?>" value="<?php echo htmlspecialchars($UserName); ?>" />

				<input type="password" name="Password" id="Password" placeholder="<?php echo $Lang['Password']; ?>" value="" />
				<br style="clear:both">
				<p>
				<label for="Expires"><?php echo $Lang['Login_Expiration_Time']; ?></label>
				<select name="Expires" id="Expires" style="display:inline;">
					<option value="30">30<?php echo $Lang['Days']; ?></option>
					<option value="14">14<?php echo $Lang['Days']; ?></option>
					<option value="7">7<?php echo $Lang['Days']; ?></option>
					<option value="1">1<?php echo $Lang['Days']; ?></option>
					<option value="0">0<?php echo $Lang['Days']; ?></option>
				</select>
				</p>
				<input type="text" name="VerifyCode" id="VerifyCode" placeholder="<?php echo $Lang['Verification_Code']; ?>" onclick="document.getElementById('Verification_Code_Img').src='<?php echo $Config['WebsitePath']; ?>/seccode.php';" value="" placeholder="<?php echo $Lang['Verification_Code']; ?>" style="width:66%;"/>
				<img src="" id="Verification_Code_Img" style="cursor: pointer;" onclick="this.src+=''" style="width:33%;" align="middle" />
				<br style="clear:both">
				<a href="<?php echo $Config['WebsitePath']; ?>/register" class="button"><?php echo $Lang['Sign_Up']; ?></a>
				<input type="submit" class="button" value="<?php echo $Lang['Log_In']; ?>" name="submit" style="float:right;" />
				
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