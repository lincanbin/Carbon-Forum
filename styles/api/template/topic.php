<?php
if (!defined('InternalAccess')) exit('{"Status": 0,"ErrorMessage": "403"}');
foreach($PostsArray as $key => $Post)
{
	$PostsArray['PostFloor'] = ($Page-1) * $Config['PostsPerPage'] + $key;
}
?>
{
	"Status": 1,
	"Page": "<?php echo $Page;?>",
	"TotalPage": "<?php echo $TotalPage;?>",
	"IsFavorite": "<?php echo $CurUserID?$IsFavorite:0;?>",
	"TopicInfo" : <?php echo json_encode($Topic); ?>,
	"PostsArray": <?php echo json_encode($PostsArray);?>
}