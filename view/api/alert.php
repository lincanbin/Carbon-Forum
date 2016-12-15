<?php
if (!defined('InternalAccess')) exit('{"Status": 0, "ErrorCode": "403", "ErrorMessage": "403"}');
?>{
	"Status": 0,
	"ErrorCode": <?php echo $StatusCode;?>,
	"ErrorMessage": "<?php echo $Error;?>"
}