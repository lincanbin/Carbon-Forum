<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<div id="header">

</div>
<div id="content">
<?php } ?>
<!-- main-content start -->
	<div data-title="<?php echo $PageTitle; ?>" id="New" class="panel" selected="true">
		<script type="text/javascript">
			var MaxTagNum = <?php echo $Config["MaxTagsNum"]; ?>;//最多的话题数量
			var MaxTitleChars = <?php echo $Config['MaxTitleChars']; ?>;//主题标题最多字节数
			var MaxPostChars = <?php echo $Config['MaxPostChars']; ?>;//主题内容最多字节数
			loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/mobile.new.function.js?version=<?php echo $Config['Version']; ?>", function() {
				// body...
				console.log("Loaded");
			});
		</script>
		<form name="NewForm" onkeydown="if(event.keyCode==13)return false;">
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<input type="hidden" name="ContentHash" value="" />
			<p><input type="text" name="Title" id="Title" value="<?php echo htmlspecialchars($Title); ?>" placeholder="<?php echo $Lang['Title']; ?>" /></p>
			<p>
				<textarea name="Content" id="Content" rows="10" placeholder="<?php echo $Lang['Content']; ?>"></textarea>
			</p>
			<p>
				<input type="text" name="AlternativeTag" id="AlternativeTag" value="" onclick="JavaScript:GetTags();" placeholder="<?php echo $Lang['Add_Tags']; ?>" />
				<ul id="SelectTags" class="list">
					<li class="divider"><?php echo $Lang['Tags']; ?></li>
				</ul>
			</p>
			<p>
				<div id="TagsList">
				</div>
			</p>
			<p><input type="button" value="<?php echo $Lang['Submit']; ?>" name="submit" class="button" onclick="JavaScript:CreateNewTopic();" id="PublishButton" /></p>
		</form>
	<!-- main-content end -->
<?php
if(!$IsAjax){
?>
	</div>
<!-- this is the default left side nav menu.  If you do not want any, do not include these -->
<nav>
	<ul class="list">
		<?php include($TemplatePath.'sider.php'); ?>
	</ul>
</nav>
<?php } ?>