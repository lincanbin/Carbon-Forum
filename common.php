<?php
/*
 * Carbon-Forum
 * https://github.com/lincanbin/Carbon-Forum
 *
 * Copyright 2006-2017 Canbin Lin (lincanbin@hotmail.com)
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A high performance open-source forum software written in PHP. 
 */
/*
逐渐替换为帕斯卡命名法
数据库从设计上避免使用Join多表联查
*/
define('CARBON_FORUM_VERSION', '5.9.0');

//Initialize timer
$StartTime = microtime(true);
$TimeStamp = intval($_SERVER['REQUEST_TIME']);
if ((@include __DIR__ . '/config.php') != 1) {
	//Bring user to installation
	header("Location: install/");
	exit(); //No errors
}
require(LanguagePath . 'common.php');
//Initialize PHP Data Object(Database)
require(LibraryPath . 'PDO.class.php');
require(LibraryPath . 'WhiteHTMLFilterConfig.php');
require(LibraryPath . 'WhiteHTMLFilter.php');

$DB = new Db(DBHost, DBPort, DBName, DBUser, DBPassword);
//Initialize MemCache(d) / Redis
$MCache = false;
if (EnableMemcache) {
	if (extension_loaded('memcached')) {
		//MemCached
		$MCache = new Memcached(MemCachePrefix . 'Cache');
		//Using persistent memcached connection
		if (!count($MCache->getServerList())) {
			$MCache->addServer(MemCacheHost, MemCachePort);
		}
	} elseif (extension_loaded('memcache')) {
		//MemCache
		require(LibraryPath . "MemcacheMod.class.php");
		$MCache = new MemcacheMod(MemCacheHost, MemCachePort);
	} elseif (extension_loaded('redis')) {
		//Redis
		//https://github.com/phpredis/phpredis
		$MCache = new Redis();
		$MCache->pconnect(MemCacheHost, MemCachePort);
	} elseif (extension_loaded('xcache')) {
		// XCache
		require(LibraryPath . "XCache.class.php");
		$MCache = new XCache();
	}
}

//Load configuration
$Config = array();
if ($MCache) {
	$Config = $MCache->get(MemCachePrefix . 'Config');
}
if (!$Config) {
	foreach ($DB->query('SELECT ConfigName,ConfigValue FROM ' . PREFIX . 'config') as $ConfigArray) {
		$Config[$ConfigArray['ConfigName']] = $ConfigArray['ConfigValue'];
	}
	// Update
	if ($Config['Version'] != CARBON_FORUM_VERSION) {
		header("Location: update/"); // Bring user to installation
		exit(); //No errors
	}
	if ($MCache) {
		$MCache->set(MemCachePrefix . 'Config', $Config, 86400);
	}
}
// 热门标签列表
$HotTagsArray = json_decode($Config['CacheHotTags'], true);
$HotTagsArray = $HotTagsArray ? $HotTagsArray : array();

