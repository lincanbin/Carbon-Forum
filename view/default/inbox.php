<div class="main-content">
<script>
$(document).ready(function(){
	loadMoreMessages(true);
	loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/default/inbox.js?version=<?php echo CARBON_FORUM_VERSION; ?>", function(){
	});
});
</script>
	<input type="hidden" id="InboxID" value="<?php echo $InboxID; ?>" />
	<input type="hidden" id="MessagesPage" value="1" />
	<input type="hidden" id="MessagesLoading" value="0" />
	<script type="text/template" id="MessageTemplate">
	<div class="message-item">
		<div class="message-avatar avatar-{{Position}}">
			<a href="<?php echo $Config['WebsitePath']; ?>/u/{{ContactName}}">
				<img src="<?php echo $Config['WebsitePath']; ?>/upload/avatar/middle/{{ContactID}}.png" alt="{{ContactName}}"/>
			</a>
		</div>
		<div class="jt jt-{{Position}}"></div>
		<div class="message-content">
			<p>{{Content}}</p>
			<div class="grey">{{FormatTime}}</div>
		</div>
	</div>
	</script>

	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/"><?php echo $Config['SiteName']; ?></a> &raquo;
        <a href="<?php echo $Config['WebsitePath']; ?>/notifications/list#notifications3"><?php echo $Lang['Inbox']; ?></a> &raquo;
        <?php echo str_replace('{{UserName}}', '<a href="' . $Config['WebsitePath']  . '/u/' . $ContactUserName . '">' . $ContactUserName . '</a>', $Lang['Chat_With_SB']) ?>
	</div>
	<div class="main-box">
		<div class="inbox-textarea-wrap">
			<textarea class="h160 w600" name="MessageContent" id="MessageContent" placeholder="<?php echo $Lang['Message_Content']; ?>"></textarea>
		</div>
		<div class="text-center">
			<input type="button" value="<?php echo $Lang['Send_Message']; ?>" class="textbtn" onclick="JavaScript:;" id="SendMessageButton" />
		</div>
		<div class="c"></div>
		<div id="MessagesList"></div>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
	<?php include($TemplatePath.'sider.php'); ?>
</div>
<!-- main-sider end -->