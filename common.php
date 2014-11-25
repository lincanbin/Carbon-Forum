<?php
/*
 * Carbon-Forum v 3.2.0
 * https://github.com/lincanbin/Carbon-Forum
 *
 * Copyright 2014, Lin Canbin
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A high performance open-source forum software written in PHP. 
 */

//逐渐替换为帕斯卡命名法
//数据库从设计上避免使用Join多表联查

//error_reporting(0);//不输出任何错误信息
//error_reporting(E_ALL ^ E_NOTICE);//除了 E_NOTICE，报告其他所有错误
error_reporting(E_ALL);//输出所有错误信息，调试用
ini_set('display_errors', '1');//显示错误
//开始计时，初始化常量、常量
$mtime     = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];
$TimeStamp = time();
require(dirname(__FILE__)."/config.php");
require(dirname(__FILE__).'/language/'.ForumLanguage.'/common.php');
//初始化数据库操作类
require(dirname(__FILE__)."/includes/PDO.class.php");
$DB = new Db(DBHost, DBName, DBUser, DBPassword);
//加载网站设置

$Config=array();
foreach($DB->query('SELECT ConfigName,ConfigValue FROM '.$Prefix.'config') as $ConfigArray)
{
	$Config[$ConfigArray['ConfigName']] = $ConfigArray['ConfigValue'];
}

