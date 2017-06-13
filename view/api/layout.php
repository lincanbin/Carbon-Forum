<?php
if (!defined('InternalAccess')) exit('{"Status": 0, "ErrorCode": "403", "ErrorMessage": "403"}');

ob_start();
include($ContentFile);

$TotalTime = number_format((microtime(true) - $StartTime) * 1000, 3);
header("X-Response-Time: " . $TotalTime . "ms");
ob_end_flush();
?>