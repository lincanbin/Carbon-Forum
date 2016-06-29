<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if($Message){
?>
<script type="text/javascript">CarbonAlert("<?php echo $Message; ?>");</script>
<?php
}
?>
<br />
<form action="" method="post">
	<div class="input-group">
		<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
		<input type="text" name="UserName" id="UserName" placeholder="<?php echo $Lang['UserName']; ?>" value="<?php echo $OauthObject->NickName; ?>" />
		<br />
		<p>
			<input type="submit" class="button" value="<?php echo $Lang['Sign_Up']; ?>" name="submit" style="float:right;" />
		</p>
	</div>
</form>