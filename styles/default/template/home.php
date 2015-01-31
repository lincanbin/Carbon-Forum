<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="main-box home-box-list">
		<?php
		foreach ($TopicsArray as $Topic) {
		?>
			<div class="post-list">
				<div class="item-avatar">
					<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $Topic['UserName'] ?>" target="_blank">
						<?php echo GetAvatar($Topic['UserID'], $Topic['UserName'], 'middle'); ?>
					</a>
				</div>
				<div class="item-content">
					<h2>
						<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>">
							<?php echo $Topic['Topic']; ?>
						</a>
					</h2>
					<span class="item-tags">
						<?php
						if($Topic['Tags']){
							foreach (explode("|", $Topic['Tags']) as $Tag) {
						?><a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag); ?>" target="_blank"><?php echo $Tag; ?></a>
							<?php
							}
						}
						?></span><span class="item-date float-right"><a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $Topic['UserName'] ?>"><?php echo $Topic['UserName']; ?></a>&nbsp;•&nbsp;<?php echo FormatTime($Topic['LastTime']); 
							if($Topic['Replies']){
						?>&nbsp;•&nbsp;<?php echo $Lang['Last_Reply_From']; ?>&nbsp;<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $Topic['LastName'] ?>"><?php echo $Topic['LastName']; ?></a><?php } ?>
					</span>
				</div>
							<?php if($Topic['Replies']){ ?>
							<div class="item-count">
							<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>" target="_blank"><?php echo $Topic['Replies']; ?></a>
							</div>
							<?php } ?>
							<div class="c"></div>
							</div>
							
		<?php
		}
		?>
								<div class="pagination">
								<?php Pagination("/page/",$Page,$TotalPage);
								?>
									<div class="c">
									</div>
								</div>
				</div>
			</div>
			<!-- main-content end -->
			<!-- main-sider start -->
			<div class="main-sider">
				<?php
				include($TemplatePath.'sider.php');
				?>
				<div class="sider-box">
					<div class="sider-box-title"><?php echo $Lang['Website_Statistics']; ?></div>
					<div class="sider-box-content">
						<ul>
							<li><?php echo $Lang['Topics_Number']; ?>：<?php echo $Config['NumTopics']; ?></li>
							<li><?php echo $Lang['Posts_Number']; ?>：<?php echo $Config['NumPosts']; ?></li>
							<li><?php echo $Lang['Tags_Number']; ?>：<?php echo $Config['NumTags']; ?></li>
							<li><?php echo $Lang['Users_Number']; ?>：<?php echo $Config['NumUsers']; ?></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- main-sider end -->