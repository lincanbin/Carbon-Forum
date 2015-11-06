<?php
if (!defined('InternalAccess')) exit('{"Status": 0, "ErrorCode": "403", "ErrorMessage": "403"}');
foreach($ReplyArray as $Key => $Post)
{
	$ReplyArray[$Key]['PostFloor'] = 0;
}
foreach($MentionArray as $Key => $Post)
{
	$MentionArray[$Key]['PostFloor'] = 0;
}
?>
{
	"Status": 1,
	"ReplyArray" : <?php echo json_encode($ReplyArray); ?>,
	"MentionArray": <?php echo json_encode($MentionArray);?>
}