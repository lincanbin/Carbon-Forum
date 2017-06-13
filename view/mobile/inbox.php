<script>
	$(document).ready(function(){
		loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/mobile/inbox.js?version=<?php echo CARBON_FORUM_VERSION; ?>", function(){
			loadMoreMessages(true);
			var _target = $(".panel.active[selected=true]");
			_target.unbind();
			_target.scroll(function () {
				loadMessagesList($(this));
			});
		});
	});
</script>
<template id="MessageTemplate">
	<div class="message-item message-{{Position}} ">
		<div class="message-avatar">
			<a href="<?php echo $Config['WebsitePath']; ?>/u/{{ContactName}}" data-transition="slide">
				<img src="<?php echo $Config['WebsitePath']; ?>/upload/avatar/middle/{{ContactID}}.png" alt="{{ContactName}}"/>
			</a>
		</div>
		<div class="jt jt-{{Position}}"></div>
		<div class="message-content">
			<p>{{Content}}</p>
			<div class="grey">{{FormatTime}}</div>
		</div>
	</div>
</template>

<input type="hidden" id="InboxID" value="<?php echo $InboxID; ?>"/>
<input type="hidden" id="MessagesPage" value="1"/>
<input type="hidden" id="MessagesLoading" value="0"/>
<p>
	<textarea class="w600 h160" name="MessageContent" id="MessageContent"
			  placeholder="<?php echo $Lang['Message_Content']; ?>"></textarea>
</p>

<p>
	<input type="button" value="<?php echo $Lang['Send_Message']; ?>" name="submit" class="button block green"
		   onclick="JavaScript:;" id="SendMessageButton" style="width:100%;"/>
</p>

<div id="MessagesList"></div>