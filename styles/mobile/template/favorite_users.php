<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<div id="header">
	<a id="menubadge" onclick="JavaScript:af.ui.toggleSideMenu()" class="menuButton"></a>
</div>
<div id="content">
<?php } ?>
	<div data-title="<?php echo $Lang['My_Following_Users']; ?>" id="Following-Users-<?php echo $Page; ?>" class="panel" selected="true">
		<h2 class="expanded" onclick="showHide(this,'UsersFollowing<?php echo $Page; ?>');"><?php echo $Lang['My_Following_Users']; ?></h2>
		<p id="UsersFollowing<?php echo $Page; ?>">
<?php
foreach ($UsersFollowing as $User){
?>
			<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($User['Title']); ?>" class="button"><?php echo GetAvatar($User['FavoriteID'], $User['Title'], 'small'); ?>&nbsp;&nbsp;<?php echo $User['Title']; ?></a>
<?php
}
?>
<?php
if($Page>1){
?>
	<ul class="list topic-list">
		<li class="pagination"><a href="JavaScript:$.ui.loadContent('<?php echo $Config['WebsitePath']; ?>/users/following/page/<?php echo ($Page-1); ?>',false,false,'slide');"><?php echo $Lang['Page_Previous']; ?></a></li>
	</ul>
<?php
}
foreach($PostsArray as $key => $Post) {
?>
<div class="card carbonforum-card">
	<div class="card-header">
		<div class="carbonforum-avatar">
			<a href="<?php echo $Config['WebsitePath'].'/u/'.$Post['UserName']; ?>">
				<?php echo GetAvatar($Post['UserID'], $Post['UserName'], 'small'); ?>
			</a>
		</div>
		<div class="carbonforum-name"><?php echo $Post['UserName'];?></div>
		<div class="carbonforum-date"><?php echo FormatTime($Post['PostTime']); ?></div>
	</div>
	<div class="card-header">
		<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Post['TopicID']; ?>"><?php echo $Post['IsTopic']?$Lang['Created_Topic']:$Lang['Replied_Topic'];?>&nbsp;<?php echo $Post['Subject'];?></a>
	</div>
	<div class="card-content"><p><?php echo strip_tags(mb_substr($Post['Content'], 0, 200, 'utf-8'),'<p><br><a>'); ?></p></div>
</div>
<?php
}
?>
			<ul class="list topic-list">
				<li class="pagination"><a href="JavaScript:$.ui.loadContent('<?php echo $Config['WebsitePath']; ?>/users/following/page/<?php echo ($Page+1); ?>',false,false,'slide');"><?php echo $Lang['Page_Next']; ?></a></li>
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