$PHPSelf  = addslashes(htmlspecialchars($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']));
$UrlPath  = $Config['WebsitePath'] ? str_ireplace($Config['WebsitePath'].'/', '', substr($PHPSelf, 0, -4)):substr($PHPSelf, 1, -4);

//消除低版本中魔术引号的影响
if (get_magic_quotes_gpc()) {
	function StripslashesDeep($var) {
		return is_array($var) ? array_map('StripslashesDeep', $var) : stripslashes($var);
	}
	$_GET = StripslashesDeep($_GET);
	$_POST = StripslashesDeep($_POST);
	$_COOKIE = StripslashesDeep($_COOKIE);
	$_REQUEST = StripslashesDeep($_REQUEST);
}

// At某人并提醒他，使用时常在其前后加空格或回车，如 “@admin ”
function AddingNotifications($Content, $TopicID, $PostID, $FilterUser='')
{
	/*
	Type:
	1:新回复
	2:@ 到我的
	*/
	global $Prefix, $DB, $TimeStamp, $CurUserName;
	//例外列表
	$ExceptionUser = array($CurUserName);
	if ($FilterUser != $CurUserName)
	{
		$ExceptionUser[] = $FilterUser;
	}
	// 正则跟用户注册、登录保持一致
	preg_match_all('/\B\@([a-zA-Z0-9\x80-\xff\-_.]{4,20})/', $Content, $out, PREG_PATTERN_ORDER);
	$TemporaryUserList = array_unique($out[1]);//排重
	$TemporaryUserList = array_diff($TemporaryUserList, $ExceptionUser);
	if($TemporaryUserList)
	{
		$UserList = $DB->row('SELECT ID FROM `'.$Prefix.'users` WHERE `UserName` in (?)', $TemporaryUserList);
		if($UserList && count($UserList) <= 20)
		//最多@ 20人，防止骚扰
		{
			foreach ($UserList as $UserID) {
				$DB->query('INSERT INTO `'.$Prefix.'notifications`(`ID`,`UserID`, `UserName`, `Type`, `TopicID`, `PostID`, `Time`, `IsRead`) VALUES (null,?,?,?,?,?,?,?)',array($UserID, $CurUserName, 2, $TopicID, $PostID, $TimeStamp, 0));
				$DB->query('UPDATE `'.$Prefix.'users` SET `NewMessage` = NewMessage+1 WHERE ID = :UserID',array('UserID' => $UserID));
			}
		}
	}
}


//提示信息
function AlertMsg($PageTitle, $error, $status_code=200)
{
	global $Lang, $UrlPath, $IsMobie, $IsApp, $Prefix, $DB, $Config, $CurUserID, $CurUserName, $CurUserCode,$CurUserRole,$CurUserInfo, $FormHash, $starttime, $PageMetaKeyword, $TemplatePath;
	$errors   = array();
	if(!$IsApp){
		switch($status_code)
		{
			case 404:
				header("HTTP/1.1 404 Not Found");
				header("Status: 404 Not Found");
				break;
			case 401:
				header("HTTP/1.1 401 Unauthorized");
				header("Status: 401 Unauthorized");
				break;
			case 403:
				header("HTTP/1.1 403 Forbidden");
				header("Status: 403 Forbidden");
				break;
			case 400:
				header("HTTP/1.1 400 Bad Request");
				header("Status: 400 Bad Request");
				break;
			case 500:
				header("HTTP/1.1 500 Internal Server Error");
				header("Status: 500 Internal Server Error");
				break;
			case 503:
				header("HTTP/1.1 503 Service Unavailable");
				header("Status: 503 Service Unavailable");
				break;
			default:
				break;
		}
	}
	$ContentFile = $TemplatePath.'alert.php';
	include($TemplatePath.'layout.php');
	exit;
}


//获取数组中的某一列
function ArrayColumn($Input, $ColumnKey)
{
	if (version_compare(PHP_VERSION, '5.5.0') < 0) {
		$Result = array();
		if($Input)
		{
			foreach ($Input as $Value) {
				$Result[] = $Value[$ColumnKey];
			}
		}
		return $Result;
	}else{
		return array_column($Input, $ColumnKey);
	}
}


//鉴权
function Auth($MinRoleRequire, $AuthorizedUserID=0, $StatusRequire=false)
{
	global $CurUserRole, $CurUserID, $CurUserInfo, $Lang;
	$error = '';
	if ($CurUserRole < $MinRoleRequire)
	{
		$error = str_replace('{{RoleDict}}', $Lang['RolesDict'][$MinRoleRequire], $Lang['Error_Insufficient_Permissions']);
	}
	if($CurUserID && $StatusRequire==true && $CurUserInfo['UserAccountStatus'] == 0){
		$error = $Lang['Error_Account_navailable'];
	}
	if($AuthorizedUserID && $CurUserID && $CurUserID == $AuthorizedUserID)
	{
		$error = false;
	}
	if ($error) {
		AlertMsg($Lang['Error_Message'], $error, 401);
	}
}


//转换字符
function CharCV($string)
{
	$string = htmlspecialchars(trim($string));
	return $string;
}


// 过滤掉一些非法字符
function CharsFilter($String)
{
	$String = str_replace("<", "", $String);
	$String = str_replace(">", "", $String);
	return trim($String);
}


// 获得IP地址
function CurIP()
{
	$IP = false;
	if(!empty($_SERVER["HTTP_CLIENT_IP"]))
	{
		$IP = trim($_SERVER["HTTP_CLIENT_IP"]);
	}
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		$IPs = explode (",", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if ($IP)
		{
			array_unshift($IPs, $IP);
			$IP = FALSE;
		}
		//支持使用CDN后获取IP，理论上令 $IP = $IPs[0]; 即可，安全起见遍历过滤一次
		for ($i = 0; $i < count($IPs); $i++)
		{
			if (!preg_match("/^(10|172.16|172.17|172.18|172.19|172.20|172.21|172.22|172.23|172.24|172.25|172.26|172.27|172.28|172.29|172.30|172.31|192.168)/i", trim($IPs[$i])))
			{
				$IP = trim($IPs[$i]);
				break;
			}
		}
	}
	return ($IP ? $IP : $_SERVER['REMOTE_ADDR']);
}


// 获得表单校验散列
function FormHash()
{
	global $Config, $SALT;
	if(array_key_exists('UserCode', $_COOKIE))
		return substr(md5($Config['SiteName'] . $_COOKIE['UserCode'] . $SALT), 8, 8);
	else
		return substr(md5($Config['SiteName'] . $SALT), 8, 8);
}


//格式化文件大小
function FormatBytes($size, $precision = 2)
{
	$units = array(
		' B',
		' KB',
		' MB',
		' GB',
		' TB'
	);
	for ($i = 0; $size >= 1024 && $i < 4; $i++)
		$size /= 1024;
	return round($size, $precision) . $units[$i];
}


// 格式化时间
function FormatTime($UnixTimeStamp)
{
	global $Lang;
	$Seconds = time() - $UnixTimeStamp;
	if ($Seconds < 2592000) {
		// 小于30天如下显示
		if ($Seconds >= 86400) {
			return round($Seconds / 86400, 0) . '&nbsp;'.$Lang['Time_Days_Ago'];
		} else if ($Seconds >= 3600) {
			return round($Seconds / 3600, 0) . '&nbsp;'.$Lang['Time_Hours_Ago'];
		} else if ($Seconds >= 60) {
			return round($Seconds / 60, 0) . '&nbsp;'.$Lang['Time_Minutes_Ago'];
		} else if ($Seconds < 0) {
			return $Lang['Time_Just_Now'];
		} else {
			return ($Seconds + 1) . '&nbsp;'.$Lang['Time_Seconds_Ago'];
		}
	} else {
		// 大于一月
		return date("Y-m-d", $UnixTimeStamp);
		//gmdate()可以返回格林威治标准时，date()则为当地时
	}
}


//获取头像
function GetAvatar($UserID, $UserName, $Size='middle')
{
	global $Config;
	return '<img src="'.$Config['WebsitePath'].'/upload/avatar/'.$Size.'/'.$UserID.'.png" alt="'.$UserName.'"/>';
}


//获取Cookie
function GetCookie($Key,$DefaultValue=false)
{
	global $Config;
	if(array_key_exists($Config['CookiePrefix'].$Key, $_COOKIE))
		return $_COOKIE[$Config['CookiePrefix'].$Key];
	else
		if($DefaultValue)
		{
			SetCookies(array($Key => $DefaultValue));
			return $DefaultValue;
		}else
			return false;
}


//长整数intval，防止溢出
function int($s)
{
	return($a=preg_replace('/[^\-\d]*(\-?\d*).*/','$1',$s))?$a:'0';
}


//判断是否为邮件地址
function IsEmail($email)
{
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}


//判断是否为合法用户名
function IsName($string)
{
	return !preg_match('/^[0-9]{4,20}$/', $string) && preg_match('/^[a-zA-Z0-9\x80-\xff\-_.]{4,20}$/i', $string);
}

//只有上一页下一页的分页
function PaginationSimplified($PageUrl,$PageCount,$IsLastPage)
{
	global $Config,$Lang;
	$PageUrl = $Config['WebsitePath'].$PageUrl;
	if ($PageCount != 1)
		echo '<a href="'.$PageUrl.($PageCount-1).'">&lsaquo;&lsaquo;'.$Lang['Page_Previous'].'</a>';
	echo '<span id=pagenum><span class="currentpage">'.$PageCount.'</span>';
	if (!$IsLastPage)
		echo '<a href="'.$PageUrl.($PageCount+1).'">'.$Lang['Page_Next'].'&rsaquo;&rsaquo;</a>';
	echo '</span>';
}


//分页
function Pagination($PageUrl,$PageCount,$TotalPage)
{
	if($TotalPage<=1)
		return false;
	global $Config, $Lang;
	$PageUrl = $Config['WebsitePath'].$PageUrl;
	$PageLast=$PageCount-1;
	$PageNext=$PageCount+1;

	echo '<span id=pagenum><span class="currentpage">'.$PageCount.'/'.$TotalPage.'</span>';
	if ($PageCount != 1)
		echo '<a href="'.$PageUrl.$PageLast.'">&lsaquo;&lsaquo;'.$Lang['Page_Previous'].'</a>';
	if (($PageCount-6) > 1)
		echo '<a href="'.$PageUrl.'1">1</a>';
	if (($PageCount-5) <= 1)
	{
		$PageiStart=1;
	}
	else if ($PageCount >= ($TotalPage-5))
	{
		$PageiStart=$TotalPage-9;
	}else{
		$PageiStart=$PageCount-5;
	}

	if ($PageCount+5 >= $TotalPage)
	{
		$PageiEnd=$TotalPage;
	}else if ($PageCount<=5 && $TotalPage>=10)
	{
		$PageiEnd=10;
	}else{
		$PageiEnd=$PageCount+5;
	}
	for($Pagei=$PageiStart;$Pagei<=$PageiEnd;$Pagei++)
	{
		if ($PageCount==$Pagei)
		{
		echo '<span class="currentpage">'.$Pagei.'</span>';
		}
		elseif ($Pagei > 0 && $Pagei <= $TotalPage)
		{
			echo '<a href="'.$PageUrl.$Pagei.'">'.$Pagei.'</a>';
		}
	}
	if ($PageCount+5<$TotalPage)
	{
		echo '<a href="'.$PageUrl.$TotalPage.'">'.$TotalPage.'</a>';
	}
	if ($PageCount != $TotalPage)
	{
		echo '<a href="'.$PageUrl.$PageNext.'">'.$Lang['Page_Next'].'&rsaquo;&rsaquo;</a>';
	}
	//echo '&nbsp;&nbsp;&nbsp;<input type="text" onkeydown="JavaScript:if((event.keyCode==13)&&(this.value!=\'\')){window.location=\''.$PageUrl.'\'+this.value;}" onkeyup="JavaScript:if(isNaN(this.value)){this.value=\'\';}" size=4 title="请输入要跳转到第几页,然后按下回车键">';
	echo '</span>';
}


//来源检查
function ReferCheck($UserHash)
{
	if(empty($_SERVER['HTTP_REFERER']) || $UserHash != FormHash() || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))
		return false;
	else
		return true;
}

//表单获取
function Request($Type, $Key ,$DefaultValue='')
{
	switch ($Type) {
		case 'Get':
			return array_key_exists($Key, $_GET) ? trim($_GET[$Key]) : $DefaultValue;
			break;
		case 'Post':
			return array_key_exists($Key, $_POST) ? trim($_POST[$Key]) : $DefaultValue;
			break;
		default:
			return array_key_exists($Key, $_REQUEST ) ? trim($_REQUEST [$Key]) : $DefaultValue;
			break;
	}
}


//设置工作模式为API模式(返回Json格式数据)
//SetStyle('api','API');
function SetStyle($PathName, $StyleName)
{
	global $IsApp, $TemplatePath, $Style;
	if ($StyleName = 'API')
		$IsApp = true;
	$TemplatePath = dirname(__FILE__) .'/styles/'.$PathName.'/template/';
	$Style = $StyleName;
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
}


//批量设置Cookie
function SetCookies($CookiesArray,$Expires=0)
{
	global $Config;
	foreach ($CookiesArray as $key => $value) {
		if(!$Expires)
			setcookie($Config['CookiePrefix'].$key, $value, 0, $Config['WebsitePath'].'/');
		else
			setcookie($Config['CookiePrefix'].$key, $value, time()+ 86400 * $Expires, $Config['WebsitePath'].'/');
	}
}


//大小写不敏感的array_diff
function TagsDiff($Arr1, $Arr2)
{
	global $Config;
	$Arr2 = array_change_key_case(array_flip($Arr2), CASE_LOWER);//flip，排重，Key有Hash索引，速度更快
	foreach ($Arr1 as $Key => $Item)
	{
		if (mb_strlen($Item, "UTF-8") > $Config["MaxTagChars"] || array_key_exists(strtolower(trim($Item)), $Arr2) || strpos("|", $Item) || !preg_match('/^[a-zA-Z0-9\x80-\xff\-_. ]{1,'.$Config['MaxTagChars'].'}$/i', $Item))
		{
			unset($Arr1[$Key]); 
		}else{
			$Arr1[$Key] = htmlspecialchars(trim($Arr1[$Key]));//XSS
		}
	}
	return $Arr1; 
}


//修改系统设置
function UpdateConfig($NewConfig)
{
	global $Prefix, $DB;
	if($NewConfig)
	{
		foreach ($NewConfig as $Key => $Value)
		{
			$DB->query("UPDATE `".$Prefix."config` SET ConfigValue=? WHERE ConfigName=?", array($Value, $Key));
		}
		return true;
	}else{
		return false;
	}
	
}


//跨站脚本白名单过滤
function XssEscape($html) {
		preg_match_all("/\<([^\<]+)\>/is", $html, $ms);

		$searchs[] = '<';
		$replaces[] = '&lt;';
		$searchs[] = '>';
		$replaces[] = '&gt;';

		if($ms[1]) {
			$allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote|object|param|embed|pre';
			$ms[1] = array_unique($ms[1]);
			foreach ($ms[1] as $value) {
				$searchs[] = "&lt;".$value."&gt;";
 
				$value = str_replace('&', '_uch_tmp_str_', $value);
				$value = dhtmlspecialchars($value);
				$value = str_replace('_uch_tmp_str_', '&', $value);
 
				$value = str_replace(array('\\','/*'), array('.','/.'), $value);
				$skipkeys = array('onabort','onactivate','onafterprint','onafterupdate','onbeforeactivate','onbeforecopy','onbeforecut','onbeforedeactivate',
						'onbeforeeditfocus','onbeforepaste','onbeforeprint','onbeforeunload','onbeforeupdate','onblur','onbounce','oncellchange','onchange',
						'onclick','oncontextmenu','oncontrolselect','oncopy','oncut','ondataavailable','ondatasetchanged','ondatasetcomplete','ondblclick',
						'ondeactivate','ondrag','ondragend','ondragenter','ondragleave','ondragover','ondragstart','ondrop','onerror','onerrorupdate',
						'onfilterchange','onfinish','onfocus','onfocusin','onfocusout','onhelp','onkeydown','onkeypress','onkeyup','onlayoutcomplete',
						'onload','onlosecapture','onmousedown','onmouseenter','onmouseleave','onmousemove','onmouseout','onmouseover','onmouseup','onmousewheel',
						'onmove','onmoveend','onmovestart','onpaste','onpropertychange','onreadystatechange','onreset','onresize','onresizeend','onresizestart',
						'onrowenter','onrowexit','onrowsdelete','onrowsinserted','onscroll','onselect','onselectionchange','onselectstart','onstart','onstop',
						'onsubmit','onunload','javascript','script','eval','behaviour','expression','style');//class
				$skipstr = implode('|', $skipkeys);
				$value = preg_replace(array("/($skipstr)/i"), '.', $value);
				if(!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
					$value = '';
				}
				$replaces[] = empty($value)?'':"<".str_replace('&quot;', '"', $value).">";
			}
		}
		$html = str_replace($searchs, $replaces, $html);
	return $html;
}


function dhtmlspecialchars($string, $flags = null) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlspecialchars($val, $flags);
		}
	} else {
		if($flags === null) {
			$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
			if(strpos($string, '&amp;#') !== false) {
				$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
			}
		} else {
			if(PHP_VERSION < '5.4.0') {
				$string = htmlspecialchars($string, $flags);
			} else {
				if(strtolower(CHARSET) == 'utf-8') {
					$charset = 'UTF-8';
				} else {
					$charset = 'ISO-8859-1';
				}
				$string = htmlspecialchars($string, $flags, $charset);
			}
		}
	}
	return $string;
}

