<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<template id="RepliedToMePostTemplate">  
	<div class="card">
		<div class="card-header"><a href="<?php echo $Config['WebsitePath']; ?>/goto/{{TopicID}}-{{ID}}" data-transition="slide">{{UserName}}&nbsp;&nbsp;<?php echo $Lang['Replied_To_Topic']; ?>&nbsp;›&nbsp;{{Subject}}</a></div>
		<div class="card-content">
			<div class="card-content-inner">{{Content}}</div>
		</div>
		<div class="card-footer">{{FormatPostTime}}</div>
	</div>
</template>

<template id="MentionedMePostTemplate">  
	<div class="card">
		<div class="card-header"><a href="<?php echo $Config['WebsitePath']; ?>/goto/{{TopicID}}-{{ID}}" data-transition="slide">{{UserName}}&nbsp;&nbsp;<?php echo $Lang['Mentioned_Me']; ?>&nbsp;›&nbsp;{{Subject}}</a></div>
		<div class="card-content">
			<div class="card-content-inner">{{Content}}</div>
		</div>
		<div class="card-footer">{{FormatPostTime}}</div>
	</div>
</template>

<input type="hidden" id="RepliedToMePage" value="1" />
<input type="hidden" id="RepliedToMeLoading" value="0" />
<input type="hidden" id="MentionedMePage" value="1" />
<input type="hidden" id="MentionedMeLoading" value="0" />

<div class="button-grouped flex tabbed">
	<a class="button pressed" onclick="$('#MentionedMeList').hide();$('#RepliedToMeList').show();"><?php echo $Lang['Notifications_Replied_To_Me']; ?></a>
	<a class="button" id="mention_button" onclick="$('#RepliedToMeList').hide();$('#MentionedMeList').show();"><?php echo $Lang['Notifications_Mentioned_Me']; ?></a>
</div>
<div id="RepliedToMeList"></div>
<div id="MentionedMeList" style="display:none;"></div>
<script type="text/javascript">
	loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/mobile/notifications.function.js?version=<?php echo CARBON_FORUM_VERSION; ?>", function() {
		loadMoreReply(true);
		loadMoreMention(true);
		var _target = $(".panel.active[selected=true]");
		_target.scroll(function() {
			loadNotificationsList($(this));
		});
	});
</script>