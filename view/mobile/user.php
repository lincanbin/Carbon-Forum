<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<div class="card user-card-header-pic">
	<div style="color:#FFFFFF;background-image:url(<?php echo $Config['WebsitePath'] . '/upload/avatar/large/' . $UserInfo['ID'] . '.png'; ?>)" valign="bottom" class="card-header color-white no-border"><?php echo $UserInfo['UserName']; ?></div>
	<div class="card-content">
		<div class="card-content-inner">
			<p class="color-gray"><?php echo $Lang['Registered_In']; ?>：<?php echo FormatTime($UserInfo['UserRegTime']); ?></p>
			<p><?php echo $Lang['UserName']; ?>：<strong><?php echo $UserInfo['UserName']; ?></strong></p>
			<p>
				<?php echo $Lang['Topics_Number']; ?>：
				<a data-transition="slide" href="<?php echo $Config['WebsitePath']; ?>/search/<?php echo urlencode('user:' . $UserInfo['UserName']); ?>">
					<?php echo $UserInfo['Topics']; ?>
				</a>
				&nbsp;&nbsp;&nbsp;
				<?php echo $Lang['Posts_Number']; ?>：
				<a data-transition="slide" href="<?php echo $Config['WebsitePath']; ?>/search/<?php echo urlencode('user:' . $UserInfo['UserName'] . ' post:true'); ?>">
					<?php echo $UserInfo['Replies']; ?>
				</a>
			</p>
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
foreach($PostsArray as $Post) {
?>
<div class="card">
	<div class="card-header">
		<!--<?php echo FormatTime($Post['PostTime']); ?>-->
		<a href="<?php echo $Config['WebsitePath']; ?>/goto/<?php echo $Post['TopicID']; ?>-<?php echo $Post['ID']; ?>" data-transition="slide"><?php echo $Post['Subject'];?></a>
	</div>
	<div class="card-content">
		<div class="card-content-inner">
			<?php echo strip_tags(mb_substr($Post['Content'], 0, 300, 'utf-8'),'<p><br>'); ?>
		</div>
	</div>
	<div class="card-footer"><?php echo FormatTime($Post['PostTime']); ?></div>
</div>
<?php
}
?>