$PHPSelf = addslashes(htmlspecialchars($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']));
$UrlPath = '';
//For IIS ISAPI_Rewrite
$RequestURI = str_ireplace('?' . (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : ''), '', (isset($_SERVER['HTTP_X_REWRITE_URL']) ? $_SERVER['HTTP_X_REWRITE_URL'] : $_SERVER['REQUEST_URI']));
$IsAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
$CurProtocol = IsSSL() ? 'https://' : 'http://';

$_HEAD = array();
$_PUT = array();
$_DELETE = array();
$_OPTIONS = array();

//消除低版本中魔术引号的影响
if (version_compare(PHP_VERSION, '5.4.0') < 0 && get_magic_quotes_gpc()) {
	function StripslashesDeep($var)
	{
		return is_array($var) ? array_map('StripslashesDeep', $var) : stripslashes($var);
	}

	$_GET = StripslashesDeep($_GET);
	$_POST = StripslashesDeep($_POST);
	$_COOKIE = StripslashesDeep($_COOKIE);
	$_REQUEST = StripslashesDeep($_REQUEST);
}


// At某人并提醒他，使用时常在其前后加空格或回车，如 “@admin ”
function AddingNotifications($Content, $TopicID, $PostID, $FilterUser = '')
{
	/*
	Type:
	1:新回复
	2:@ 到我的
	*/
	global $DB, $MCache, $TimeStamp, $CurUserName;
	$InTransaction = $DB->inTransaction();//是否处于其他事务之中
	//例外列表
	$ExceptionUser = array(
		$CurUserName
	);
	if ($FilterUser != $CurUserName) {
		$ExceptionUser[] = $FilterUser;
	}
	// 正则跟用户注册、登录保持一致
	preg_match_all('/\B\@([a-zA-Z0-9\x80-\xff\-_]{4,20})/', strip_tags($Content, '<br><p>'), $out, PREG_PATTERN_ORDER);
	$TemporaryUserList = array_unique($out[1]); //排重
	$TemporaryUserList = array_diff($TemporaryUserList, $ExceptionUser);
	//对数组重新分配下标
	sort($TemporaryUserList);
	if ($TemporaryUserList) {
		try {
			if (!$InTransaction) {
				$DB->beginTransaction();
			}
			$UserList = $DB->row('SELECT ID FROM `' . PREFIX . 'users` WHERE `UserName` IN (?)', $TemporaryUserList);
			if ($UserList && count($UserList) <= 20) {
				//最多@ 20人，防止骚扰
				foreach ($UserList as $UserID) {
					$DB->query('INSERT INTO `' . PREFIX . 'notifications`(`ID`,`UserID`, `UserName`, `Type`, `TopicID`, `PostID`, `Time`, `IsRead`) VALUES (NULL,?,?,?,?,?,?,?)', array(
						$UserID,
						$CurUserName,
						2,
						$TopicID,
						$PostID,
						$TimeStamp,
						0
					));
					$DB->query('UPDATE `' . PREFIX . 'users` SET `NewMention` = NewMention+1 WHERE ID = :UserID', array(
						'UserID' => $UserID
					));
					//清理内存缓存
					if ($MCache) {
						$MCache->delete(MemCachePrefix . 'UserInfo_' . $UserID);
					}
				}
			}
			if (!$InTransaction) {
				$DB->commit();
			}
		} catch (Exception $ex) {
			if (!$InTransaction) {
				$DB->rollBack();
			} else {
				throw $ex;
			}
			return false;
		}
	}
	return true;
}


//提示信息
function AlertMsg($PageTitle, $Error, $StatusCode = 200)
{
	global $Lang, $CurProtocol, $RequestURI, $UrlPath, $IsAjax, $IsMobile, $IsApp, $DB, $Config, $HotTagsArray, $CurUserID, $CurUserName, $CurUserCode, $CurUserRole, $CurUserInfo, $FormHash, $StartTime, $PageMetaKeyword, $TemplatePath;
	$HttpStatuses = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		118 => 'Connection timed out',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		210 => 'Content Different',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Reserved',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		310 => 'Too many Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested range unsatisfiable',
		417 => 'Expectation failed',
		418 => 'I\'m a teapot',
		422 => 'Unprocessable entity',
		423 => 'Locked',
		424 => 'Method failure',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		449 => 'Retry With',
		450 => 'Blocked by Windows Parental Controls',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway or Proxy Error',
		503 => 'Service Unavailable',
		504 => 'Gateway Time-out',
		505 => 'HTTP Version not supported',
		507 => 'Insufficient storage',
		508 => 'Loop Detected',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
	];
	$Protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1');
	if (!$IsAjax && !empty($HttpStatuses[$StatusCode])) {
		// http_response_code($StatusCode);
		header($Protocol . ' ' . $StatusCode . ' ' . $HttpStatuses[$StatusCode]);
	}
	$ContentFile = $TemplatePath . 'alert.php';
	include($TemplatePath . 'layout.php');
	exit;
}


//获取数组中的某一列
function ArrayColumn($Input, $ColumnKey)
{
	if (version_compare(PHP_VERSION, '5.5.0') < 0) {
		$Result = array();
		if ($Input) {
			foreach ($Input as $Value) {
				$Result[] = $Value[$ColumnKey];
			}
		}
		return $Result;
	} else {
		return array_column($Input, $ColumnKey);
	}
}


//鉴权
function Auth($MinRoleRequire, $AuthorizedUserID = 0, $StatusRequire = false)
{
	global $CurUserRole, $CurUserID, $CurUserInfo, $Lang, $RequestURI;
	$error = '';
	if ($CurUserRole < $MinRoleRequire) {
		$error = str_replace('{{RoleDict}}', $Lang['RolesDict'][$MinRoleRequire], $Lang['Error_Insufficient_Permissions']);
	}
	if ($CurUserID && $StatusRequire == true && $CurUserInfo['UserAccountStatus'] == 0) {
		$error = $Lang['Error_Account_navailable'];
	}
	if ($AuthorizedUserID && $CurUserID && $CurUserID == $AuthorizedUserID) {
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
	$IsCDN = false; //未使用CDN时，应直接使用 $_SERVER['REMOTE_ADDR'] 以防止客户端伪造IP
	$IP = false;
	if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
		$IP = trim($_SERVER["HTTP_CLIENT_IP"]);
	}
	if ($IsCDN && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$IPs = array_map("trim", explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']));
		if ($IP) {
			array_unshift($IPs, $IP);//插入头部而不是尾部，提升性能
			$IP = FALSE;
		}
		//支持使用CDN后获取IP，理论上令 $IP = $IPs[0]; 即可，安全起见遍历过滤一次
		foreach ($IPs as $Key => $Value) {
			/*
			Fails validation for the following private IPv4 ranges: 10.0.0.0/8, 172.16.0.0/12 and 192.168.0.0/16.
			Fails validation for the IPv6 addresses starting with FD or FC.
			*/
			if (filter_var($Value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
				$IP = $Value;
				break;
			}
		}
	}
	return htmlspecialchars($IP ? $IP : $_SERVER['REMOTE_ADDR']);
}


//关键词过滤
function Filter($Content)
{
	if (is_file(LibraryPath . 'Filtering.words.config.json')) {
		$FilteringWords = JsonDecode(file_get_contents(LibraryPath . 'Filtering.words.config.json'));
	} else {
		$FilteringWords = JsonDecode(file_get_contents(LibraryPath . 'Filtering.words.config.template.json'));
	}
	$Prohibited = false;
	$GagTime = 0;
	foreach ($FilteringWords as $SearchRegEx => $Rule) {

		if (preg_match_all("/" . $SearchRegEx . "/i", $Content, $SearchWordsList)) {
			//var_dump($SearchWordsList);
			foreach ($SearchWordsList as $SearchWord) {
				if (is_array($Rule)) {
					$Content = str_ireplace($SearchWord, $Rule[0], $Content);
					$Prohibited |= ($Rule[0] === false);
					$GagTime = ($Rule[1] > $GagTime) ? $Rule[1] : $GagTime; //将规则中封禁时间最长的一个赏给用户
				} else {
					$Content = str_ireplace($SearchWord, $Rule, $Content);
					//$Prohibited |= false;
					//$GagTime = 0;
				}
			}
		}
	}
	return array(
		'Content' => $Content, //过滤后的内容
		'Prohibited' => $Prohibited, //是否包含有禁止发布的词
		'GagTime' => $GagTime //赏赐给用户的禁言时间（秒）
	);
}

// 获得表单校验散列
function FormHash()
{
	global $Config;
	if (GetCookie('UserCode'))
		return substr(md5($Config['SiteName'] . GetCookie('UserCode') . SALT), 8, 8);
	else
		return substr(md5($Config['SiteName'] . SALT), 8, 8);
}


//格式化文件大小
function FormatBytes($size, $precision = 2)
{
	// https://www.zhihu.com/question/21578998/answer/86401223
	// According to Metric prefix, IEEE 1541-2002.
	$units = array(
		' Bytes',
		' KiB',
		' MiB',
		' GiB',
		' TiB'
	);
	for ($i = 0; $size >= 1024 && $i < 4; $i++)
		$size /= 1024;
	return round($size, $precision) . $units[$i];
}


// 格式化时间
function FormatTime($UnixTimeStamp)
{
	global $Lang;
	$Seconds = $_SERVER['REQUEST_TIME'] - $UnixTimeStamp;
	if ($Seconds < 2592000) {
		// 小于30天如下显示
		if ($Seconds >= 86400) {
			return round($Seconds / 86400, 0) . '&nbsp;' . $Lang['Time_Days_Ago'];
		} else if ($Seconds >= 3600) {
			return round($Seconds / 3600, 0) . '&nbsp;' . $Lang['Time_Hours_Ago'];
		} else if ($Seconds >= 60) {
			return round($Seconds / 60, 0) . '&nbsp;' . $Lang['Time_Minutes_Ago'];
		} else if ($Seconds < 0) {
			return $Lang['Time_Just_Now'];
		} else {
			return ($Seconds + 1) . '&nbsp;' . $Lang['Time_Seconds_Ago'];
		}
	} else {
		// 大于一月
		return date("Y-m-d", $UnixTimeStamp);
		//gmdate()可以返回格林威治标准时，date()则为当地时
	}
}


//获取头像
function GetAvatar($UserID, $UserName, $Size = 'middle')
{
	global $Config;
	return '<img src="' . $Config['WebsitePath'] . '/upload/avatar/' . $Size . '/' . $UserID . '.png" alt="' . $UserName . '"/>';
}


//获取Tag标签
function GetTagIcon($TagID, $Icon, $TagName, $Size = 'middle')
{
	global $Config;
	return '<img src="' . $Config['WebsitePath'] . '/upload/tag/' . $Size . '/' . ($Icon ? $TagID : '0') . '.png" alt="' . $TagName . '"/>';
}

//获取Cookie
function GetCookie($Key, $DefaultValue = false)
{
	global $Config, $IsApp;
	if (!$IsApp) {
		if (!empty($_COOKIE[$Config['CookiePrefix'] . $Key])) {
			return $_COOKIE[$Config['CookiePrefix'] . $Key];
		} else if ($DefaultValue) {
			SetCookies(array(
				$Key => $DefaultValue
			));
			return $DefaultValue;
		}
	} else {
		return Request("Request", "Auth" . $Key, $DefaultValue);
	}
	return false;
}


//Hash值校验，防止时序攻击法
function HashEquals($KnownString, $UserString)
{
	if (version_compare(PHP_VERSION, '5.6.0') < 0) {
		return ($KnownString === $UserString);
	} else {
		return hash_equals($KnownString, $UserString);
	}
}

//长整数intval，防止溢出，目前暂未用到
function Int($s)
{
	return ($a = preg_replace('/[^\-\d]*(\-?\d*).*/', '$1', $s)) ? $a : '0';
}


//判断是否为邮件地址
function IsEmail($email)
{
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

function IsJson($String)
{
	JsonDecode($String);
	return (json_last_error() === JSON_ERROR_NONE);
}

//判断是否为合法用户名
function IsName($string)
{
	return !preg_match('/^[0-9]{4,20}$/', $string) && preg_match('/^[a-zA-Z0-9\x{4e00}-\x{9fa5}\-_]{4,20}$/ui', $string);
}


//判断当前协议
function IsSSL()
{
	if (!isset($_SERVER['HTTPS']))
		return false;
	if ($_SERVER['HTTPS'] === 1) { //Apache
		return true;
	} elseif ($_SERVER['HTTPS'] === 'on') { //IIS
		return true;
	} elseif ($_SERVER['SERVER_PORT'] == 443) { //其他
		return true;
	}
	return false;
}

// 去除注释的JsonDecode
function JsonDecode($Json)
{
	return json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $Json), true);
}

//登出
function LogOut()
{
	global $CurUserID;
	SetCookies(array(
		'UserID' => '',
		'UserExpirationTime' => '',
		'UserCode' => ''
	), 1);
	$CurUserID = 0;
}


//跳转
function Redirect($URI = '', $ExitCode = 0)
{
	global $Config;
	header('location: ' . $Config['WebsitePath'] . '/' . $URI);
	exit($ExitCode);
}

//来源检查
function ReferCheck($UserHash)
{
	global $IsApp;
	if (!$IsApp && (empty($_SERVER['HTTP_REFERER']) || $UserHash != FormHash() || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])))
		return false;
	else
		return true;
}

