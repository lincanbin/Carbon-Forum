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
			loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/mobile.new.function.js", function() {
				// body...
				console.log("Loaded");
			});
		</script>
		<form name="NewForm" onkeydown="if(event.keyCode==13)return false;">
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<input type="hidden" name="ContentHash" value="" />
			<p><input type="text" name="Title" id="Title" value="<?php echo htmlspecialchars($Title); ?>" placeholder="<?php echo $Lang['Title']; ?>" /></p>
			<p>
				<textarea name="Content"></textarea>
			</p>
			<p>
				<div onclick="JavaScript:document.NewForm.AlternativeTag.focus();">
					<span id="SelectTags"></span>
					<input type="text" name="AlternativeTag" id="AlternativeTag" value="" class="tag-input" onfocus="JavaScript:GetTags();" placeholder="<?php echo $Lang['Add_Tags']; ?>" />
				</div>
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