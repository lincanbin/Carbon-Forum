<?php
set_time_limit(0);
date_default_timezone_set('Asia/Shanghai');//设置中国时区
$Message = '';
$Version = '3.6.5';
$Prefix = 'carbon_';
if(is_file('install.lock')){
	die("请删除 install/install.lock 文件后再进行操作！<br>Please Remove install/install.lock before install!");
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$fp = fopen(__DIR__.'/database.sql', "r") or die("不能打开SQL文件");
	$Language = $_POST['Language'];
	$DBHost = $_POST['DBHost'];
	$DBName = $_POST['DBName'];
	$DBUser = $_POST['DBUser'];
	$DBPassword = $_POST['DBPassword'];
	$SearchServer = $_POST['SearchServer'];
	$SearchPort   = $_POST['SearchPort'];
	$EnableMemcache   = $_POST['EnableMemcache'];
	$MemCachePrefix   = $_POST['MemCachePrefix'];
	//$WebsitePath = $_POST['WebsitePath'];
	$WebsitePath = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	if(preg_match('/(.*)\/install/i', $WebsitePath , $WebsitePathMatch))
	{
		$WebsitePath = $WebsitePathMatch[1];
	}else{
		$WebsitePath = '';
	}
	//初始化数据库操作类
	require('../includes/PDO.class.php');
	$DB = new Db($DBHost, $DBName, $DBUser, $DBPassword);
	//数据库安装
	while($SQL=GetNextSQL()){
		$DB->query($SQL);
	}
	$DB->query("INSERT INTO `".$Prefix."config` VALUES ('WebsitePath', '".$WebsitePath."')");
	$DB->query("INSERT INTO `".$Prefix."config` VALUES ('LoadJqueryUrl', '".$WebsitePath."/static/js/jquery.js')");
	$DB->query("UPDATE `".$Prefix."config` SET `ConfigValue`='".date('Y-m-d')."' WHERE `ConfigName`='DaysDate'");
	$DB->query("UPDATE `".$Prefix."config` SET `ConfigValue`='".$Version."' WHERE `ConfigName`='Version'");
	$DB->CloseConnection();
	fclose($fp) or die("Can’t close file");

	//写入config文件
	$ConfigPointer=fopen(__DIR__.'/config.tpl','r');
	$ConfigBuffer=fread($ConfigPointer, filesize(__DIR__.'/config.tpl'));
	$ConfigBuffer = str_replace("{{Language}}",$Language,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{DBHost}}",$DBHost,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{DBName}}",$DBName,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{DBUser}}",$DBUser,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{DBPassword}}",$DBPassword,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{SearchServer}}",$SearchServer,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{SearchPort}}",$SearchPort,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{EnableMemcache}}",$EnableMemcache,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{MemCachePrefix}}",$MemCachePrefix,$ConfigBuffer);

	fclose($ConfigPointer);
	$ConfigPHP = fopen("../config.php","w+");       
	fwrite($ConfigPHP,$ConfigBuffer);
	fclose($ConfigPHP);

	//写入htaccess文件
	$HtaccessPointer=fopen(__DIR__.'/htaccess.tpl','r');
	$HtaccessBuffer=fread($HtaccessPointer, filesize(__DIR__.'/htaccess.tpl'));
	$HtaccessBuffer = str_replace("{{WebSitePath}}",$WebsitePath,$HtaccessBuffer);
	//Server Software Type
	if(isset($_SERVER['HTTP_X_REWRITE_URL'])){//IIS(ISAPI_Rewrite)
		$HtaccessBuffer = str_replace("{{RedirectionType}}","[QSA,NU,PT,L]",$HtaccessBuffer);
	}else{//Others
		$HtaccessBuffer = str_replace("{{RedirectionType}}","[L]",$HtaccessBuffer);
	}
	fclose($HtaccessPointer);
	$Htaccess = fopen("../.htaccess","w+");       
	fwrite($Htaccess, $HtaccessBuffer );
	fclose($Htaccess);

	//rewrite文件配置
	$Message = '安装成功。<br />Installed successfully! <br /><a href="../register">点我马上注册管理员账号<br />The first registered users will become administrators.</a>';

	if (!file_exists('install.lock')) {  
		touch('install.lock');
	}
	if (!file_exists('../update/update.lock')) {  
		touch("../update/update.lock");
	}
	
}else{
	if (version_compare(PHP_VERSION, '5.3.0') < 0) {
		$Message = '你的PHP版本过低，可能会无法正常使用！<br />Your PHP version is too low, it may not work properly!';
	}
	if (! extension_loaded('pdo_mysql')) {
		$Message = '你的PHP未编译pdo_mysql，本程序无法正常工作<br />Your PHP don’t support pdo_mysql extension, this program does not work! ';
	}
}
//从文件中逐条取SQL
function GetNextSQL() {
	global $fp;
	$sql="";
	while ($line = fgets($fp, 40960)) {
		$line = trim($line);
		//以下三句在高版本php中不需要，在部分低版本中也许需要修改
		/*
		$line = str_replace("////","//",$line);
		$line = str_replace("/’","’",$line);
		$line = str_replace("//r//n",chr(13).chr(10),$line);
		*/
		//$line = stripcslashes($line);
		if (strlen($line)>1) {
			if ($line[0]=="-" && $line[1]=="-") {
				continue;
			}
		}
		$sql.=$line.chr(13).chr(10);
		if (strlen($line)>0){
			if ($line[strlen($line)-1]==";"){
				break;
			}
		}
	}
	return $sql;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head>
	<meta charset="UTF-8" />
	<meta content="True" name="HandheldFriendly" />
	<title>Install - Carbon Forum</title>
	<link href="../styles/default/theme/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<!-- content wrapper start -->
	<div class="wrapper">
		<!-- main start -->
		<div class="main">
			<!-- main-content start -->
			<div class="main-content">
				<div class="title">
					Carbon Forum &raquo; 安装&nbsp;&nbsp;/&nbsp;&nbsp;Install
				</div>
				<div class="main-box">
					<form action="?" method="post">
						<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
							<tbody>
								<tr>
									<td width="auto" align="center" colspan="2"><span class="red"><?php echo $Message; ?></span></td>
								</tr>
								<?php if(!$Message) {?>
								<tr>
									<td width="280" align="right">安装语言&nbsp;&nbsp;/&nbsp;&nbsp;Language</td>
									<td width="auto" align="left">
										<select name="Language">
											<option value="zh-cn">简体中文</option>
											<option value="zh-tw">繁體中文</option>
											<option value="en">English</option>
											<option value="pl">polski</option>
										</select>
									</td>
								</tr>
								<tr>
									<td width="280" align="right">数据库地址&nbsp;&nbsp;/&nbsp;&nbsp;Database Host</td>
									<td width="auto" align="left"><input type="text" name="DBHost" class="sl w200" value="localhost" /></td>
								</tr>
								<tr>
									<td width="280" align="right">数据库名&nbsp;&nbsp;/&nbsp;&nbsp;Database Name</td>
									<td width="auto" align="left"><input type="text" name="DBName" class="sl w200" value="" /></td>
								</tr>
								<tr>
									<td width="280" align="right">数据库登陆账号&nbsp;&nbsp;/&nbsp;&nbsp;Database Account</td>
									<td width="auto" align="left"><input type="text" name="DBUser" class="sl w200" value="root" /></td>
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
									<td width="auto" align="left"><input type="submit" value="安 装 / Install " name="submit" class="textbtn" /></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</form>
				</div>
			</div>
			<!-- main-content end -->
			<div class="main-sider">
				<div class="sider-box">
					<div class="sider-box-title">安装说明</div>
					<div class="sider-box-content">
						<p>
							如果出现“Access denied”错误，说明填写不正确，请返回重新填写。
						</p>
						<p>
							安装完毕后，第一个注册的用户将会自动成为管理员。
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
				Power By <a href="http://www.94cb.com" target="_blank">Carbon Forum</a> © 2006-2015
			</p>
		</div>
		<!-- footer end -->

	</div>
	<!-- content wrapper end -->
</body>
</html>
