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
<div class="member-avatar"><?php echo GetAvatar($UserInfo['ID'], $UserInfo['UserName'], 'large'); ?></div>
<div class="member-detail">
<p>会员：<strong><?php echo $UserInfo['UserName']; ?></strong> (第<?php echo $UserInfo['ID']; ?>号会员，<?php echo FormatTime($UserInfo['UserRegTime']); ?>加入)
</p>
<p>主贴： <?php echo $UserInfo['Topics']; ?>  &nbsp;&nbsp;&nbsp; 回贴： <?php echo $UserInfo['Replies']; ?></p>
<p>网站： <a href="<?php echo $UserInfo['UserHomepage']; ?>" target="_blank" rel="nofollow"><?php echo $UserInfo['UserHomepage']; ?></a></p>
<p>关于： <br/> <?php echo $UserInfo['UserIntro']; ?></p>
</div>
<div class="c"></div>
</div>
<!-- User Infomation end -->
<!-- posts list start -->
<div class="title">
	最后活动于 <?php echo FormatTime($UserInfo['LastPostTime']); ?>
</div>
<div class="main-box home-box-list">
<?php
foreach($PostsArray as $key => $post)
{
?>
	<div class="commont-item">
		<div class="user-commont-data">
			<div class="commont-content">
			<h2 class="grey"><?php echo $post['IsTopic']?'创建了主题':'回复了主题';?>&nbsp;›&nbsp;<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $post['TopicID']; ?>" target="_blank"><?php echo $post['Subject'];?></a></h2>
			<?php echo strip_tags(mb_substr($post['Content'], 0, 200, 'utf-8'),'<p><br><a>'); ?>
			</div>
			
			<div class="commont-data-date">
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