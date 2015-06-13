<?php
if (!defined('InternalAccess')) exit('{"Status": 0,"ErrorMessage": "403"}');
if($Error){
?>{
	"Status": 0,
	"ErrorMessage": "<?php echo $Error;?>"
}<?php }else{ ?>{
	"Status": 1,
	"TopicID": "<?php echo $TopicID;?>",
	"PostID": "<?php echo $PostID;?>",
	"Page": "<?php echo $TotalPage;?>"
}<?php } ?>