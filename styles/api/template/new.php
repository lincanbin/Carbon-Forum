<?php
if (!defined('InternalAccess')) exit('{"Status": 0, "ErrorCode": "403", "ErrorMessage": "403"}');
if($Error){
?>{
	"Status": 0,
	"ErrorCode": <?php echo $ErrorCode;?>,
	"ErrorMessage": "<?php echo $Error;?>"
}<?php
}else{
?>{
	"Status": 1,
	"TopicID": <?php echo $TopicID;?>
}<?php
}
?>