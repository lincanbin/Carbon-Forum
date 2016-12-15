<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<h2 class="expanded" onclick="showHide(this,'UsersFollowing<?php echo $Page; ?>');"><?php echo $Lang['My_Following_Users']; ?></h2>
<p id="UsersFollowing<?php echo $Page; ?>">
<?php
foreach ($UsersFollowing as $User){
?>
	<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($User['Title']); ?>" class="button"><?php echo GetAvatar($User['FavoriteID'], $User['Title'], 'small'); ?>&nbsp;&nbsp;<?php echo $User['Title']; ?></a>
<?php
}
?>
<?php
if($Page>1){
?>
<ul class="list topic-list">
<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/users/following/page/<?php echo ($Page-1); ?>"><?php echo $Lang['Page_Previous']; ?></a></li>
</ul>
<?php
}
foreach($PostsArray as $key => $Post) {
?>
<div class="card carbonforum-card">
	<div class="card-header">
		<div class="carbonforum-avatar">
			<a href="<?php echo $Config['WebsitePath'].'/u/'.urlencode($Post['UserName']); ?>">
				<?php echo GetAvatar($Post['UserID'], $Post['UserName'], 'small'); ?>
			</a>
		</div>
		<div class="carbonforum-name"><?php echo $Post['UserName'];?></div>
		<div class="carbonforum-date"><?php echo FormatTime($Post['PostTime']); ?></div>
	</div>
	<div class="card-header">
		<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Post['TopicID']; ?>"><?php echo $Post['IsTopic']?$Lang['Created_Topic']:$Lang['Replied_Topic'];?>&nbsp;<?php echo $Post['Subject'];?></a>
	</div>
	<div class="card-content"><p><?php echo strip_tags(mb_substr($Post['Content'], 0, 200, 'utf-8'),'<p><br><a>'); ?></p></div>
</div>
<?php
}
if (!$IsLastPage) {
?>
<ul class="list topic-list">
	<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/users/following/page/<?php echo ($Page+1); ?>"><?php echo $Lang['Page_Next']; ?></a></li>
</ul>
<?php
}
?>