<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
//关键字加亮
function KeywordHighlight($Content)
{
	global $KeywordArray;
	if ($KeywordArray) {
		$KeywordHighlightArray = array();
		foreach ($KeywordArray as $Value) {
			$KeywordHighlightArray[] = '<span class="red">' . $Value . '</span>';
		}
		return str_ireplace($KeywordArray, $KeywordHighlightArray, $Content);
	} else {
		return $Content;
	}
}
?>
<ul class="list topic-list">
<?php
if($Page>1){
?>
	<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/search/<?php echo urlencode($Keyword).'/page/'.($Page-1); ?>" data-transition="slide"><?php echo $Lang['Page_Previous']; ?></a></li>
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
			<h2><?php echo KeywordHighlight($Topic['Topic']); ?></h2>
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
if (!$IsLastPage) {
?>
	<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/search/<?php echo urlencode($Keyword).'/page/'.($Page+1); ?>" data-transition="slide" data-persist-ajax="false"><?php echo $Lang['Page_Next']; ?></a></li>
<?php
}
?>
</ul>