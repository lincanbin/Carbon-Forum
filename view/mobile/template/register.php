<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if($Error){
?>
<script type="text/javascript">CarbonAlert("<?php echo $Error; ?>");</script>
<?php
}
?>
<br />
<form action="?" method="post" onsubmit="JavaScript:this.Password.value=md5(this.Password.value);">
	<div class="input-group">
		<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
		<input type="text" placeholder="<?php echo $Lang['UserName']; ?>" name="UserName" value="<?php echo htmlspecialchars($UserName); ?>" />
		<input type="text" placeholder="<?php echo $Lang['Email']; ?>" name="Email" value="<?php echo htmlspecialchars($Email); ?>" />
		<input type="password" placeholder="<?php echo $Lang['Password']; ?>" name="Password" value="" />
		<input type="text" placeholder="<?php echo $Lang['Verification_Code']; ?>" name="VerifyCode" value="" style="width:66%;" /> 
		<img src="<?php echo $Config['WebsitePath']; ?>/seccode.php" align="middle" onclick="this.src+=''" style="cursor: pointer;" />
		<input type="submit" value="<?php echo $Lang['Sign_Up']; ?>" name="submit" class="button" style="float:right;" />
	</div>
</form>