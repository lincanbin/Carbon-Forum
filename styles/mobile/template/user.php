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
<!-- main-content start -->
<div data-title="<?php echo $PageTitle; ?>" id="User<?php echo $UserInfo['UserName']; ?>" class="panel" selected="true">
<!-- User Infomation start -->
<div class="card user-card-header-pic">
	<div style="color:#FFFFFF;background-image:url(<?php echo $Config['WebsitePath'] . '/upload/avatar/large/' . $UserInfo['ID'] . '.png'; ?>)" valign="bottom" class="card-header color-white no-border"><?php echo $UserInfo['UserName']; ?></div>
	<div class="card-content">
		<div class="card-content-inner">
			<p class="color-gray"><?php echo $Lang['Registered_In']; ?>：<?php echo FormatTime($UserInfo['UserRegTime']); ?></p>
			<p><?php echo $Lang['UserName']; ?>：<strong><?php echo $UserInfo['UserName']; ?></strong></p>
			<p><?php echo $Lang['Topics_Number']; ?>： <?php echo $UserInfo['Topics']; ?>  &nbsp;&nbsp;&nbsp; <?php echo $Lang['Posts_Number']; ?>： <?php echo $UserInfo['Replies']; ?></p>
			<p><?php echo $Lang['Homepage']; ?>： <a href="<?php echo $UserInfo['UserHomepage']; ?>" target="_blank" rel="nofollow"><?php echo $UserInfo['UserHomepage']; ?></a></p>
			<p><?php echo $Lang['Introduction']; ?>： <br/> <?php echo $UserInfo['UserIntro']; ?></p>
		</div>
	</div>
	<div class="card-footer">
<?php
if($CurUserID){
?>
	<a href="###" onclick="javascript:Manage(<?php echo $UserInfo['ID']; ?>, 4, 3, false, this);" class="link"><?php echo $IsFavorite?$Lang['Unfollow']:$Lang['Follow']; ?></a>
<?php
	if($CurUserRole>=4){
?>
	<a href="###" onclick="javascript:Manage(<?php echo $UserInfo['ID']; ?>, 3, 'Block', true, this);" class="link"><?php echo $UserInfo['UserAccountStatus']?$Lang['Block_User']:$Lang['Unblock_User']; ?></a>
<?php
	}
}
?>
	</div>
</div>
<!-- User Infomation end -->
<!-- posts list start -->
<div class="content-block-title"><?php echo $Lang['Last_Activity_In']; ?> <?php echo FormatTime($UserInfo['LastPostTime']); ?></div>
<?php
foreach($PostsArray as $key => $post) {
?>
<div class="card">
	<div class="card-header">
		<!--<?php echo FormatTime($post['PostTime']); ?>-->
		<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $post['TopicID']; ?>" data-transition="slide" data-persist-ajax="true"><?php echo $post['Subject'];?></a>
	</div>
	<div class="card-content">
		<div class="card-content-inner">
			<?php echo strip_tags(mb_substr($post['Content'], 0, 300, 'utf-8'),'<p><br>'); ?>
		</div>
	</div>
	<div class="card-footer"><?php echo FormatTime($post['PostTime']); ?></div>
</div>
<?php
}
?>
<!-- posts list end -->
</div>
<!-- main-content end -->

<?php
if(!$IsAjax){
?>
</div>
<!-- main-sider start -->
<!-- this is the default left side nav menu.  If you do not want any, do not include these -->
<nav>
	<!--header class="header"><h1>Left Menu</h1></header-->
	<ul class="list">
		<?php include($TemplatePath.'sider.php'); ?>
	</ul>
</nav>
<!-- main-sider end -->
<?php } ?>
