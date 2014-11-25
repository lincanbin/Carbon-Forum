
<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/">
			<?php echo $Config['SiteName']; ?>
		</a>
		&raquo; <?php echo $Lang['My_Favorites']; ?>
	</div>
	<div class="main-box home-box-list">
		<?php
		foreach ($TopicsArray as $Topic) {
		?>
			<div class="post-list">
				<div class="item-content">
					<h2>
						<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['FavoriteID']; ?>" target="_blank">
							<?php echo $Topic['Title']; ?>
						</a>
					</h2>
					<span class="item-tags">
						<a href="###" onclick="javascript:Manage(<?php echo $Topic['FavoriteID']; ?>, 4, 1, false, this);"><?php echo $Lang['Unsubscribe']; ?></a>
					</span>
					<span class="item-date float-right">
						<?php echo $Lang['Collected_In'].FormatTime($Topic['DateCreated']); ?>
					</span>
				</div>
				<div class="c"></div>
			</div>
							
		<?php
		}
		?>
		<div class="pagination">
			<?php Pagination('/favorites/page/', $Page, $TotalPage); ?>
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