<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/"><?php echo $Config['SiteName']; ?></a> &raquo; <?php echo $UserInfo['UserName']; ?> 
	</div>
<!-- User Infomation start -->
<div class="main-box">
<div class="member-avatar btn"><?php echo GetAvatar($UserInfo['ID'], $UserInfo['UserName'], 'large'); ?>
<?php
if($CurUserID){
?>
	<p><a href="###" onclick="javascript:Manage(<?php echo $UserInfo['ID']; ?>, 4, 3, false, this);"><?php echo $IsFavorite?$Lang['Unfollow']:$Lang['Follow']; ?></a></p>
<?php
	if($CurUserRole>=4){
?>
	<div class="c"></div>
	<p><a href="###" onclick="javascript:Manage(<?php echo $UserInfo['ID']; ?>, 3, 'Block', true, this);"><?php echo $UserInfo['UserAccountStatus']?$Lang['Block_User']:$Lang['Unblock_User']; ?></a></p>
	<p><a href="###" onclick="javascript:Manage(<?php echo $UserInfo['ID']; ?>, 3, 'ResetAvatar', true, this);"><?php echo $Lang['Reset_Avatar']; ?></a></p>
<?php
	}
}
?>
</div>
<div class="member-detail">
<p><?php echo $Lang['UserName']; ?>：<strong><?php echo $UserInfo['UserName']; ?></strong></p>
<p><?php echo $Lang['Registered_In']; ?>：<?php echo FormatTime($UserInfo['UserRegTime']); ?></p>
<p><?php echo $Lang['Topics_Number']; ?>： <?php echo $UserInfo['Topics']; ?>  &nbsp;&nbsp;&nbsp; <?php echo $Lang['Posts_Number']; ?>： <?php echo $UserInfo['Replies']; ?></p>
<p><?php echo $Lang['Homepage']; ?>： <a href="<?php echo $UserInfo['UserHomepage']; ?>" target="_blank" rel="nofollow"><?php echo $UserInfo['UserHomepage']; ?></a></p>
<p><?php echo $Lang['Introduction']; ?>： <br/> <?php echo $UserInfo['UserIntro']; ?></p>
</div>
<div class="c"></div>
</div>
<!-- User Infomation end -->
<!-- posts list start -->
<div class="title">
	<?php echo $Lang['Last_Activity_In']; ?> <?php echo FormatTime($UserInfo['LastPostTime']); ?>
</div>
<div class="main-box home-box-list">
<?php
foreach($PostsArray as $key => $post)
{
?>
	<div class="comment-item">
		<div class="user-comment-data">
			<div class="comment-content">
			<span class="user-activity-title"><?php echo $post['IsTopic']?$Lang['Created_Topic']:$Lang['Replied_To_Topic']; ?>&nbsp;›&nbsp;<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $post['TopicID']; ?>" target="_blank"><?php echo $post['Subject'];?></a></span>
			<?php echo strip_tags(mb_substr($post['Content'], 0, 300, 'utf-8'),'<p><br>'); ?>
			</div>
			
			<div class="comment-data-date">
				<div class="float-right">
	&laquo;&nbsp;&nbsp;<?php echo FormatTime($post['PostTime']); ?></div>
				<div class="c"></div>
			</div>
			<div class="c"></div>
		</div>
		<div class="c"></div>
	</div>
<?php
}
?>
</div>
<!-- posts list end -->
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
	<?php include($TemplatePath.'sider.php'); ?>
</div>
<!-- main-sider end -->