//表单获取
function Request($Type, $Key, $DefaultValue = '')
{
	global $_PUT, $_DELETE, $_OPTIONS;
	switch ($Type) {
		case 'Get':
			return isset($_GET[$Key]) ? trim($_GET[$Key]) : $DefaultValue;
			break;
		case 'Post':
			return isset($_POST[$Key]) ? trim($_POST[$Key]) : $DefaultValue;
			break;
		case 'Put':
			return isset($_PUT[$Key]) ? trim($_PUT[$Key]) : $DefaultValue;
			break;
		case 'Delete':
			return isset($_DELETE[$Key]) ? trim($_DELETE[$Key]) : $DefaultValue;
			break;
		case 'Options':
			return isset($_OPTIONS[$Key]) ? trim($_OPTIONS[$Key]) : $DefaultValue;
			break;
		default:
			return isset($_REQUEST[$Key]) ? trim($_REQUEST[$Key]) : $DefaultValue;
			break;
	}
}


//设置工作模式为API模式(返回Json格式数据)
//SetStyle('api','API');
function SetStyle($PathName, $StyleName)
{
	global $IsApp, $TemplatePath, $Style;
	if ($StyleName = 'API') {
		$IsApp = true;
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json; charset=utf-8');
	}
	$TemplatePath = __DIR__ . '/view/' . $PathName . '/';
	$Style = $StyleName;
}


