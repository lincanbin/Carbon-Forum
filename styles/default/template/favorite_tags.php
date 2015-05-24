<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<?php echo $Lang['My_Following_Tags']; ?>
	</div>
	<div class="main-box home-box-list">
		<?php
		foreach ($TopicsArray as $Topic) {
		?>
		<div class="post-list">
			<div class="item-avatar">
				<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $Topic['UserName'] ?>" target="_blank">
						<?php echo GetAvatar($Topic['UserID'], $Topic['UserName'], 'middle'); ?>
					</a>
			</div>
			<div class="item-content">
				<h2>
					<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>" target="_blank"><?php echo $Topic['Topic']; ?></a>
				</h2>
				<span class="item-date">
						<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $Topic['UserName'] ?>"><?php echo $Topic['UserName']; ?></a>&nbsp;&nbsp;•&nbsp;&nbsp;
						<?php echo FormatTime($Topic['LastTime']); 
							if($Topic['Replies']) {
						?>&nbsp;&nbsp;•&nbsp;&nbsp;<?php echo $Lang['Last_Reply_From']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $Topic['LastName'] ?>"><?php echo $Topic['LastName']; ?></a><?php } ?>
					</span>
					
					<span class="item-tags">
						<?php if($Topic['Tags']) { foreach (explode("|", $Topic['Tags']) as $Tag) { ?>
							<a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag); ?>" target="_blank"><?php echo $Tag; ?></a>
						<?php } } ?>
					</span>
			</div>
		<?php if($Topic['Replies']){ ?>
			<div class="item-count">
				<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>"><?php echo $Topic['Replies']; ?></a>
			</div>
		<?php } ?>
			<div class="c"></div>
		</div>
		<?php
		}
		?>
		<div class="pagination">
			<?php PaginationSimplified('/tags/following/page/', $Page, empty($TopicsArray)); ?>
			<div class="c"></div>
		</div>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
	<div class="sider-box">
		<div class="sider-box-title"><?php echo $Lang['My_Following_Tags']; ?></div>
		<div class="sider-box-content btn">
			<?php foreach ($TagsFollowing as $Tag) {?>
			<a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag['Title']); ?>" target="_blank"><?php echo $Tag['Title']; ?></a>
			<?php } ?>
		</div>
	</div>
	<?php
	include($TemplatePath.'sider.php');
	?>
</div>
<!-- main-sider end -->