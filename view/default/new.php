<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<script>
var AllowEmptyTags = <?php echo $Config["AllowEmptyTags"]; ?>;//允许空话题
var MaxTagNum = <?php echo $Config["MaxTagsNum"]; ?>;//最多的话题数量
var MaxTitleChars = <?php echo $Config['MaxTitleChars']; ?>;//主题标题最多字节数
var MaxPostChars = <?php echo $Config['MaxPostChars']; ?>;//主题内容最多字节数
loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.config.js?version=<?php echo CARBON_FORUM_VERSION; ?>",function() {
	loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.all.min.js?version=<?php echo CARBON_FORUM_VERSION; ?>",function(){
		loadScript("<?php echo $Config['WebsitePath']; ?>/language/<?php echo ForumLanguage; ?>/<?php echo ForumLanguage; ?>.js?version=<?php echo CARBON_FORUM_VERSION; ?>",function(){
			loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/default/new.function.js?version=<?php echo CARBON_FORUM_VERSION; ?>",function(){
				$("#editor").empty();
				InitNewTopicEditor();
				$.each(<?php echo json_encode(ArrayColumn($HotTagsArray, 'Name')); ?>,function(Offset,TagName) {
					TagsListAppend(TagName, Offset);
				});
				console.log('editor loaded.');
			});
		});
	});
});
</script>
<div class="main-content">
		<div class="main-box">
			<form name="NewForm" onkeydown="if(event.keyCode==13)return false;">
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<input type="hidden" name="ContentHash" value="" />
			<p><input type="text" name="Title" id="Title" value="<?php echo htmlspecialchars($Title); ?>" style="width:624px;" placeholder="<?php echo $Lang['Title']; ?>" /></p>
		</div>
			<div id="editor" style="width:100%;height:320px;">Loading……</div>
			<script type="text/javascript">
			var Content='<?php echo $Content; ?>';
			</script>
		<div class="main-box" style="margin-top:20px;">
			<p>
				<div class="tags-list bth" style="width:624px;height:33px;border-bottom-width:2px;" onclick="JavaScript:document.NewForm.AlternativeTag.focus();">
					<span id="SelectTags" class="btn"></span>
					<input type="text" name="AlternativeTag" id="AlternativeTag" value="" class="tag-input" onfocus="JavaScript:GetTags();" placeholder="<?php echo $Lang['Add_Tags']; ?>" />
				</div>
			</p>
			<p>
				<div id="TagsList" class="btn">
				</div>
			</p>
			<p><div class="text-center"><input type="button" value="<?php echo $Lang['Submit']; ?>(Ctrl+Enter)" name="submit" class="textbtn" onclick="JavaScript:CreateNewTopic();" id="PublishButton" /></div><div class="c"></div></p>
			</form>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
	<?php include($TemplatePath.'sider.php'); ?>
</div>
<!-- main-sider end -->