//批量设置Cookie
function SetCookies($CookiesArray, $Expires = 0)
{
	global $TimeStamp, $Config;
	foreach ($CookiesArray as $key => $value) {
		if (!$Expires)
			setcookie($Config['CookiePrefix'] . $key, $value, 0, $Config['WebsitePath'] . '/', null, false, true);
		else
			setcookie($Config['CookiePrefix'] . $key, $value, $TimeStamp + 86400 * $Expires, $Config['WebsitePath'] . '/', null, false, true);
	}
}


//大小写不敏感的array_diff
function TagsDiff($Arr1, $Arr2)
{
	global $Config;
	$Arr2 = array_change_key_case(array_flip($Arr2), CASE_LOWER); //flip，排重，Key有Hash索引，速度更快
	foreach ($Arr1 as $Key => $Item) {
		if (mb_strlen($Item, "UTF-8") > $Config["MaxTagChars"] || isset($Arr2[strtolower(trim($Item))]) || strpos("|", $Item) || !preg_match('/^[a-zA-Z0-9\x80-\xff\-_\s]{1,' . $Config['MaxTagChars'] . '}$/i', $Item) || $Item != Filter($Item)['Content']) {
			unset($Arr1[$Key]);
		} else {
			$Arr1[$Key] = htmlspecialchars(trim($Arr1[$Key])); //XSS
		}
	}
	sort($Arr1);
	return $Arr1;
}


