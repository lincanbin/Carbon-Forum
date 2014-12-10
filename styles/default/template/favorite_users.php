<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/">
			<?php echo $Config['SiteName']; ?>
		</a>
		&raquo; <?php echo $Lang['My_Following_Users']; ?>
	</div>
	<div class="main-box home-box-list">
		<?php
		foreach($PostsArray as $key => $Post)
		{
		?>
			<div class="comment-item">
				<div class="comment-avatar">
					<a href="<?php echo $Config['WebsitePath'].'/u/'.$Post['UserName']; ?>">
					<?php echo GetAvatar($Post['UserID'], $Post['UserName'], 'middle'); ?>
					</a>
				</div>
				<div class="comment-content">
					<span class="user-activity-title"><a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($Post['UserName']); ?>" target="_blank"><?php echo $Post['UserName']; ?></a><?php echo $Post['IsTopic']?$Lang['Created_Topic']:$Lang['Replied_Topic'];?>&nbsp;›&nbsp;<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Post['TopicID']; ?>" target="_blank"><?php echo $Post['Subject'];?></a></span>
					<?php echo strip_tags(mb_substr($Post['Content'], 0, 200, 'utf-8'),'<p><br><a>'); ?>
				</div>
					
				<div class="comment-data-date">
					<div class="float-right">
						&laquo;&nbsp;&nbsp;<?php echo FormatTime($Post['PostTime']); ?>
					</div>
					<div class="c"></div>
				</div>
					<div class="c"></div>
			</div>
		<?php
		}
		?>
		<div class="pagination">
			<?php PaginationSimplified('/users/following/page/', $Page, empty($PostsArray)); ?>
			<div class="c"></div>
		</div>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
	<div class="sider-box">
		<div class="sider-box-title"><?php echo $Lang['My_Following_Users']; ?></div>
		<div class="sider-box-content btn">
			<?php foreach ($UsersFollowing as $User) {?>
			<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($User['Title']); ?>" target="_blank"><?php echo GetAvatar($User['FavoriteID'], $User['Title'], 'small'); ?>&nbsp;&nbsp;<?php echo $User['Title']; ?></a>
			<?php } ?>
		</div>
	</div>
	<?php
	include($TemplatePath.'sider.php');
	?>
</div>
<!-- main-sider end -->