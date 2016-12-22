<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<h2 class="expanded"><?php echo $TagInfo['Name']; ?></h2>
<p id="TagDescription<?php echo $TagInfo['ID']; ?>">
<?php
if($CurUserID){
?>
<a href="#" class="button block" onclick="javascript:Manage(<?php echo $TagInfo['ID']; ?>, 4, 2, false, this);"><?php echo $IsFavorite?$Lang['Unfollow']:$Lang['Follow']; ?></a>
<?php
}
echo $TagInfo['Description'];
?>
</p>
<ul class="list topic-list">
<?php
if($Page>1){
?>
	<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo strtolower(urlencode($TagInfo['Name'])).'/page/'.($Page-1); ?>" data-transition="slide"><?php echo $Lang['Page_Previous']; ?></a></li>

<?php
}
?>
<!-- main-content start -->
<?php
foreach ($TopicsArray as $Topic) {
?>
	<li>
		<div class="avatar">
			<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($Topic['UserName']); ?>" data-transition="slide">
					<?php echo GetAvatar($Topic['UserID'], $Topic['UserName'], 'middle'); ?>
			</a>
		</div>
		<div class="content">
		<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>" data-transition="slide">
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
<ul class="list">
	<li class="divider"><?php echo $Lang['Tag']; ?>：<?php echo $TagName; ?></li>
	<li>
		<?php echo $TagInfo['TotalPosts']; ?><?php echo $Lang['Topics']; ?>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo $TagInfo['Followers']; ?><?php echo $Lang['Followers']; ?>
	</li>
	<li><?php echo $Lang['Created_In']; ?><?php echo FormatTime($TagInfo['DateCreated']); ?></li>
	<li><?php echo $Lang['Last_Updated_In']; ?><?php echo FormatTime($TagInfo['MostRecentPostTime']); ?></li>
</ul>