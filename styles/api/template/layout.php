<?php
if (!defined('InternalAccess')) exit('{"Status": 0, "ErrorCode": "403", "ErrorMessage": "403"}');

ob_start();
include($ContentFile);

$MicroTime = explode(' ', microtime());
$TotalTime = number_format(($MicroTime[1] + $MicroTime[0] - $StartTime), 6) * 1000;
header("X-Response-Time: " . $TotalTime . "ms");
ob_end_flush();
?>