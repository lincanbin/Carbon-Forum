<?php
if (!defined('InternalAccess')) exit('{"Status": 0, "ErrorCode": "403", "ErrorMessage": "403"}');
foreach($ReplyArray as $Key => $Post)
{
	$ReplyArray[$Key]['PostFloor'] = -1;
	$ReplyArray[$Key]['Content'] = strip_tags(mb_substr($Post['Content'], 0, 256, 'utf-8'),'<p><br><a>');;
}
foreach($MentionArray as $Key => $Post)
{
	$MentionArray[$Key]['PostFloor'] = -1;
	$MentionArray[$Key]['Content'] = strip_tags(mb_substr($Post['Content'], 0, 256, 'utf-8'),'<p><br><a>');;
}
?>
{
	"Status": 1,
	"ReplyArray" : <?php echo json_encode($ReplyArray); ?>,
	"MentionArray": <?php echo json_encode($MentionArray);?>
}