//修改系统设置
function UpdateConfig($NewConfig)
{
	global $DB, $Config, $MCache;
	if ($NewConfig) {
		foreach ($NewConfig as $Key => $Value) {
			$DB->query("UPDATE `" . PREFIX . "config` SET ConfigValue=? WHERE ConfigName=?", array(
				$Value,
				$Key
			));
			$Config[$Key] = $Value;
		}
		if ($MCache) {
			$MCache->set(MemCachePrefix . 'Config', $Config, 86400);
		}
		return true;
	} else {
		return false;
	}

}


//修改用户资料
function UpdateUserInfo($NewUserInfo, $UserID = 0)
{
	global $DB, $CurUserID, $CurUserInfo, $MCache;
	if ($UserID == 0) {
		$UserID = $CurUserID;
	}
	if ($NewUserInfo) {
		$StringBindParam = '';
		foreach ($NewUserInfo as $Key => $Value) {
			$StringBindParam .= $Key . ' = :' . $Key . ',';
		}
		$StringBindParam = substr($StringBindParam, 0, -1);
		$Result = $DB->query('UPDATE `' . PREFIX . 'users` SET ' . $StringBindParam . ' WHERE ID = :UserID', array_merge($NewUserInfo, array(
			'UserID' => $UserID
		)));
		if ($MCache) {
			$MCache->set(MemCachePrefix . 'UserInfo_' . $UserID, $DB->row("SELECT *, (NewReply + NewMention + NewMessage) as NewNotification FROM " . PREFIX . "users WHERE ID = :UserID", array(
				"UserID" => $UserID
			)), 86400);
		}
		return $Result;
	} else {
		return false;
	}

}

