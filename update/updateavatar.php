<?php
set_time_limit(0);
require('../config.php');
require('../includes/PDO.class.php');
require("../includes/MaterialDesign.Avatars.class.php");
$DB     = new Db(DBHost, DBName, DBUser, DBPassword);

$UserIDArray = $DB->query('SELECT UserName, ID FROM '.$Prefix.'users');
foreach ($UserIDArray as $UserInfo) {
	if(!is_file('../upload/avatar/small/' . $UserInfo['ID'] . '.png')){
		echo $UserInfo['UserName'].'<br />';
		if(extension_loaded('gd')){
			$Avatar = new MDAvtars(mb_substr($UserInfo['UserName'], 0, 1, "UTF-8"), 256);
			$Avatar->Save('../upload/avatar/large/' . $UserInfo['ID'] . '.png', 256);
			$Avatar->Save('../upload/avatar/middle/' . $UserInfo['ID'] . '.png', 48);
			$Avatar->Save('../upload/avatar/small/' . $UserInfo['ID'] . '.png', 24);
		}
	}
}
$DB->CloseConnection();