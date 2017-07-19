<?php
@set_time_limit(0);
date_default_timezone_set('Asia/Shanghai'); //设置中国时区
$Message = '';
$Version = '5.9.0';
define('DATABASE_PREFIX', 'carbon_');

if (is_file('update.lock')) {
	die("请删除 update/update.lock 文件后再进行操作！<br>Please Remove update/update.lock before update!");
}

//检查config.php是否可以写入
if (is_writable(dirname(dirname(__FILE__))) === false) {
	die("根目录不可写，无法写入配置文件。  The root directory can not be written. This causes the configuration file to not be generated. ");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$Language       = $_POST['Language'];
	$DBHost         = $_POST['DBHost'];
	$DBName         = $_POST['DBName'];
	$DBUser         = $_POST['DBUser'];
	$DBPassword     = $_POST['DBPassword'];
	$SearchServer   = $_POST['SearchServer'];
	$SearchPort     = $_POST['SearchPort'];
	$EnableMemcache = $_POST['EnableMemcache'];
	$MemCachePrefix = $_POST['MemCachePrefix'];
	//$WebsitePath = $_POST['WebsitePath'];
	$WebsitePath    = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	if (preg_match('/(.*)\/update/i', $WebsitePath, $WebsitePathMatch)) {
		$WebsitePath = $WebsitePathMatch[1];
	} else {
		$WebsitePath = '';
	}
	//初始化数据库操作类
	require('../library/PDO.class.php');
	$DB         = new Db($DBHost, 3306, $DBName, $DBUser, $DBPassword);
	$OldVersion = $DB->single("SELECT ConfigValue FROM `" . DATABASE_PREFIX . "config` WHERE `ConfigName`='Version'");
	//数据处理
	$DB->query("UPDATE `" . DATABASE_PREFIX . "config` SET `ConfigValue`='" . $WebsitePath . "' WHERE `ConfigName`='WebsitePath'");
	$DB->query("UPDATE `" . DATABASE_PREFIX . "config` SET `ConfigValue`='" . $WebsitePath . "/static/js/jquery.js' WHERE `ConfigName`='LoadJqueryUrl'");
	
	//写入config文件
	$ConfigPointer = fopen('../install/config.tpl', 'r');
	$ConfigBuffer  = fread($ConfigPointer, filesize('../install/config.tpl'));
	$ConfigBuffer  = str_replace("{{Language}}", $Language, $ConfigBuffer);
	$ConfigBuffer  = str_replace("{{DBHost}}", $DBHost, $ConfigBuffer);
	$ConfigBuffer  = str_replace("{{DBName}}", $DBName, $ConfigBuffer);
	$ConfigBuffer  = str_replace("{{DBUser}}", $DBUser, $ConfigBuffer);
	$ConfigBuffer  = str_replace("{{DBPassword}}", $DBPassword, $ConfigBuffer);
	$ConfigBuffer  = str_replace("{{SearchServer}}", $SearchServer, $ConfigBuffer);
	$ConfigBuffer  = str_replace("{{SearchPort}}", $SearchPort, $ConfigBuffer);
	$ConfigBuffer  = str_replace("{{EnableMemcache}}", $EnableMemcache, $ConfigBuffer);
	$ConfigBuffer  = str_replace("{{MemCachePrefix}}", $MemCachePrefix, $ConfigBuffer);
	
	fclose($ConfigPointer);
	$ConfigPHP = fopen('../config.php', "w+");
	fwrite($ConfigPHP, $ConfigBuffer);
	fclose($ConfigPHP);
	//rewrite文件配置
	//写入htaccess文件
	$HtaccessPointer = fopen('../install/htaccess.tpl', 'r');
	$HtaccessBuffer  = fread($HtaccessPointer, filesize('../install/htaccess.tpl'));
	$HtaccessBuffer  = str_replace("{{WebSitePath}}", $WebsitePath, $HtaccessBuffer);
	//Server Software Type
	if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { //IIS(ISAPI_Rewrite)
		$HtaccessBuffer = str_replace("{{RedirectionType}}", "[QSA,NU,PT,L]", $HtaccessBuffer);
	} else { //Others
		$HtaccessBuffer = str_replace("{{RedirectionType}}", "[L]", $HtaccessBuffer);
	}
	fclose($HtaccessPointer);
	$Htaccess = fopen("../.htaccess", "w+");
	fwrite($Htaccess, $HtaccessBuffer);
	fclose($Htaccess);
	
	//当前版本低于3.3.0，需要进行的升级到3.3.0的升级操作
	if (VersionCompare('3.3.0', $OldVersion)) {
		require("../library/MaterialDesign.Avatars.class.php");
		$UserIDArray = $DB->query('SELECT UserName, ID FROM ' . DATABASE_PREFIX . 'users');
		foreach ($UserIDArray as $UserInfo) {
			if (!is_file('../upload/avatar/small/' . $UserInfo['ID'] . '.png')) {
				//echo $UserInfo['UserName'].'<br />';
				if (extension_loaded('gd')) {
					$Avatar = new MDAvtars(mb_substr($UserInfo['UserName'], 0, 1, "UTF-8"), 256);
					$Avatar->Save('../upload/avatar/large/' . $UserInfo['ID'] . '.png', 256);
					$Avatar->Save('../upload/avatar/middle/' . $UserInfo['ID'] . '.png', 48);
					$Avatar->Save('../upload/avatar/small/' . $UserInfo['ID'] . '.png', 24);
				}
			}
		}
	}

	//当前版本低于3.5.0，需要进行的升级到3.5.0的升级操作
	if (VersionCompare('3.5.0', $OldVersion)) {
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('PushConnectionTimeoutPeriod', '22')");
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('SMTPHost', 'smtp1.example.com')");
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('SMTPPort', '587')");
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('SMTPAuth', 'true')");
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('SMTPUsername', 'user@example.com')");
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('SMTPPassword', 'secret')");
		
		$DB->query("DROP TABLE IF EXISTS `" . DATABASE_PREFIX . "app_user`");
		$DB->query("CREATE TABLE `" . DATABASE_PREFIX . "app_users` (
					  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `AppID` int(10) unsigned NOT NULL,
					  `OpenID` varchar(64) NOT NULL,
					  `AppUserName` varchar(50) CHARACTER SET utf8,
					  `UserID` int(10) unsigned NOT NULL,
					  `Time` int(10) unsigned NOT NULL,
					  PRIMARY KEY (`ID`),
					  KEY `Index` (`AppID`,`OpenID`),
					  KEY `UserID` (`UserID`)
					) DEFAULT CHARSET=utf8;");
	}

	//当前版本低于3.6.0，需要进行的升级到3.6.0的升级操作
	if (VersionCompare('3.6.0', $OldVersion)) {
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "tags` CHANGE `IsEnabled` `IsEnabled` TINYINT(1) UNSIGNED NULL DEFAULT '1'");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "tags` ADD INDEX `TotalPosts` (`IsEnabled`, `TotalPosts`)");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "tags` CHANGE `Description` `Description` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('CacheHotTags', '')");
	}

	//当前版本低于5.9.0，需要进行的升级到5.9.0的升级操作
	if (VersionCompare('5.9.0', $OldVersion)) {
		if (!empty($DB->query("SHOW COLUMNS FROM `" . DATABASE_PREFIX . "users` LIKE 'NewNotification'"))) {
			$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "users` DROP COLUMN `NewNotification`;");

		}
		if (!empty($DB->query("SHOW COLUMNS FROM `" . DATABASE_PREFIX . "users` LIKE 'NewMention'"))) {
			$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "users` DROP COLUMN `NewMention`;");

		}
		if (!empty($DB->query("SHOW COLUMNS FROM `" . DATABASE_PREFIX . "users` LIKE 'NewReply'"))) {
			$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "users` DROP COLUMN `NewReply`;");

		}
		$DB->query("ALTER TABLE " . DATABASE_PREFIX . "users ADD COLUMN `NewMention` INT (10) UNSIGNED NOT NULL DEFAULT 0 AFTER `NumFavTopics`;");
		$DB->query("ALTER TABLE " . DATABASE_PREFIX . "users ADD COLUMN `NewReply` INT (10) UNSIGNED NOT NULL DEFAULT 0 AFTER `NumFavTopics`;");
		$DB->query("UPDATE " . DATABASE_PREFIX . "users SET NewReply = NewMessage;");
		$DB->query("UPDATE " . DATABASE_PREFIX . "users SET NewMessage = 0;");
		$DB->query("DROP TABLE IF EXISTS `" . DATABASE_PREFIX . "messages`;");
		$DB->query("CREATE TABLE `" . DATABASE_PREFIX . "messages` (
			  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `InboxID` int(10) NOT NULL DEFAULT '0',
			  `UserID` int(10) NOT NULL DEFAULT '0',
			  `Content` longtext NOT NULL,
			  `Time` int(10) unsigned NOT NULL,
			  `IsDel` tinyint(3) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`ID`),
			  KEY `Index` (`IsDel`,`InboxID`,`Time`) USING BTREE
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

		$DB->query("DROP TABLE IF EXISTS `" . DATABASE_PREFIX . "inbox`;");
		$DB->query("CREATE TABLE `" . DATABASE_PREFIX . "inbox` (
			`ID` int(10) NOT NULL AUTO_INCREMENT,
			`SenderID` int(10) NOT NULL,
			`SenderName` varchar(50) NOT NULL,
			`ReceiverID` int(10) NOT NULL,
			`ReceiverName` varchar(50) NOT NULL,
			`LastContent` varchar(255) NOT NULL DEFAULT '',
			`LastTime` int(10) NOT NULL DEFAULT '0',
			`IsDel` tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`ID`),
			KEY `DialogueID` (`LastTime`) USING BTREE,
			KEY `SenderID` (`SenderID`,`ReceiverID`),
			KEY `ReceiverID` (`ReceiverID`,`SenderID`)
			) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");

		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "app` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "app_users` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "blogs` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "blogsettings` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "config` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "dict` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "favorites` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "link` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "log` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "notifications` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "pictures` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "postrating` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "posts` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "posttags` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "roles` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "statistics` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "tags` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "topics` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "upload` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "users` ENGINE=InnoDB;");
		$DB->query("ALTER TABLE `" . DATABASE_PREFIX . "vote` ENGINE=InnoDB;");

		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('AllowEditing', 'true');");
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('AllowEmptyTags', 'false');");
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('AllowNewTopic', 'true');");
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('CloseRegistration', 'false');");
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('FreezingTime', '0');");
		$DB->query("INSERT INTO `" . DATABASE_PREFIX . "config` VALUES ('PostingInterval', '8');");
	}
	$Message = '升级成功。<br />Update successfully! ';
	//版本修改
	$DB->query("UPDATE `" . DATABASE_PREFIX . "config` SET `ConfigValue`='" . $Version . "' WHERE `ConfigName`='Version'");
	//关闭数据库连接
	$DB->CloseConnection();
	if (!file_exists('update.lock')) {
		touch('update.lock');
	}
	if (file_exists('../install/install.lock')) {
		touch("../install/install.lock");
	}
} else {
	if (version_compare(PHP_VERSION, '5.4.0') < 0) {
		$Message = '你的PHP版本过低，可能会无法正常使用！<br />Your PHP version is too low, it may not work properly!';
	}
	if (!extension_loaded('pdo_mysql')) {
		$Message = '你的PHP未编译pdo_mysql，本程序无法正常工作<br />Your PHP don’t support pdo_mysql extension, this program does not work! ';
	}
	if (!extension_loaded('mbstring')) {
		$Message = '你的PHP未编译mbstring，本程序无法正常工作<br />Your PHP don’t support mbstring extension, this program does not work! ';
	}
	if (!extension_loaded('curl')) {
		$Message = '你的PHP未编译curl，本程序无法正常工作<br />Your PHP don’t support curl extension, this program does not work! ';
	}
	if (!extension_loaded('gd')) {
		$Message = '你的PHP未编译gd，本程序无法正常工作<br />Your PHP don’t support gd extension, this program does not work! ';
	}
	if (!extension_loaded('dom')) {
		$Message = 'dom，本程序无法正常工作<br />Your PHP don’t support dom extension, this program does not work! ';
	}
	if (is_file('../config.php')) {
		require("../config.php");
	} else {
		define('ForumLanguage', 'zh-cn');
		define('DBHost', 'localhost');
		define('DBName', 'carbon');
		define('DBUser', 'root');
		//define('DBPassword', '');
	}
}

function VersionCompare($Version, $OldVersion)
{
	$VersionArray = array_map("intval", explode('.', $Version));
	;
	$OldVersionArray = array_map("intval", explode('.', $OldVersion));
	$NeedToUpdate    = false;
	foreach ($VersionArray as $Key => $Value) {
		if ($VersionArray[$Key] != $OldVersionArray[$Key]) {
			if ($VersionArray[$Key] > $OldVersionArray[$Key]) {
				$NeedToUpdate = true;
			}
			break;
		}
	}
	return $NeedToUpdate;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-cmn-Hans" lang="zh-cmn-Hans">
<head>
<meta charset="UTF-8" />
<meta content="True" name="HandheldFriendly" />
<title>Update - Carbon Forum</title>
<link href="../static/css/default/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<!-- content wrapper start -->
	<div class="wrapper">
		<!-- main start -->
		<div class="main">
			<!-- main-content start -->
<div class="main-content">
	<div class="title">
		Carbon Forum &raquo; 升级&nbsp;&nbsp;/&nbsp;&nbsp;Update
	</div>
	<div class="main-box">
			<form action="?" method="post">
			<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
				<tbody>
					<tr>
						<td width="auto" align="center" colspan="2"><span class="red"><?php
echo $Message;
?></span></td>
					</tr>
					<?php
if (!$Message) {
?>
					<tr>
						<td width="280" align="right">安装语言&nbsp;&nbsp;/&nbsp;&nbsp;Language</td>
						<td width="auto" align="left">
							<select name="Language">
								<option value="<?php
	echo ForumLanguage;
?>">Current Language: <?php
	echo ForumLanguage;
?></option>
								<option value="zh-cn">简体中文</option>
								<option value="zh-tw">繁體中文</option>
								<option value="en">English</option>
								<option value="ru">Русский</option>
								<option value="pl">polski</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="280" align="right">数据库地址&nbsp;&nbsp;/&nbsp;&nbsp;Database Host</td>
						<td width="auto" align="left"><input type="text" name="DBHost" class="sl w200" value="<?php
	echo DBHost;
?>" /></td>
					</tr>
					<tr>
						<td width="280" align="right">数据库名&nbsp;&nbsp;/&nbsp;&nbsp;Database Name</td>
						<td width="auto" align="left"><input type="text" name="DBName" class="sl w200" value="<?php
	echo DBName;
?>" /></td>
					</tr>
					<tr>
						<td width="280" align="right">数据库登陆账号&nbsp;&nbsp;/&nbsp;&nbsp;Database Account</td>
						<td width="auto" align="left"><input type="text" name="DBUser" class="sl w200" value="<?php
	echo DBUser;
?>" /></td>
					</tr>
					<tr>
						<td width="280" align="right">数据库密码&nbsp;&nbsp;/&nbsp;&nbsp;Database Password</td>
						<td width="auto" align="left"><input type="password" name="DBPassword" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td colspan="2" class="title">高级选项（可不填） / Advanced Settings (Optional)</td>
						
					</tr>
					<tr>
						<td width="280" align="right">Sphinx搜索服务器&nbsp;&nbsp;/&nbsp;&nbsp;Sphinx Search Server</td>
						<td width="auto" align="left"><input type="text" name="SearchServer" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td width="280" align="right">Sphinx搜索端口&nbsp;&nbsp;/&nbsp;&nbsp;Sphinx Search Port</td>
						<td width="auto" align="left"><input type="text" name="SearchPort" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td width="280" align="right">打开缓存&nbsp;&nbsp;/&nbsp;&nbsp;Enable Cache<br />(Memcached / Redis / XCache)</td>
						<td width="auto" align="left">
							<select name="EnableMemcache">
								<option value="false">关闭 / False</option>
								<option value="true">打开 / True</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="280" align="right">缓存前缀&nbsp;&nbsp;/&nbsp;&nbsp;Cache Prefix</td>
						<td width="auto" align="left"><input type="text" name="MemCachePrefix" class="sl w200" value="carbon_" /></td>
					</tr>
					<tr>
						<td width="280" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="升 级 / Update" name="submit" class="textbtn" /></td>
					</tr>
					<?php
}
?>
				</tbody>
			</table>
		</form>
	</div>
</div>
	<!-- main-content end -->
	<div class="main-sider">
		<div class="sider-box">
			<div class="sider-box-title">升级说明</div>
			<div class="sider-box-content">
				<p class="red">
				请一定要先备份好你的数据库和upload文件夹！！！<br />
				如果没有备份，请不要继续进行升级操作。
				</p>
				<p class="red">
				Please be sure to back up your database and upload folders! ! !<br />
				If there is no backup, please do not proceed with the upgrade operation! ! !
				</p>
				<p>
				如果出现“Access denied”错误说明填写不正确，请返回重新填写。
				</p>
				<p>
				如果你使用的是性能极差的虚拟主机，你可以在本地电脑完成升级，再上传至服务器（注意本地电脑运行环境要与服务器一致）。
				</p>
			</div>
		</div>
	</div>
		<div class="c"></div>
		</div>
		<!-- main end -->
		<div class="c"></div>

		<!-- footer start -->
		<div class="Copyright">
			<p>
			Powered By <a href="https://www.94cb.com" target="_blank">Carbon Forum <?php
echo $Version;
?></a> © 2006-2016
			</p>
		</div>
		<!-- footer end -->

	</div>
	<!-- content wrapper end -->
</body>
</html>