$UserAgent = array_key_exists('HTTP_USER_AGENT', $_SERVER) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
if ($UserAgent) {
	$IsSpider = preg_match('/(bot|crawl|spider|slurp|sohu-search|lycos|robozilla|google)/i', $UserAgent);
	$IsMobie  = preg_match('/(iPod|iPhone|Android|Opera Mini|BlackBerry|webOS|UCWEB|Blazer|PSP)/i', $UserAgent);
	$IsApp = $_SERVER['HTTP_HOST'] == $Config['AppDomainName']?true:false;
	// 设置模板前缀
	if ($IsApp) {
		$TemplatePath = dirname(__FILE__) .'/styles/api/template/';
		$Style = 'API';
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');
	} else if ($_SERVER['HTTP_HOST'] == $Config['MobileDomainName']) {
		$TemplatePath = dirname(__FILE__) .'/styles/mobile/template/';
		$Style = 'Mobile';
	} else {
		$TemplatePath = dirname(__FILE__) .'/styles/default/template/';
		$Style = 'Default';
	}
	$AutomaticSwitch = GetCookie('Style','Default');
	if ($_SERVER['HTTP_HOST'] != $Config['MobileDomainName'] && $IsMobie && $AutomaticSwitch != 'Default') {
		//如果是手机，则跳转到移动版，暂时关闭
		//header('location: http://'.$Config['MobileDomainName'].$_SERVER['REQUEST_URI']);
	}
} else {
	//exit('error: 400 no agent');
	$IsSpider = '';
	$IsMobie  = '';
}

