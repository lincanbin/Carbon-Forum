<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<div class="button-grouped flex tabbed">
	<a class="button pressed" onclick="$('#Notifications2').hide();$('#Notifications1').show();"><?php echo $Lang['Notifications_Replied_To_Me']; ?></a>
	<a class="button" id="mention_button" onclick="$('#Notifications1').hide();$('#Notifications2').show();"><?php echo $Lang['Notifications_Mentioned_Me']; ?></a>
</div>
<div id="Notifications1">
<!-- Posts list start -->
<?php
foreach($ReplyArray as $Post)
{
?>
	<div class="card">
		<div class="card-header"><a href="<?php echo $Config['WebsitePath']; ?>/goto/<?php echo $Post['TopicID']; ?>-<?php echo $Post['ID']; ?>" data-transition="slide"><?php echo $Post['UserName'];?>&nbsp;&nbsp;<?php echo $Lang['Replied_To_Topic']; ?>&nbsp;›&nbsp;<?php echo $Post['Subject'];?></a></div>
		<div class="card-content">
			<div class="card-content-inner"><?php echo strip_tags(mb_substr($Post['Content'], 0, 512, 'utf-8'),'<p><br><a>'); ?></div>
		</div>
		<div class="card-footer"><?php echo FormatTime($Post['PostTime']); ?></div>
	</div>
<?php
}
?>
<!-- Posts list end -->
</div>

<div id="Notifications2" style="display:none;">
<!-- Posts list start -->
<?php
foreach($MentionArray as $Post)
{
?>
	<div class="card">
		<div class="card-header"><a href="<?php echo $Config['WebsitePath']; ?>/goto/<?php echo $Post['TopicID']; ?>-<?php echo $Post['ID']; ?>" data-transition="slide"><?php echo $Post['UserName'];?>&nbsp;&nbsp;<?php echo $Lang['Mentioned_Me']; ?>&nbsp;›&nbsp;<?php echo $Post['Subject'];?></a></div>
		<div class="card-content">
			<div class="card-content-inner"><?php echo strip_tags(mb_substr($Post['Content'], 0, 512, 'utf-8'),'<p><br><a>'); ?></div>
		</div>
		<div class="card-footer"><?php echo FormatTime($Post['PostTime']); ?></div>
	</div>
<?php
}
?>
<!-- Posts list end -->
</div>
<script type="text/javascript">
<?php
if($MentionArray && (!$ReplyArray || ($ReplyArray && ($MentionArray[0]['PostTime'] > $ReplyArray[0]['PostTime']) ) ) ){
?>
	$("#mention_button").click();
<?php
}
?>
</script>