//跨站脚本白名单过滤
function XssEscape($html)
{
	$filter = new WhiteHTMLFilter();
	$urlFilter = function($url) {
		$token = parse_url($url);
		if (empty($token['scheme']) || in_array($token['scheme'], array('http', 'https')) === false) {
			return '';
		}
		$hostWhiteList = array(
			'www.youtube.com', 'youtube.com', 'www.youtu.be', 'youtu.be',
			'player.youku.com', 'v.youku.com',
			'video.tudou.com', 'www.tudou.com',
			'player.video.qiyi.com', 'open.iqiyi.com',
			'imgcache.qq.com', 'v.qq.com',
			'static.hdslb.com',
			//'www.le.com',
			'share.vrs.sohu.com', 'tv.sohu.com',
			'player.pptv.com',
			'cdn.aixifan.com',
			'v.ifeng.com',
			'video.sina.com.cn',
			'galaxy.bjcathay.com'//CNTV
		);
		if (empty($token['host']) || in_array($token['host'], $hostWhiteList) === false) {
			return '';
		}
		return $url;
	};

	$iframeRule = array(
		'iframe' => array(
			'src' => $urlFilter,
			'width',
			'height',
			'frameborder',
			'allowfullscreen'
		)
	);
	$filter->config->modifyTagWhiteList($iframeRule);
	$filter->loadHTML($html);
	$filter->clean();
	return $filter->outputHtml();
}

$UserAgent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
if ($UserAgent) {
	$IsSpider = preg_match('/(bot|crawl|spider|slurp|sohu-search|lycos|robozilla|google)/i', $UserAgent);
	$IsMobile = preg_match('/(iPod|iPhone|Android|Opera Mini|BlackBerry|webOS|UCWEB|Blazer|PSP)/i', $UserAgent);
} else {
	//exit('error: 400 no agent');
	$IsSpider = false;
	$IsMobile = false;
}
$IsApp = $_SERVER['HTTP_HOST'] == $Config['AppDomainName'] ? true : false;
/* Set current template
 * default: PC Version
 * mobile: Mobile Version
 * api: API
 */
if ($IsApp) {
	$TemplatePath = __DIR__ . '/view/api/';
	$Style = 'API';
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	//API鉴权
	$SignatureKey = Request("Request", "SKey");
	$SignatureValue = Request("Request", "SValue");
	$SignatureTime = intval(Request("Request", "STime"));
	if (!$SignatureTime || !$SignatureKey || !$SignatureValue || empty($APISignature[$SignatureKey]) || abs($SignatureTime - $TimeStamp) > 600 || !HashEquals($SignatureValue, md5($SignatureKey . $APISignature[$SignatureKey] . $SignatureTime))) {
		AlertMsg('403', 'Forbidden', 403);
	}
} elseif ($_SERVER['HTTP_HOST'] == $Config['MobileDomainName'] || (!$Config['MobileDomainName'] && $IsMobile)) {
	$TemplatePath = __DIR__ . '/view/mobile/';
	$Style = 'Mobile';
	header('X-Frame-Options: SAMEORIGIN');
} else {
	$TemplatePath = __DIR__ . '/view/default/';
	$Style = 'Default';
	header('X-Frame-Options: SAMEORIGIN');
	//header('X-XSS-Protection: 1; mode=block');
	//X-XSS-Protection may cause some issues in dashboard
}

