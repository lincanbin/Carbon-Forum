<div class="main-content">
<script>
$(document).ready(function(){
	loadMoreMessages(true);
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
		<a href="<?php echo $Config['WebsitePath']; ?>/"><?php echo $Config['SiteName']; ?></a> &raquo; <a href="<?php echo $Config['WebsitePath']; ?>/notifications/list#notifications3">Inbox</a> &raquo; Chat with <a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $ContactUserName; ?>"><?php echo $ContactUserName; ?></a>
	</div>
	<div class="main-box">
		<div class="inbox-textarea-wrap">
			<input type="hidden" name="InboxID" id="InboxID" value="<?php echo $InboxID; ?>" />
			<textarea class="w600 h160" name="MessageContent" placeholder="Content"></textarea>
		</div>
		<div class="text-center">
			<input type="button" value="提交(Ctrl+Enter)" class="textbtn" onclick="JavaScript:;" id="SendMessageButton" />
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