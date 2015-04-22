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
	<div data-title="<?php echo $PageTitle; ?>" id="Notifications1" class="panel" selected="true">
		<div class="button-grouped flex tabbed">
			<a class="button pressed" href="<?php echo $Config['WebsitePath']; ?>/notifications#Notifications1"><?php echo $Lang['Notifications_Replied_To_Me']; ?></a>
			<a class="button" href="<?php echo $Config['WebsitePath']; ?>/notifications#Notifications2"><?php echo $Lang['Notifications_Mentioned_Me']; ?></a>
		</div>
		<!-- posts list start -->
		<?php
		foreach($ReplyArray as $key => $post)
		{
		?>
			<div class="card">
				<div class="card-header"><a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $post['TopicID']; ?>" data-transition="slide" data-persist-ajax="true"><?php echo $post['UserName'];?>&nbsp;&nbsp;<?php echo $Lang['Replied_To_Topic']; ?>&nbsp;›&nbsp;<?php echo $post['Subject'];?></a></div>
				<div class="card-content">
					<div class="card-content-inner"><?php echo strip_tags(mb_substr($post['Content'], 0, 512, 'utf-8'),'<p><br><a>'); ?></div>
				</div>
				<div class="card-footer"><?php echo FormatTime($post['PostTime']); ?></div>
			</div>
		<?php
		}
		?>
		<!-- posts list end -->
	</div>


	<div data-title="<?php echo $PageTitle; ?>" id="Notifications2" class="panel" selected="false">
		<div class="button-grouped flex tabbed">
			<a class="button" href="<?php echo $Config['WebsitePath']; ?>/notifications#Notifications1"><?php echo $Lang['Notifications_Replied_To_Me']; ?></a>
			<a class="button pressed" href="<?php echo $Config['WebsitePath']; ?>/notifications#Notifications2"><?php echo $Lang['Notifications_Mentioned_Me']; ?></a>
		</div>
		<!-- posts list start -->
		<?php
		foreach($MentionArray as $key => $post)
		{
		?>
			<div class="card">
				<div class="card-header"><a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $post['TopicID']; ?>" data-transition="slide" data-persist-ajax="true"><?php echo $post['UserName'];?>&nbsp;&nbsp;<?php echo $Lang['Mentioned_Me']; ?>&nbsp;›&nbsp;<?php echo $post['Subject'];?></a></div>
				<div class="card-content">
					<div class="card-content-inner"><?php echo strip_tags(mb_substr($post['Content'], 0, 512, 'utf-8'),'<p><br><a>'); ?></div>
				</div>
				<div class="card-footer"><?php echo FormatTime($post['PostTime']); ?></div>
			</div>
		<?php
		}
		?>
		<!-- posts list end -->
	</div>
	
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