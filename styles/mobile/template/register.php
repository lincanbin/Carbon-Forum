<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if($Message){
?>
<script type="text/javascript">CarbonAlert("<?php echo $Message; ?>");</script>
<?php
}
?>
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