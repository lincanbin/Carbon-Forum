<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<!-- this is the header div at the top -->
<div id="header">
	<!--a href="javascript:$.ui.toggleLeftSideMenu()" class="button" style="float:left">Toggle Nav</a-->
</div>
<div id="content">
	<!-- here is where you can add your panels -->
<?php } ?>
	<div data-title="<?php echo $PageTitle; ?>" id="Home<?php echo $Page; ?>" class="panel" selected="true">
		<ul class="list topic-list">
		<?php
		if($Page>1){
		?>
			<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/page/<?php echo ($Page-1); ?>" data-transition="slide" data-persist-ajax="true">上一页</a></li>
		<?php
		}
		foreach ($TopicsArray as $Topic) {
		?>
			<li>
				<div class="avatar">
					<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $Topic['UserName'] ?>" target="_blank">
							<?php echo GetAvatar($Topic['UserID'], $Topic['UserName'], 'middle'); ?>
					</a>
				</div>
				<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>" data-transition="slide" data-persist-ajax="true">
					<div class="content">
					<h2><?php echo $Topic['Topic']; ?></h2>
					<p><?php echo FormatTime($Topic['LastTime']); ?>&nbsp;&nbsp;<?php echo $Topic['LastName']; ?>
					</p>
					<?php if($Topic['Replies']){ ?>
					<span class="aside">
						<?php echo $Topic['Replies']; ?>
					</span>
					<?php } ?>
					</div>
				</a>
				<div class="c"></div>
			</li>
		<?php } 
		if($Page<$TotalPage){
		?>
			<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/page/<?php echo ($Page+1); ?>" data-transition="slide" data-persist-ajax="true">下一页</a></li>
		<?php } ?>
		</ul>
	</div>
<?php
if(!$IsAjax){
?>
</div>
<!-- bottom navbar. Add additional tabs here -->
<div id="navbar" style="height:0;"></div>
<!-- this is the default left side nav menu.  If you do not want any, do not include these -->
<nav>
	<!--header class="header"><h1>Left Menu</h1></header-->
	<ul class="list">
		<?php include($TemplatePath.'sider.php'); ?>
		<li class="divider"><?php echo $Lang['Website_Statistics']; ?></li>
		<li><?php echo $Lang['Topics_Number']; ?>：<?php echo $Config['NumTopics']; ?></li>
		<li><?php echo $Lang['Posts_Number']; ?>：<?php echo $Config['NumPosts']; ?></li>
		<li><?php echo $Lang['Tags_Number']; ?>：<?php echo $Config['NumTags']; ?></li>
		<li><?php echo $Lang['Users_Number']; ?>：<?php echo $Config['NumUsers']; ?></li>
	</ul>
</nav>
<?php } ?>