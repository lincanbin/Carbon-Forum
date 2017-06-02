<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/"><?php echo $Config['SiteName']; ?></a>
		&raquo; <?php echo $UserInfo['UserName']; ?>
	</div>
	<!-- User Infomation start -->
	<div class="main-box">
		<div class="member-avatar">
			<?php echo GetAvatar($UserInfo['ID'], $UserInfo['UserName'], 'large'); ?>
		</div>
		<div class="member-detail">
			<p><?php echo $Lang['UserName']; ?>：<strong><?php echo $UserInfo['UserName']; ?></strong></p>
			<p><?php echo $Lang['Registered_In']; ?>：<?php echo FormatTime($UserInfo['UserRegTime']); ?></p>
			<p>
				<?php echo $Lang['Topics_Number']; ?>：
				<a href="<?php echo $Config['WebsitePath']; ?>/search/<?php echo urlencode('user:' . $UserInfo['UserName']); ?>">
					<?php echo $UserInfo['Topics']; ?>
				</a>
				&nbsp;&nbsp;&nbsp;
				<?php echo $Lang['Posts_Number']; ?>：
				<a href="<?php echo $Config['WebsitePath']; ?>/search/<?php echo urlencode('user:' . $UserInfo['UserName'] . ' post:true'); ?>">
					<?php echo $UserInfo['Replies']; ?>
				</a>
			</p>
			<p><?php echo $Lang['Homepage']; ?>： <a href="<?php echo $UserInfo['UserHomepage']; ?>" target="_blank"
													rel="nofollow"><?php echo $UserInfo['UserHomepage']; ?></a></p>
			<p><?php echo $Lang['Introduction']; ?>：
				<br/> <?php echo str_replace("\n", "<br \>", $UserInfo['UserIntro']); ?></p>
		</div>
		<div class="member-btn btn">
			<?php
			if ($CurUserID) {
				?>
				<p>
				<?php
				if ($CurUserID != $UserInfo['ID']) {
					?>
					<a href="###" onclick="javascript:Manage(<?php echo $UserInfo['ID']; ?>, 4, 3, false, this);">
						<?php echo $IsFavorite ? $Lang['Unfollow'] : $Lang['Follow']; ?>
					</a>
					<a href="<?php echo $Config['WebsitePath']; ?>/inbox/<?php echo urlencode($UserInfo['UserName']); ?>">
						<?php echo $Lang['Send_Message'] ?>
					</a>
						<?php
					}
				if ($CurUserRole >= 4) {
					?>
					<div class="c"></div>
					<a href="###" onclick="javascript:Manage(<?php echo $UserInfo['ID']; ?>, 3, 'Block', true, this);">
						<?php echo $UserInfo['UserAccountStatus'] ? $Lang['Block_User'] : $Lang['Unblock_User']; ?>
					</a>

					<a href="###" onclick="javascript:Manage(<?php echo $UserInfo['ID']; ?>, 3, 'ResetAvatar', true, this);">
						<?php echo $Lang['Reset_Avatar']; ?>
					</a>
					<?php
				}
				?>
				</p>
				<?php
			}
			?>
		</div>
		<div class="c"></div>
	</div>
	<!-- User Infomation end -->
	<!-- posts list start -->
	<div class="title">
		<?php echo $Lang['Last_Activity_In']; ?><?php echo FormatTime($UserInfo['LastPostTime']); ?>
	</div>
	<div class="main-box home-box-list">
		<?php
		foreach ($PostsArray as $Post) {
			?>
			<div class="comment-item">
				<div class="user-comment-data">
					<div class="comment-content">
						<span class="user-activity-title"><?php echo $Post['IsTopic'] ? $Lang['Created_Topic'] : $Lang['Replied_To_Topic']; ?>
							&nbsp;›&nbsp;<a
									href="<?php echo $Config['WebsitePath']; ?>/goto/<?php echo $Post['TopicID']; ?>-<?php echo $Post['ID']; ?>#Post<?php echo $Post['ID']; ?>"><?php echo $Post['Subject']; ?></a></span>
						<?php echo strip_tags(mb_substr($Post['Content'], 0, 300, 'utf-8'), '<p><br>'); ?>
					</div>

					<div class="comment-data-date">
						<div class="float-right">
							&laquo;&nbsp;&nbsp;<?php echo FormatTime($Post['PostTime']); ?></div>
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
	<?php include($TemplatePath . 'sider.php'); ?>
</div>
<!-- main-sider end -->