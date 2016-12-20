<?php
if (!defined('InternalAccess')) exit('{"Status": 0, "ErrorCode": "403", "ErrorMessage": "403"}');
?>
{
	"Status": 1,
	"CarbonForumVersion": <?php echo CARBON_FORUM_VERSION ?>,
	"PageTitle": "<?php echo $PageTitle;?>",
	"Page": <?php echo $Page;?>,
	"TotalPage": <?php echo $TotalPage;?>,
	"TopicsArray": <?php echo json_encode($TopicsArray);?>
}