<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<div id="header">
	<a id="menubadge" onclick="JavaScript:af.ui.toggleSideMenu()" class="menuButton"></a>
</div>
<div id="content">
<?php } ?>
<!-- main-content start -->
	<div data-title="<?php echo $PageTitle; ?>" id="Login" class="panel" selected="true">
<?php if($Message){ ?>
		<script type="text/javascript">CarbonAlert("<?php echo $Message; ?>");</script>
<?php } ?>
		<br />
		<form action="?" method="post">
			<div class="input-group">
				<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
				<input type="text" placeholder="<?php echo $Lang['UserName']; ?>" name="UserName" value="<?php echo htmlspecialchars($UserName); ?>" />
				<input type="text" placeholder="<?php echo $Lang['Email']; ?>" name="Email" value="<?php echo htmlspecialchars($Email); ?>" />
				<input type="password" placeholder="<?php echo $Lang['Password']; ?>" name="Password" value="" />
				<input type="password" placeholder="<?php echo $Lang['Confirm_Password']; ?>" name="Password2" value="" />
				<input type="text" placeholder="<?php echo $Lang['Verification_Code']; ?>" name="VerifyCode" value="" style="width:66%;" /> 
				<img src="<?php echo $Config['WebsitePath']; ?>/seccode.php" align="middle" onclick="this.src+=''" style="cursor: pointer;" />
				<input type="submit" value="<?php echo $Lang['Sign_Up']; ?>" name="submit" class="button" style="float:right;" />
			</div>
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