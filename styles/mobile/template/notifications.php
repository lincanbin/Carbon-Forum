<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<div id="header">

</div>
<div id="content">
<?php } ?>
<!-- main-content start -->
	<div data-title="<?php echo $PageTitle; ?>" id="Notifications" class="panel" selected="true">
		<div>
			<!-- posts list start -->
			<?php
			foreach($ReplyArray as $key => $post)
			{
			?>
				<div class="comment-item">
					<div class="user-comment-data">
						<div class="comment-content">
						<h2 class="grey"><a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $post['UserName']; ?>" target="_blank"><?php echo $post['UserName'];?></a>&nbsp;&nbsp;<?php echo $Lang['Replied_To_Topic']; ?>&nbsp;›&nbsp;<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $post['TopicID']; ?>" target="_blank"><?php echo $post['Subject'];?></a></h2>
						<?php echo strip_tags(mb_substr($post['Content'], 0, 512, 'utf-8'),'<p><br><a>'); ?>
						</div>
						
						<div class="comment-data-date">
							<div class="float-right">
				&laquo;&nbsp;&nbsp;<?php echo FormatTime($post['PostTime']); ?></div>
							<div class="c"></div>
						</div>
						<div class="c"></div>
					</div>
					<div class="c"></div>
				</div>
			<?php
			}
			?>
			<!-- posts list end -->
		</div>
		<div>
			<!-- posts list start -->
			<?php
			foreach($MentionArray as $key => $post)
			{
			?>
				<div class="comment-item">
					<div class="user-comment-data">
						<div class="comment-content">
						<h2 class="grey"><a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $post['UserName']; ?>" target="_blank"><?php echo $post['UserName'];?></a>&nbsp;&nbsp;<?php echo $Lang['Mentioned_Me']; ?>&nbsp;›&nbsp;<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $post['TopicID']; ?>" target="_blank"><?php echo $post['Subject'];?></a></h2>
						<?php echo strip_tags(mb_substr($post['Content'], 0, 512, 'utf-8'),'<p><br><a>'); ?>
						</div>
						
						<div class="comment-data-date">
							<div class="float-right">
				&laquo;&nbsp;&nbsp;<?php echo FormatTime($post['PostTime']); ?></div>
							<div class="c"></div>
						</div>
						<div class="c"></div>
					</div>
					<div class="c"></div>
				</div>
			<?php
			}
			?>
			<!-- posts list end -->
		</div>
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