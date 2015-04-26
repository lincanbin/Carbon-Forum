<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<!-- this is the header div at the top -->
<div id="header">
	<a id="menubadge" onclick="JavaScript:af.ui.toggleSideMenu()" class="menuButton"></a>
</div>
<div id="content">
<?php } ?>
	<div data-title="<?php echo $PageTitle; ?>" id="Favorites-<?php echo $Page; ?>" class="panel" selected="true">
		<ul class="list">
<?php
if($Page>1){
?>
	
			<li><a href="JavaScript:$.ui.loadContent('<?php echo $Config['WebsitePath']; ?>/favorites/page/<?php echo ($Page-1); ?>',false,false,'slide');"><?php echo $Lang['Page_Previous']; ?></a></li>
<?php
}
foreach ($TopicsArray as $Topic) {
?>
			<li>
				<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['FavoriteID']; ?>" data-transition="slide" data-persist-ajax="true">
					<?php echo $Topic['Title']; ?>
				</a>
			</li>
							
<?php
}
if($Page<$TotalPage){
?>
			<li><a href="JavaScript:$.ui.loadContent('<?php echo $Config['WebsitePath']; ?>/favorites/page/<?php echo ($Page+1); ?>',false,false,'slide');"><?php echo $Lang['Page_Next']; ?></a></li>
<?php } ?>
		</ul>
	</div>
<?php
if(!$IsAjax){
?>
	</div>
<nav>
	<ul class="list">
		<?php include($TemplatePath.'sider.php'); ?>
	</ul>
</nav>
<?php } ?>