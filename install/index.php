<?php
set_time_limit(0);
date_default_timezone_set('Asia/Shanghai');//设置中国时区
$Message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$fp = fopen(dirname(__FILE__).'/database.sql', "r") or die("不能打开SQL文件");
	$Language = $_POST['Language'];
	$DBHost = $_POST['DBHost'];
	$DBName = $_POST['DBName'];
	$DBUser = $_POST['DBUser'];
	$DBPassword = $_POST['DBPassword'];
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
	$DB->query("INSERT INTO `carbon_config` VALUES ('WebsitePath', '".$WebsitePath."')");
	$DB->query("INSERT INTO `carbon_config` VALUES ('LoadJqueryUrl', '".$WebsitePath."/static/js/jquery.js')");
	$DB->CloseConnection();
	fclose($fp) or die("Can’t close file");

	//写入config文件
	$ConfigPointer=fopen(dirname(__FILE__).'/config.tpl','r');
	$ConfigBuffer=fread($ConfigPointer, filesize(dirname(__FILE__).'/config.tpl'));
	$ConfigBuffer = str_replace("{{Language}}",$Language,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{DBHost}}",$DBHost,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{DBName}}",$DBName,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{DBUser}}",$DBUser,$ConfigBuffer);
	$ConfigBuffer = str_replace("{{DBPassword}}",$DBPassword,$ConfigBuffer);
	fclose($ConfigPointer);
	$ConfigPHP = fopen("../config.php","w+");       
	fwrite($ConfigPHP,$ConfigBuffer);
	fclose($ConfigPHP);

	//写入htaccess文件
	$HtaccessPointer=fopen(dirname(__FILE__).'/htaccess.tpl','r');
	$HtaccessBuffer=fread($HtaccessPointer, filesize(dirname(__FILE__).'/htaccess.tpl'));
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
	$Message = '安装成功，安装完成后请马上删除install和update文件夹。<br />安裝成功，安裝完成後請立即刪除install與update資料夾。<br />Please delete the install folder and the update folder. <br /><a href="../register">点我马上注册管理员账号。<br />點我立即註冊管理員帳號。<br />The first registered users will become administrators.</a>';

	//安全起见，修改为不可执行文件
	if (file_exists('index.php')) {  
		rename ("index.php", "index.txt");
	}
	if (file_exists('../update/index.php')) {  
		rename ("../update/index.php", "../update/index.txt");
	}
	
}else{
	if (version_compare(PHP_VERSION, '5.3.0') < 0) {
		$Message = '你的PHP版本过低，可能会无法正常使用！<br />您的PHP版本太低，可能會無法正常使用！<br />Your PHP version is too low, it may not work properly!';
	}
	if (! extension_loaded('pdo_mysql')) {
		$Message = '你的PHP未编译pdo_mysql，本程序无法正常工作！<br />你的PHP不支援pdo_mysql擴充套件，此程式無法正常運作！<br />Your PHP don’t support pdo_mysql extension, this program does not work! ';
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
		Carbon Forum &raquo; 安装&nbsp;&nbsp;/&nbsp;&nbsp;安裝&nbsp;&nbsp;/&nbsp;&nbsp;Install
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
						<td width="280" align="right">安装语言&nbsp;&nbsp;/&nbsp;&nbsp;安裝語言&nbsp;&nbsp;/&nbsp;&nbsp;Language</td>
						<td width="auto" align="left">
							<select name="Language">
								<option value="zh-cn">简体中文（中国）</option>
								<option value="zh-tw">繁體中文（台灣）</option>
								<option value="en">English</option>
								<option value="pl">Polski</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="280" align="right">数据库地址&nbsp;&nbsp;/&nbsp;&nbsp;資料庫主機的位址&nbsp;&nbsp;/&nbsp;&nbsp;Database Host</td>
						<td width="auto" align="left"><input type="text" name="DBHost" class="sl w200" value="127.0.0.1" /></td>
					</tr>
					<tr>
						<td width="280" align="right">数据库名&nbsp;&nbsp;/&nbsp;&nbsp;資料庫名稱&nbsp;&nbsp;/&nbsp;&nbsp;Database Name</td>
						<td width="auto" align="left"><input type="text" name="DBName" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td width="280" align="right">数据库登陆账号&nbsp;&nbsp;/&nbsp;&nbsp;資料庫登入帳號&nbsp;&nbsp;/&nbsp;&nbsp;Database Account</td>
						<td width="auto" align="left"><input type="text" name="DBUser" class="sl w200" value="root" /></td>
					</tr>
					<tr>
						<td width="280" align="right">数据库密码&nbsp;&nbsp;/&nbsp;&nbsp;資料庫密碼&nbsp;&nbsp;/&nbsp;&nbsp;Database Password</td>
						<td width="auto" align="left"><input type="password" name="DBPassword" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td width="280" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="安 装 / 安裝 / Install " name="submit" class="textbtn" /></td>
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
			<div class="sider-box-title">安装说明 / 安裝提示</div>
			<div class="sider-box-content">
				<p>
				如果出现“Access denied”错误，说明填写不正确，请返回重新填写。<br />若出現Access denied錯誤，表明輸入有誤，請回到上一頁重新輸入。
				</p>
				<p>
				安装完毕后，第一个注册的用户将会自动成为管理员。<br />安裝完成後，第一位註冊的使用者將會自動成爲管理員。
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
