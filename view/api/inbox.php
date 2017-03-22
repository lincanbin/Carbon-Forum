<?php
if (!defined('InternalAccess')) exit('{"Status": 0, "ErrorCode": "403", "ErrorMessage": "403"}');
?>{
	"Status": 1,
	"DialogInfo" :  <?php echo json_encode($DialogInfo);?>,
	"MessagesArray": <?php echo json_encode($MessagesArray);?>
}