$CurIP = CurIP();
$FormHash = FormHash();
// 限制不能打开.php的网址
if(strpos(isset($_SERVER['HTTP_X_REWRITE_URL'])?$_SERVER['HTTP_X_REWRITE_URL']:$_SERVER["REQUEST_URI"], '.php')){
	AlertMsg('404','404 NOT FOUND',404);
}

//设置基本环境变量
/*
$IsSpider
$IsMobie
*/

// 获取当前用户
$CurUserInfo = null;//当前用户信息，Array，以后判断是否登陆使用if($CurUserID)
$CurUserRole = 0;
$CurUserID      = GetCookie('UserID');
$CurUserCode    = GetCookie('UserCode');

if ($CurUserID && $CurUserCode) {
	$TempUserInfo = $DB->row("SELECT * FROM ".$Prefix."users WHERE ID = :UserID", array("UserID"=>$CurUserID));
	if($TempUserInfo && $CurUserCode == md5($TempUserInfo['Password'].$TempUserInfo['Salt'].$Style.$SALT))
	{
		$CurUserName = $TempUserInfo['UserName'];
		$CurUserRole = $TempUserInfo['UserRoleID'];
		$CurUserInfo = $TempUserInfo;
	}else{
		$CurUserID = 0;
		$CurUserCode = '';
		SetCookies(array('UserID' => '', 'UserCode' => ''), 1);
	}
	unset($TempUserInfo);
}
?>
