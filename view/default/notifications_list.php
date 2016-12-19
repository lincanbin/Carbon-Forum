<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
<script>
$(document).ready(function(){
	$("#notifications").easyResponsiveTabs({
		type: 'default', //Types: default, vertical, accordion           
		width: 'auto', //auto or any custom width
		fit: true,   // 100% fits in a container
		closed: false, // Close the panels on start, the options 'accordion' and 'tabs' keep them closed in there respective view types
		activate: function() {}  // Callback function, gets called if tab is switched
	});
/*<?php
if($MentionArray && (!$ReplyArray || ($ReplyArray && ($MentionArray[0]['PostTime'] > $ReplyArray[0]['PostTime']) ) ) ){
?>
	$(".resp-tab-item")[1].click();
<?php
}
?>*/
	loadMoreReply(true);
	loadMoreMention(true);
});
</script>
<script type="text/template" id="RepliedToMePostTemplate">
<div class="comment-item">
	<div class="user-comment-data">
		<div class="comment-content">
		<span class="user-activity-title">
		<a href="<?php echo $Config['WebsitePath']; ?>/u/{{UserName}}">{{UserName}}</a>
		&nbsp;&nbsp;<?php echo $Lang['Replied_To_Topic']; ?>&nbsp;›&nbsp;
		<a href="<?php echo $Config['WebsitePath']; ?>/goto/{{TopicID}}-{{ID}}#Post{{ID}}">{{Subject}}</a></span>
			{{Content}}
		</div>
		
		<div class="comment-data-date">
			<div class="float-right">
&laquo;&nbsp;&nbsp;{{FormatPostTime}}</div>
			<div class="c"></div>
		</div>
		<div class="c"></div>
	</div>
	<div class="c"></div>
</div>
</script>
<script type="text/template" id="MentionedMePostTemplate">
<div class="comment-item">
	<div class="user-comment-data">
		<div class="comment-content">
		<span class="user-activity-title">
		<a href="<?php echo $Config['WebsitePath']; ?>/u/{{UserName}}">{{UserName}}</a>
		&nbsp;&nbsp;<?php echo $Lang['Mentioned_Me']; ?>&nbsp;›&nbsp;
		<a href="<?php echo $Config['WebsitePath']; ?>/goto/{{TopicID}}-{{ID}}#Post{{ID}}">{{Subject}}</a></span>
			{{Content}}
		</div>
		
		<div class="comment-data-date">
			<div class="float-right">
&laquo;&nbsp;&nbsp;{{FormatPostTime}}</div>
			<div class="c"></div>
		</div>
		<div class="c"></div>
	</div>
	<div class="c"></div>
</div>
</script>

	<div id="notifications" class="tab-container">
		<input type="hidden" id="RepliedToMePage" value="1" />
		<input type="hidden" id="RepliedToMeLoading" value="0" />
		<input type="hidden" id="MentionedMePage" value="1" />
		<input type="hidden" id="MentionedMeLoading" value="0" />
		<ul class="resp-tabs-list">
			<li><?php echo $Lang['Notifications_Replied_To_Me']; ?></li>
			<li><?php echo $Lang['Notifications_Mentioned_Me']; ?></li>
		</ul>
		<div class="resp-tabs-container main-box home-box-list">
			<div id="RepliedToMeList"></div>
			<div id="MentionedMeList"></div>
		</div>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
	<?php include($TemplatePath.'sider.php'); ?>
</div>
<!-- main-sider end -->