$CurView = GetCookie('View', $IsMobile ? 'mobile' : 'desktop');
$CurIP = CurIP();
$FormHash = FormHash();
// 限制不能打开.php的网址
if (strpos($RequestURI, '.php')) {
	AlertMsg('403', 'Forbidden', 403);
}


$CurrentDate = date('Y-m-d');
if ($Config['DaysDate'] != $CurrentDate) {
	if (!strtotime($Config['DaysDate'])) {
		$Config['DaysDate'] = $CurrentDate;
	}
	$DB->query('INSERT INTO `' . PREFIX . 'statistics` 
		(`DaysUsers`, `DaysPosts`, `DaysTopics`, `TotalUsers`, `TotalPosts`, `TotalTopics`, `DaysDate`, `DateCreated`) 
		SELECT 
		:DaysUsers, :DaysPosts, :DaysTopics, :TotalUsers, :TotalPosts, :TotalTopics, :DaysDate, :DateCreated 
		FROM dual  
		WHERE NOT EXISTS(  
			SELECT *  FROM `' . PREFIX . 'statistics`  
			WHERE DaysDate = :DaysDate2
		)
		', array(
		'DaysUsers' => $Config['DaysUsers'],
		'DaysPosts' => $Config['DaysPosts'],
		'DaysTopics' => $Config['DaysTopics'],
		'TotalUsers' => $Config['NumUsers'],
		'TotalPosts' => $Config['NumPosts'],
		'TotalTopics' => $Config['NumTopics'],
		'DaysDate' => $Config['DaysDate'],
		'DateCreated' => $TimeStamp,
		'DaysDate2' => $Config['DaysDate']
	));
	UpdateConfig(array(
		'DaysDate' => $CurrentDate,
		'DaysTopics' => 0,
		'DaysPosts' => 0,
		'DaysUsers' => 0,
		'CacheHotTags' => json_encode($DB->query('SELECT ID,Name,Icon,TotalPosts,Followers FROM ' . PREFIX . 'tags 
			WHERE IsEnabled=1 AND TotalPosts>0
			ORDER BY TotalPosts DESC 
			LIMIT ' . $Config['TopicsPerPage']))
	));
}
// Get the infomation of current user
$CurUserInfo = null; //当前用户信息，Array，以后判断是否登陆使用if($CurUserID)
$CurUserRole = 0;
$CurUserID = intval(GetCookie('UserID'));
$CurUserName = '';
$CurUserExpirationTime = intval(GetCookie('UserExpirationTime'));
$CurUserCode = GetCookie('UserCode');

if ($CurUserExpirationTime > $TimeStamp && $CurUserExpirationTime < ($TimeStamp + 2678400) && $CurUserID && $CurUserCode) {
	$TempUserInfo = array();
	if ($MCache) {
		$TempUserInfo = $MCache->get(MemCachePrefix . 'UserInfo_' . $CurUserID);
	}
	if (empty($TempUserInfo)) {
		$TempUserInfo = $DB->row("SELECT *, (NewReply + NewMention + NewMessage) as NewNotification FROM " . PREFIX . "users WHERE ID = :UserID", array(
			"UserID" => $CurUserID
		));
		if ($MCache && $TempUserInfo) {
			$MCache->set(MemCachePrefix . 'UserInfo_' . $CurUserID, $TempUserInfo, 86400);
		}
	}
	if ($TempUserInfo && HashEquals(md5($TempUserInfo['Password'] . $TempUserInfo['Salt'] . $CurUserExpirationTime . SALT), $CurUserCode)) {
		$CurUserName = $TempUserInfo['UserName'];
		$CurUserRole = $TempUserInfo['UserRoleID'];
		$CurUserInfo = $TempUserInfo;
	} else {
		LogOut();
	}
	unset($TempUserInfo);
} elseif ($CurUserExpirationTime || $CurUserID || $CurUserCode) {
	LogOut();
}