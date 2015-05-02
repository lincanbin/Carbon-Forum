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
if(!$IsAjax){
?>
<!-- this is the header div at the top -->
<div id="header">
	<a id="menubadge" onclick="JavaScript:af.ui.toggleSideMenu()" class="menuButton"></a>
</div>
<div id="content">
	<!-- here is where you can add your panels -->
<?php
}
?>
	<div data-title="<?php echo $PageTitle; ?>" id="Search<?php echo base64_encode($Keyword).'-'.$Page; ?>" class="panel" selected="true">
		<ul class="list topic-list">
<?php
if($Page>1){
?>
			<li class="pagination"><a href="JavaScript:$.ui.loadContent('<?php echo $Config['WebsitePath']; ?>/search/<?php echo urlencode($Keyword).'/page/'.($Page-1); ?>',false,false,'slide');" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Page_Previous']; ?></a></li>

<?php
}
?>
<!-- main-content start -->
<?php
foreach ($TopicsArray as $Topic) {
?>
			<li>
				<div class="avatar">
					<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $Topic['UserName'] ?>" data-transition="slide" data-persist-ajax="true">
							<?php echo GetAvatar($Topic['UserID'], $Topic['UserName'], 'middle'); ?>
					</a>
				</div>
				<div class="content">
				<a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $Topic['ID']; ?>" data-transition="slide" data-persist-ajax="true">
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
if(!empty($TopicsArray)){
?>
			<li class="pagination"><a href="JavaScript:$.ui.loadContent('<?php echo $Config['WebsitePath']; ?>/search/<?php echo urlencode($Keyword).'/page/'.($Page+1); ?>',false,false,'slide');" data-transition="slide" data-persist-ajax="false"><?php echo $Lang['Page_Next']; ?></a></li>
<?php
}
?>
		</ul>
	</div>
<?php
if(!$IsAjax){
?>
</div>
<nav>
	<ul class="list">
		<?php include($TemplatePath.'sider.php'); ?>
	</ul>
</nav>
<?php
}
?>