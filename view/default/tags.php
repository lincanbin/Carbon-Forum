<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/">
			<?php echo $Config['SiteName']; ?>
		</a> &raquo; <?php echo $Lang['Tag']; ?>
	</div>
	<div class="main-box home-box-list">
	
<?php
foreach ($TagsArray as $Tag) {
?>
		<div class="tags-list-detail">
			<div>
				<div class="item-avatar">
					<a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag['Name']); ?>">
						<?php echo GetTagIcon($Tag['ID'], $Tag['Icon'], $Tag['Name'], 'middle'); ?>
					</a>
				</div>
				<div class="tag-item-content grey">
					<h2>
						<a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag['Name']); ?>">
							<?php echo $Tag['Name']; ?>
						</a>
					</h2>
<?php
if($CurUserID){
?>				<div class="btn float-right">
					<a href="###" onclick="javascript:Manage(<?php echo $Tag['ID']; ?>, 4, 2, false, this);">
						<?php echo isset($IsFavoriteArray[$Tag['ID']])?$Lang['Unfollow']:$Lang['Follow']; ?>
					</a>
				</div>
<?php } ?>
				<div class="c"></div>
				&nbsp;&nbsp;
				<?php echo $Tag['TotalPosts']; ?><?php echo $Lang['Topics']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo $Tag['Followers']; ?><?php echo $Lang['Followers']; ?>
				</div>
				<div class="c"></div>
			</div>
			<p class="fs14"><?php echo ($Tag['Description']? mb_strlen($Tag['Description']) > 60 ? mb_substr($Tag['Description'], 0, 60, 'utf-8').'……' : $Tag['Description'] : '' ); ?></p>
		</div>
<?php
}
?>
		<div class="c"></div>
		<div class="pagination">
<?php
Pagination("/tags/page/",$Page,$TotalPage);
?>
			<div class="c"></div>
		</div>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
<?php
include($TemplatePath.'sider.php');
?>
</div>
<!-- main-sider end -->