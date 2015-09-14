<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<div class="button-grouped flex tabbed">
	<a class="button pressed" onclick="$('#Notifications2').hide();$('#Notifications1').show();"><?php echo $Lang['Notifications_Replied_To_Me']; ?></a>
	<a class="button" onclick="$('#Notifications1').hide();$('#Notifications2').show();"><?php echo $Lang['Notifications_Mentioned_Me']; ?></a>
</div>
<div id="Notifications1">
<!-- posts list start -->
<?php
foreach($ReplyArray as $key => $post)
{
?>
	<div class="card">
		<div class="card-header"><a href="<?php echo $Config['WebsitePath']; ?>/goto/<?php echo $post['TopicID']; ?>-<?php echo $post['ID']; ?>" data-transition="slide" data-persist-ajax="true"><?php echo $post['UserName'];?>&nbsp;&nbsp;<?php echo $Lang['Replied_To_Topic']; ?>&nbsp;›&nbsp;<?php echo $post['Subject'];?></a></div>
		<div class="card-content">
			<div class="card-content-inner"><?php echo strip_tags(mb_substr($post['Content'], 0, 512, 'utf-8'),'<p><br><a>'); ?></div>
		</div>
		<div class="card-footer"><?php echo FormatTime($post['PostTime']); ?></div>
	</div>
<?php
}
?>
<!-- posts list end -->
</div>

<div id="Notifications2" style="display:none;">
<!-- posts list start -->
<?php
foreach($MentionArray as $key => $post)
{
?>
	<div class="card">
		<div class="card-header"><a href="<?php echo $Config['WebsitePath']; ?>/goto/<?php echo $post['TopicID']; ?>-<?php echo $post['ID']; ?>" data-transition="slide" data-persist-ajax="true"><?php echo $post['UserName'];?>&nbsp;&nbsp;<?php echo $Lang['Mentioned_Me']; ?>&nbsp;›&nbsp;<?php echo $post['Subject'];?></a></div>
		<div class="card-content">
			<div class="card-content-inner"><?php echo strip_tags(mb_substr($post['Content'], 0, 512, 'utf-8'),'<p><br><a>'); ?></div>
		</div>
		<div class="card-footer"><?php echo FormatTime($post['PostTime']); ?></div>
	</div>
<?php
}
?>
<!-- posts list end -->
</div>