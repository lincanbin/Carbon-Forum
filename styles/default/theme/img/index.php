<?php
header('HTTP/1.1 404 Not Found');
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>404 Not Found</TITLE>
</HEAD><BODY>
<H1>Not Found</H1>
The requested URL <?php echo dirname($_SERVER["SCRIPT_NAME"]);?> was not found on this server.<P>
<HR>
<ADDRESS>Web Server at <?php echo $_SERVER["SERVER_NAME"];?> Port <?php echo $_SERVER["SERVER_PORT"];?></ADDRESS>
</BODY></HTML>