<?php
if (!defined('InternalAccess')) exit('{"Status": 0, "ErrorCode": "403", "ErrorMessage": "403"}');
foreach($PostsArray as $Key => $Post)
{
	$PostsArray[$Key]['PostFloor'] = ($Page-1) * $Config['PostsPerPage'] + $Key;
}
?>
{
	"Status": 1,
	"Page": <?php echo $Page;?>,
	"TotalPage": <?php echo $TotalPage;?>,
	"IsFavorite": <?php echo $CurUserID?$IsFavorite:0;?>,
	"TopicInfo" : <?php echo json_encode($Topic); ?>,
	"PostsArray": <?php echo json_encode($PostsArray);?>
}