<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<!-- this is the header div at the top -->
<div id="header">
	<a id="menubadge" onclick="JavaScript:af.ui.toggleSideMenu()" class="menuButton"></a>
</div>
<div id="content">
	<!-- here is where you can add your panels -->
<?php } ?>
	<div data-title="<?php echo $PageTitle; ?>" id="Topic<?php echo $ID.'-'.$Page; ?>" class="panel" selected="true">
<?php
if($Page>1){
?>
	<ul class="list topic-list">
		<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $ID.'-'.($Page-1); ?>" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Page_Previous']; ?></a></li>
	</ul>
<?php
}
if($Page==1){
?>
<div class="card">
	<div class="card-header"><?php echo $topic['Topic']; ?></div>
	<div class="card-content">
		<div class="card-content-inner">
			<p class="color-gray">By <a href="<?php echo $Config['WebsitePath'].'/u/'.$topic['UserName']; ?>"><?php echo $topic['UserName']; ?></a>
 at <?php echo FormatTime($topic['PostTime']); ?><br /><?php echo $topic['Favorites']; ?><?php echo $Lang['People_Collection']; ?> • <?php echo ($topic['Views']+1); ?><?php echo $Lang['People_Have_Seen']; ?>
			</p>
			<p><?php echo $PostsArray[0]['Content']; ?></p>
			<div class="button-grouped">
<?php
if($topic['Tags']){
	foreach (explode("|", $topic['Tags']) as $Tag) {
?>					<a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag); ?>" class="button"><?php echo $Tag; ?></a>
<?php
	}
}
?>
			</div>
		</div>
	</div>
	<div class="card-footer">
	<?php
if($CurUserRole>=4){
	if($topic['IsDel']==0){
	?>
		<a href="#" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'Delete', true, this);" class="link red"><?php echo $Lang['Delete']; ?></a>
<?php
	}else{
?>
		<a href="#" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'Recover', false, this);" class="link green"><?php echo $Lang['Recover']; ?></a>
		<a href="#" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'PermanentlyDelete', true, this);" class="link red"><?php echo $Lang['Permanently_Delete']; ?></a>
<?php
	}
?>
		<a href="#" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'Lock', true, this);" class="link"><?php echo $topic['IsLocked']?$Lang['Unlock']:$Lang['Lock']; ?></a>
		<a href="#" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'Sink', true, this);" class="link"><?php echo $Lang['Sink']; ?></a>
		<a href="#" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'Rise', true, this);" class="link"><?php echo $Lang['Rise']; ?></a>
<?php
}
if($CurUserID){
?>
		<a href="#" onclick="javascript:Manage(<?php echo $ID; ?>, 4, 1, false, this);" class="link"><?php echo $IsFavorite?$Lang['Unsubscribe']:$Lang['Collect']; ?></a>
<?php
}
?>
	</div>
</div>
<!-- post main content end -->
<?php
	unset($PostsArray[0]);
}
if($topic['Replies']!=0)
{
?>
<!-- comment list start -->
<div class="content-block-title">
	<?php echo $topic['Replies']; ?> <?php echo $Lang['Replies']; ?>  |  <?php echo $Lang['Last_Updated_In']; ?> <?php echo FormatTime($topic['LastTime']); ?>
</div>
<?php
foreach($PostsArray as $key => $post)
{
	$PostFloor = ($Page-1)*$Config['PostsPerPage']+$key;
?>
<div class="card carbonforum-card">
	<div class="card-header no-border">
		<div class="carbonforum-avatar">
			<a href="<?php echo $Config['WebsitePath'].'/u/'.$post['UserName']; ?>">
				<?php echo GetAvatar($post['UserID'], $post['UserName'], 'small'); ?>
			</a>
		</div>
		<div class="carbonforum-name"><?php echo $post['UserName'];?></div>
		<div class="carbonforum-date"><?php echo FormatTime($post['PostTime']); ?></div>
		<div class="carbonforum-floor">#<?php echo $PostFloor; ?></div>
	</div>
	<div class="card-content"><p><?php echo $post['Content']; ?></p></div>
	<div class="card-footer no-border">
<?php if($CurUserID){
	if($CurUserRole>=4){
?>
	<a href="#" onclick="javascript:Manage(<?php echo $post['ID']; ?>, 2, 'Delete', true, this);" class="link"><?php echo $Lang['Delete']; ?></a>
<?php
	}
?>
	<a href="#" title="<?php echo $Lang['Reply']; ?>" onclick="JavaScript:Reply('<?php echo $post['UserName'];?>', <?php echo $PostFloor; ?>, <?php echo $post['ID'];?>, '<?php echo $FormHash;?>', <?php echo $ID;?>);" class="link"><?php echo $Lang['Reply']; ?></a>
<?php } ?>
	</div>
</div>
<?php
}
?>
<!-- comment list end -->
<?php
}
?>
<ul class="list topic-list">
<?php
if(!$topic['IsLocked'] && !$CurUserInfo){
?>
	<li class="pagination"><?php echo $Lang['Requirements_For_Login']; ?></li>
<?php
}else if($topic['IsLocked']){
?>
	<li class="pagination"><?php echo $Lang['Topic_Has_Been_Locked']; ?></li>
<?php
}else{
?>
	<li class="pagination"><a href="#" onclick="JavaScript:Reply('<?php echo $topic['UserName'];?>', 1, 0, '<?php echo $FormHash;?>', <?php echo $ID; ?>);"><?php echo $Lang['Reply']; ?></a></li>
<?php
}
if($Page<$TotalPage){
?>
	
	<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $ID.'-'.($Page+1); ?>" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Page_Next']; ?></a></li>
<?php } ?>
</ul>
<?php
if(!$IsAjax){
?>
</div>
<!-- this is the default left side nav menu.  If you do not want any, do not include these -->
<nav>
	<!--header class="header"><h1>Left Menu</h1></header-->
	<ul class="list">
		<?php include($TemplatePath.'sider.php'); ?>
	</ul>
</nav>
<?php } ?>
<script type="text/javascript">
TopicParse();
</script>
