<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<ul class="list">
<?php
if($Page>1){
?>

	<li><a href="<?php echo $Config['WebsitePath']; ?>/favorites/page/<?php echo ($Page-1); ?>"><?php echo $Lang['Page_Previous']; ?></a></li>
<?php
}
foreach ($TopicsArray as $Topic) {
?>
	<li>
		<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['FavoriteID']; ?>" data-transition="slide">
			<?php echo $Topic['Title']; ?>
		</a>
	</li>
					
<?php
}
if($Page < $TotalPage){
?>
	<li><a href="<?php echo $Config['WebsitePath']; ?>/favorites/page/<?php echo ($Page+1); ?>"><?php echo $Lang['Page_Next']; ?></a></li>
<?php
}
?>
</ul>