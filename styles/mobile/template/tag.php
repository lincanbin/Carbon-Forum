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
	<div data-title="<?php echo $PageTitle; ?>" id="Tag<?php echo $TagInfo['ID'].'-'.$Page; ?>" class="panel" selected="true">
		<h2 class="expanded" onclick="showHide(this,'TagDescription<?php echo $TagInfo['ID']; ?>');"><?php echo $TagInfo['Name']; ?></h2>
		<p id="TagDescription<?php echo $TagInfo['ID']; ?>">
<?php
if($CurUserID){
?>
		<a href="#" class="button" onclick="javascript:Manage(<?php echo $TagInfo['ID']; ?>, 4, 2, false, this);"><?php echo $IsFavorite?$Lang['Unfollow']:$Lang['Follow']; ?></a>
<?php
}
echo $TagInfo['Description'];
?>
		</p>
		<ul class="list topic-list">
<?php
if($Page>1){
?>
			<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo strtolower(urlencode($TagInfo['Name'])).'/page/'.($Page-1); ?>" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Page_Previous']; ?></a></li>

<?php
}
?>
<!-- main-content start -->
		<?php
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
				<?php if($Topic['Replies']){ ?>
				<span class="aside">
					<?php echo $Topic['Replies']; ?>
				</span>
				<?php } ?>
				</div>
				
				<div class="c"></div>
			</li>
<?php
} 
if($Page<$TotalPage){
?>
			<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo strtolower(urlencode($TagInfo['Name'])).'/page/'.($Page+1); ?>" data-transition="slide" data-persist-ajax="false"><?php echo $Lang['Page_Next']; ?></a></li>
<?php } ?>
		</ul>
	</div>
<?php
if(!$IsAjax){
?>
</div>
<!-- this is the default left side nav menu.  If you do not want any, do not include these -->
<nav>
	<!--header class="header"><h1>Left Menu</h1></header-->
	<ul class="list">
		<?php include($TemplatePath.'sider.php'); ?>
		<li class="divider"><?php echo $Lang['Tag']; ?>：<?php echo $TagName; ?></li>
		<li><?php echo $TagInfo['Followers']; ?><?php echo $Lang['Followers']; ?></li>
		<li><?php echo $TagInfo['TotalPosts']; ?><?php echo $Lang['Topics']; ?></li>
		<li><?php echo $Lang['Created_In']; ?><?php echo FormatTime($TagInfo['DateCreated']); ?></li>
		<li><?php echo $Lang['Last_Updated_In']; ?><?php echo FormatTime($TagInfo['MostRecentPostTime']); ?></li>

	</ul>
</nav>
<?php } ?>