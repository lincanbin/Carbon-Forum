<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<div id="header">
	<a id="menubadge" onclick="JavaScript:af.ui.toggleSideMenu()" class="menuButton"></a>
</div>
<div id="content">
<?php } ?>
	<div data-title="<?php echo $Lang['My_Following_Tags']; ?>" id="Following-Tags<?php echo $Page; ?>" class="panel" selected="true">
		<h2 class="expanded" onclick="showHide(this,'TagsFollowing<?php echo $Page; ?>');"><?php echo $Lang['My_Following_Tags']; ?></h2>
		<p id="TagsFollowing<?php echo $Page; ?>">
<?php
foreach ($TagsFollowing as $Tag) {
?>
			<a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag['Title']); ?>" class="button"><?php echo $Tag['Title']; ?></a>
<?php
}
?>
		</p>
		<ul class="list topic-list">
<?php
if($Page>1){
?>
			<li class="pagination"><a href="JavaScript:$.ui.loadContent('<?php echo $Config['WebsitePath']; ?>/tags/following/page/<?php echo ($Page-1); ?>',false,false,'slide');"><?php echo $Lang['Page_Previous']; ?></a></li>
<?php
}
foreach ($TopicsArray as $Topic) {
?>
			<li>
				<div class="avatar">
					<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $Topic['UserName'] ?>" data-transition="slide" data-persist-ajax="true">
							<?php echo GetAvatar($Topic['UserID'], $Topic['UserName'], 'middle'); ?>
					</a>
				</div>
				<div class="content">
				<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>" data-transition="slide" data-persist-ajax="true">
					<h2><?php echo $Topic['Topic']; ?></h2>
				</a>
				<p><?php echo FormatTime($Topic['LastTime']); ?>&nbsp;&nbsp;<?php echo $Topic['LastName']; ?>
				</p>
<?php
if($Topic['Replies']){
?>
				<span class="aside">
					<?php echo $Topic['Replies']; ?>
				</span>
<?php
}
?>
				</div>
				
				<div class="c"></div>
			</li>
<?php
} 
?>
			<li class="pagination"><a href="JavaScript:$.ui.loadContent('<?php echo $Config['WebsitePath']; ?>/tags/following/page/<?php echo ($Page+1); ?>',false,false,'slide');"><?php echo $Lang['Page_Next']; ?></a></li>

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
<?php
}
?>