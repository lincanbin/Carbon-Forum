<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<script type="text/javascript">
loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/default/account.function.js?version=<?php echo CARBON_FORUM_VERSION; ?>",function() {});
</script>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/"><?php echo $Config['SiteName']; ?></a> &raquo; <?php echo $Lang['Set_Your_Username']; ?>
	</div>
	<div class="main-box">
		<?php if($Message){ ?>
		<p class="red fs14" style="margin-left:60px;">
		› <?php echo $Message; ?> <br/></p>
		<?php } ?>
		<form action="" method="post">
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
				<tbody>
					<tr>
						<td width="180" align="right"><?php echo $Lang['UserName']; ?></td>
						<td width="auto" align="left"><input type="text" name="UserName" id="UserName" value="<?php echo $OauthObject->NickName; ?>" class="sl w200" onblur="CheckUserName()" /></td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left">
							<input type="submit" value="<?php echo $Lang['Sign_Up']; ?>" name="submit" class="textbtn" />
						</td>
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