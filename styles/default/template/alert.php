<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title"><a href="<?php echo $Config['WebsitePath'];?>/"><?php echo $Config['SiteName'];?></a> &raquo; <?php echo $Lang['Error_Message']; ?></div>
	<div class="main-box">
		<p class="red fs12" style="margin-left:60px;">
		› <?php echo $error;?> <br/>
		</p>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
<?php include($TemplatePath.'sider.php'); ?>
</div>
<!-- main-sider end -->