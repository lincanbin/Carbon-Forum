<?php
if (!defined('InternalAccess')) exit('{"Status": 0,"ErrorMessage": "403"}');
?>
{
	"Status": 1,
	"PageTitle": "<?php echo $PageTitle;?>",
	"Page": "<?php echo $Page;?>",
	"TotalPage": "<?php echo $TotalPage;?>",
	"TopicsArray": <?php echo json_encode($TopicsArray);?>
}
?>