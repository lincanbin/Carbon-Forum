<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<ul class="list topic-list">
<?php
if($Page>1){
?>
	<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/tags/page/<?php echo ($Page-1); ?>" data-transition="slide"><?php echo $Lang['Page_Previous']; ?></a></li>
<?php
}
foreach ($TagsArray as $Tag) {
?>
	<li>
		<div class="avatar">
			<a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag['Name']); ?>">
				<?php echo GetTagIcon($Tag['ID'], $Tag['Icon'], $Tag['Name'], 'middle'); ?>
			</a>
		</div>
		<div class="content">
		<h2>
			<a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag['Name']); ?>">
				<?php echo $Tag['Name']; ?>    (<?php echo $Tag['TotalPosts']; ?>)
			</a>
		</h2>
		<p><?php echo ($Tag['Description']? mb_strlen($Tag['Description']) > 60 ? mb_substr($Tag['Description'], 0, 60, 'utf-8').'……' : $Tag['Description'] : '' ); ?>
		</p>
		</div>
		
		<div class="c"></div>
	</li>
<?php
} 
if($Page<$TotalPage){
?>
	<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/tags/page/<?php echo ($Page+1); ?>" data-transition="slide"><?php echo $Lang['Page_Next']; ?></a></li>
<?php
}
?>
</ul>