<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<script type="text/javascript">
	var AllowEmptyTags = <?php echo $Config["AllowEmptyTags"]; ?>;//允许空话题
	var MaxTagNum = <?php echo $Config["MaxTagsNum"]; ?>;//最多的话题数量
	var MaxTitleChars = <?php echo $Config['MaxTitleChars']; ?>;//主题标题最多字节数
	var MaxPostChars = <?php echo $Config['MaxPostChars']; ?>;//主题内容最多字节数
	loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/mobile/topic.function.js?version=<?php echo CARBON_FORUM_VERSION; ?>", function() {
		$.each(<?php echo json_encode(ArrayColumn($HotTagsArray, 'Name')); ?>,function(Offset,TagName) {
			TagsListAppend(TagName, Offset);
		});
	});
</script>
<form name="NewForm">
	<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
	<input type="hidden" name="ContentHash" value="" />
	<p><input type="text" name="Title" id="Title" value="<?php echo htmlspecialchars($Title); ?>" placeholder="<?php echo $Lang['Title']; ?>" /></p>
	<p>
		<label class="button block" style="cursor: pointer;">
			<i class="icon picture"></i>
			<input type="file" id="upfile" onchange="javascript:UploadPicture('Content');" accept="image/*" style="display:none;" />
		</label>
	</p>
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
	<p><input type="button" value="<?php echo $Lang['Submit']; ?>" name="submit" class="button block green" onclick="JavaScript:CreateNewTopic();" id="PublishButton" style="width:100%;" /></p>
</form>