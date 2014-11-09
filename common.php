<?php
//设置中国时区
define('SAESPOT_VER', '20140817');
$desktopurl = 'ourjnu.com';
$mobileurl = 'm.ourjnu.com';
$clienturl = 'client.ourjnu.com';
date_default_timezone_set('PRC');
/*
不输出任何错误信息
error_reporting(0);*/


/*除了 E_NOTICE，报告其他所有错误*/
error_reporting(E_ALL ^ E_NOTICE ^E_WARNING);

/*
输出所有错误信息
error_reporting(E_ALL);
ini_set('display_errors', '1');
*/

if (!defined('IN_SAESPOT'))
	exit('error: 403 Access Denied');
//常用数组区
$campus          = array(
	"2" => "校本部",
	"3" => "珠海校区",
	"4" => "华文学院",
	"5" => "深圳校区",
	"6" => "南校区"
);
$ec_category     = array();
$ec_category[0]  = '全部课程';
$ec_category[1]  = '体育竞技与休闲运动课程群';
$ec_category[2]  = '生命科学类';
$ec_category[3]  = '文史哲类';
$ec_category[4]  = '艺术素养类';
$ec_category[5]  = '经管法类';
$ec_category[6]  = '其他类';
$ec_category[7]  = '计算机高级课程群';
$ec_category[8]  = '数学理工类';
$ec_category[9]  = '高级英语课程群';
$ec_category[10] = '中国语文课程群';
$ec_category[11] = '人文社科类';
$ec_category[12] = '公共选修课';
$ec_category[13] = '自然科学类';
$ec_category[14] = '跨学科类';

$mtime     = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];
$timestamp = time();
$php_self  = addslashes(htmlspecialchars($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']));
$url_path  = substr($php_self, 1, -4);
include(dirname(__FILE__) . '/include/mysql.class.php');
// 初始化从数据类，若要写、删除数据则需要定义主数据类
$DBS = new DB_MySQL;
$DBS->connect($servername, $dbport, $dbusername, $dbpassword, $dbname);

// 去除转义字符
function stripslashes_array(&$array)
{
	if (is_array($array)) {
		foreach ($array as $k => $v) {
			$array[$k] = stripslashes_array($v);
		}
	} else if (is_string($array)) {
		$array = stripslashes($array);
	}
	return $array;
}

@set_magic_quotes_runtime(0);
// 判断 magic_quotes_gpc 状态
if (@get_magic_quotes_gpc()) {
	$_GET    = stripslashes_array($_GET);
	$_POST   = stripslashes_array($_POST);
	$_COOKIE = stripslashes_array($_COOKIE);
}

// 获取当前用户
$cur_user     = null;
$cur_userinfo = null;
$cur_uid      = $_COOKIE['cur_uid'];
$cur_uname    = $_COOKIE['cur_uname'];
$cur_ucode    = $_COOKIE['cur_ucode'];

if ($cur_uname && $cur_uid && $cur_ucode) {
	$u_key = 'u_' . $cur_uid;
	
	// 从数据库里读取
	$db_user = $DBS->fetch_one_array("SELECT * FROM jnubbs_users WHERE id='" . $cur_uid . "' LIMIT 1");
	if ($db_user['studentnumber']) {
		$db_userinfo = $DBS->fetch_one_array("SELECT * FROM jnubbs_info i LEFT JOIN jnubbs_profession_code c on c.zyh=i.zyh and c.yxsh=i.yxsh WHERE studentnumber='" . $db_user['studentnumber'] . "' LIMIT 1");
		if ($db_userinfo) {
			$cur_userinfo = $db_userinfo;
			unset($db_userinfo);
		}
	}
	if ($db_user) {
		$db_ucode = md5($db_user['id'] . $db_user['password'] . $db_user['regtime'] . $db_user['lastposttime'] . $db_user['lastreplytime']);
		if ($cur_uname == $db_user['name'] && $cur_ucode == $db_ucode) {
			//设置cookie
			setcookie('cur_uid', $cur_uid, $timestamp + 86400 * 365, '/');
			setcookie('cur_uname', $cur_uname, $timestamp + 86400 * 365, '/');
			setcookie('cur_ucode', $cur_ucode, $timestamp + 86400 * 365, '/');
			$cur_user = $db_user;
			unset($db_user);
		}
	}
	
}

include(dirname(__FILE__) . '/model.php');

// 获得散列
function formhash()
{
	global $cur_ucode, $options, $is_mobie, $is_client;
	if (!$is_mobie || !$is_client)
		return substr(md5($options['site_create'] . $cur_ucode . 'yoursecretwords'), 8, 8);
	else
		return 12345678;
}

$formhash = formhash();

// 限制不能打开.php的网址
//if(strpos($_SERVER["REQUEST_URI"], '.php')){
//    alertmsg('404','404 NOT FOUND',1);
//}

// 只允许注册用户访问
if ($options['authorized'] && (!$cur_user || $cur_user['flag'] < 5)) {
	if (!in_array($url_path, array(
		'login',
		'logout',
		'sigin',
		'forgot',
		'qqlogin',
		'qqcallback',
		'qqsetname',
		'wblogin',
		'wbcallback',
		'wbsetname'
	))) {
		header('location: /login');
		exit('authorized only');
	}
}

//网站暂时关闭
if ($options['close'] && (!$cur_user || $cur_user['flag'] < 99)) {
	if (!in_array($url_path, array(
		'login',
		'forgot'
	))) {
		header('location: /login');
		exit('site close');
	}
}


// 获得IP地址
if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
	$onlineip = getenv('HTTP_CLIENT_IP');
} elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
	$onlineip = getenv('HTTP_X_FORWARDED_FOR');
} elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
	$onlineip = getenv('REMOTE_ADDR');
} elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
	$onlineip = $_SERVER['REMOTE_ADDR'];
}
$onlineip = addslashes($onlineip);
//if(!$onlineip) exit('error: 400 no ip');

$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
if ($user_agent) {
	$is_spider = preg_match('/(bot|crawl|spider|slurp|sohu-search|lycos|robozilla|google)/i', $user_agent);
	$is_mobie  = preg_match('/(iPod|iPhone|Android|Opera Mini|BlackBerry|webOS|UCWEB|Blazer|PSP)/i', $user_agent);
	// 设置模板前缀
	if ($_SERVER['HTTP_HOST'] == $clienturl) {
		$is_client = true;
		$tpl       = 'client_';
		header('Access-Control-Allow-Origin: *');
	} else if ($_SERVER['HTTP_HOST'] == $mobileurl) {
		$tpl    = 'ios_';
	} else {
		$tpl = '';
	}
	$viewat = $_COOKIE['vtpl'];
	if ($_SERVER['HTTP_HOST'] == $mobileurl && $is_mobie && $viewat != 'desktop') {
		//如果是手机，则跳转到移动版，暂时关闭
		//header('location: http://'.$mobileurl.$_SERVER['REQUEST_URI']);
	}
	//print_r($_SERVER);
} else {
	//exit('error: 400 no agent');
	$is_spider = '';
	$is_mobie  = '';
}

//设置基本环境变量
/*
$cur_user
$is_spider
$is_mobie
$options
*/
//提示信息
function alertmsg($title, $msg, $is_404)
{
	global $DBS, $options, $cur_user, $cur_userinfo, $starttime, $keyword;
	$errors   = array();
	$errors[] = $msg;
	if ($is_404) {
		header("HTTP/1.0 404 Not Found");
		header("Status: 404 Not Found");
	}
	$pagefile = dirname(__FILE__) . '/templates/default/' . $tpl . 'alert.php';
	include(dirname(__FILE__) . '/templates/default/' . $tpl . 'layout.php');
	exit;
}

//鉴权
function auth($require_flag, $require_login, $require_verify)
{
	global $DBS, $options, $cur_user, $cur_userinfo, $starttime, $formhash;
	$errors = array();
	if ($require_flag == 1 && $cur_user['flag'] == 0 && $cur_user)
		$errors[] = '您已被封禁，无法访问此页面，请联系管理员。';
	if ($require_flag == 1 && $cur_user['flag'] == 1 && $cur_user)
		$errors[] = '您的账号正在等待审核中，无法访问此页面，请联系管理员。';
	if ($require_login == 1 && !$cur_user)
		$errors[] = '访问此页面需要登陆，请您先登陆，如果没有账号请先注册。';
	if ($require_verify == 1 && $cur_user && !$cur_userinfo)
		$errors[] = '访问此页面需要绑定校园卡，请您先在下方完成绑定校园卡！';
	if ($errors) {
		// 页面变量
		header("HTTP/1.0 401 Unauthorized");
		header("Status: 401 Unauthorized");
		$title    = '错误信息';
		$pagefile = dirname(__FILE__) . '/templates/default/' . $tpl . 'alert.php';
		include(dirname(__FILE__) . '/templates/default/' . $tpl . 'layout.php');
		exit;
	}
}
//URL鉴权
function url_auth($auth_url)
{
	global $cur_user;
	if (!$cur_user)
		$auth_url = 'JavaScript:alert(\'访问此功能需要登陆\');';
	else
		$auth_url .= '" target="_blank';
	return $auth_url;
}
// 一些常用的函数
// 显示时间格式化
function showtime($db_time)
{
	$diftime = time() - $db_time;
	if ($diftime < 2592000) {
		// 小于30天如下显示
		if ($diftime >= 86400) {
			return round($diftime / 86400, 1) . '天前';
		} else if ($diftime >= 3600) {
			return round($diftime / 3600, 1) . '小时前';
		} else if ($diftime >= 60) {
			return round($diftime / 60, 1) . '分钟前';
		} else if($diftime < 0) {
			return '刚刚';
		} else {
			return ($diftime + 1) . '秒钟前';
		}
	} else {
		// 大于一年
		return date("Y-m-d", $db_time);
		//gmdate()可以返回格林威治标准时，date()则为当地时
	}
}

// 格式化帖子、回复内容
function set_content($text, $spider = '0')
{
	global $options, $is_client;
	// images
	$img_re = '/(http[s]?:\/\/?(' . $options['safe_imgdomain'] . ').+\.(jpg|jpe|jpeg|gif|png))\w*/i'; //参数i，大小写不敏感
	if (preg_match($img_re, $text)) {
		if (!$spider) {
			//$text = preg_replace($img_re, '<a href="\1" target="_blank" rel="nofollow"><img src="'.$options['base_url'].'/static/grey2.gif" data-original="\1" alt="" /></a>', $text);
			$text = preg_replace($img_re, '<a href="\1" target="_blank" rel="nofollow"><img src="\1" alt="" /></a>', $text);
			//$text = preg_replace($img_re, '<img src="\1" alt="" />', $text);
		} else {
			// 搜索引擎来这样显示 更利于SEO 参见 http://saepy.sinaapp.com/t/81
			$text = preg_replace($img_re, '<img src="\1" alt="" />', $text);
		}
	}
	// 腾讯微博图片
	if (strpos($text, 'qpic.cn')) {
		// http://t1.qpic.cn/mblogpic/4c7dfb4b2d3c665c4fa4/460
		$qq_img_re = '/(http:\/\/t\d+\.qpic\.cn\/[a-zA-Z0-9]+\/[a-zA-Z0-9]+)\/\d+\w*/';
		if (!$spider) {
			$text = preg_replace($qq_img_re, '<img src="' . $options['base_url'] . '/static/grey2.gif" data-original="\1/460" alt="" />', $text);
		} else {
			$text = preg_replace($qq_img_re, '<img src="\1/460" alt="" />', $text);
		}
	}
	
	// 各大网站的视频地址格式经常变，能识别一些，不能识别了再改。
	// youku
	if (strpos($text, 'player.youku.com')) {
		$text = preg_replace('/http:\/\/player\.youku\.com\/player\.php\/sid\/([a-zA-Z0-9\=]+)\/v\.swf/', '<embed src="http://player.youku.com/player.php/sid/\1/v.swf" quality="high" width="590" height="492" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed>', $text);
	}
	
	if (strpos($text, 'v.youku.com')) {
		$text = preg_replace('/http:\/\/v\.youku\.com\/v_show\/id_([a-zA-Z0-9\=]+)(\/|\.html?)?/', '<embed src="http://player.youku.com/player.php/sid/\1/v.swf" quality="high" width="590" height="492" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed>', $text);
	}
	// tudou
	if (strpos($text, 'www.tudou.com')) {
		if (strpos($text, 'programs/view')) {
			$text = preg_replace('/http:\/\/www\.tudou\.com\/(programs\/view|listplay)\/([a-zA-Z0-9\=\_\-]+)(\/|\.html?)?/', '<embed src="http://www.tudou.com/v/\2/" quality="high" width="638" height="420" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed>', $text);
		} else if (strpos($text, 'albumplay')) {
			$text = preg_replace('/http:\/\/www\.tudou\.com\/albumplay\/([a-zA-Z0-9\=\_\-]+)\/([a-zA-Z0-9\=\_\-]+)(\/|\.html?)?/', '<embed src="http://www.tudou.com/a/\1/" quality="high" width="638" height="420" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed>', $text);
		} else if (strpos($text, "tudou.com/a/")) {
			//播放器地址
			$text = preg_replace('/(http:\/\/www\.tudou\.com\/a\/([a-zA-Z0-9\=]+)\/\&amp;resourceId\=([0-9\_]+)\&amp;iid\=([0-9\_]+)\/v\.swf)/', '<embed src="\\1" quality="high" width="638" height="420" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed>', $text);
		} else {
			$text = preg_replace('/http:\/\/www\.tudou\.com\/(programs\/view|listplay)\/([a-zA-Z0-9\=\_\-]+)(\/|\.html?)?/', '<embed src="http://www.tudou.com/l/\2/" quality="high" width="638" height="420" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash"></embed>', $text);
		}
	}
	// qq
	if (strpos($text, 'v.qq.com')) {
		if (strpos($text, 'vid=')) {
			$text = preg_replace('/http:\/\/v\.qq\.com\/(.+)vid=([a-zA-Z0-9]{8,})/', '<embed src="http://static.video.qq.com/TPout.swf?vid=\2&auto=0" allowFullScreen="true" quality="high" width="590" height="492" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>', $text);
		} else {
			$text = preg_replace('/http:\/\/v\.qq\.com\/(.+)\/([a-zA-Z0-9]{8,})\.(html?)/', '<embed src="http://static.video.qq.com/TPout.swf?vid=\2&auto=0" allowFullScreen="true" quality="high" width="590" height="492" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>', $text);
		}
	}
	// gist
	if (strpos($text, '://gist')) {
		$text = preg_replace('/(https?:\/\/gist\.github\.com\/([a-zA-Z0-9-]+\/)?[\d]+)/', '<script src="\1.js"></script>', $text);
	}
	// mentions
	if (strpos(' ' . $text, '@')) {
		$text = preg_replace('/\B\@([a-zA-Z0-9\x80-\xff]{4,20})/', '@<a href="' . $options['base_url'] . '/member/\1">\1</a>', $text);
	}
	// url
	if (strpos(' ' . $text, 'http')) {
		$text = ' ' . $text;
		if (!$is_client) {
			$text = preg_replace('`([^"=\'>])((http|https|ftp)://[^\s<]+[^\s<\.)])`i', '$1<a href="$2" target="_blank" rel="nofollow">$2</a>', $text);
		} else {
			$text = preg_replace('`([^"=\'>])((http|https|ftp)://[^\s<]+[^\s<\.)])`i', '$1<a href="javascript:window.open(\'$2\', \'_system\');">$2</a>', $text);
		}
		$text = substr($text, 1);
	}
	
	$text = str_replace("\r\n", '<br/>', $text);
	
	return $text;
}

// 匹配文本里呼叫某人，为了保险，使用时常在其前后加空格，如 @admin 吧
function find_mentions($text, $filter_name = '')
{
	// 正则跟用户注册、登录保持一致
	preg_match_all('/\B\@([a-zA-Z0-9\x80-\xff]{4,20})/', $text, $out, PREG_PATTERN_ORDER);
	$new_arr = array_unique($out[1]);
	if ($filter_name && in_array($filter_name, $new_arr)) {
		foreach ($new_arr as $k => $v) {
			if ($v == $filter_name) {
				unset($new_arr[$k]);
				break;
			}
		}
	}
	return $new_arr;
}

//转换字符
function char_cv($string)
{
	$string = htmlspecialchars(addslashes($string));
	return $string;
}

// 过滤掉一些非法字符
function filter_chr($string)
{
	$string = str_replace('<', '', $string);
	$string = str_replace('>', '', $string);
	return $string;
}

//判断是否为邮件地址
function isemail($email)
{
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

//将数组保存为文件缓存
function array2file($file, $array)
{
	$fp = fopen($file, "wb");
	fwrite($fp, serialize($array));
	fclose($fp);
}

//将数组的文件缓存还原为数组
function file2array($file)
{
	if (!file_exists($file)) {
		exitstr(" does no exist");
	}
	$handle   = fopen($file, "rb");
	$contents = fread($handle, filesize($file));
	fclose($handle);
	return unserialize($contents);
}
//将公选课分类ID和分类名称互换
function elective_course_category($elective_course_keyword)
{
	global $ec_category;
	if ($elective_course_keyword == null) {
		return 0;
	} else if (is_numeric($elective_course_keyword)) {
		return $ec_category[$elective_course_keyword];
	} else {
		foreach ($ec_category as $key => $val) {
			if ($val == $elective_course_keyword) {
				return $key;
				break;
			}
		}
	}
}
//获取头像使用的curl函数
function curl_file_get_contents($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
//格式化文件大小
function formatBytes($size, $precision = 2)
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

//关键字加亮
function KeywordHighlight($content, $keyword)
{
	if ($keyword) {
		$keyword_arr_temp = explode(" ", $keyword);
		foreach ($keyword_arr_temp as $val) {
			$keyword_arr[$val] = '<font color="red">' . $val . '</font>';
		}
		return strtr($content, $keyword_arr);
	} else {
		return $content;
	}
}


//统计
Switch($user_agent)
{
	case 'mozilla/5.0 (compatible; baiduspider/2.0; +http://www.baidu.com/search/spider.html)':
		$device='baiduspider';
		break;
	case 'mozilla/5.0 (compatible; googlebot/2.1; +http://www.google.com/bot.html)':
		$device='googlebot';
		break;
	case 'mozilla/5.0 (linux;u;android 2.3.7;zh-cn;) applewebkit/533.1 (khtml,like gecko) version/4.0 mobile safari/533.1 (compatible; +http://www.baidu.com/search/spider.html)':
		$device='baiduspider mobile';
		break;
	case 'sogou web spider/4.0(+http://www.sogou.com/docs/help/webmasters.htm#07)':
		$device='sogouspider';
		break;
	case 'mozilla/5.0 (compatible; msie 7.0; windows nt 5.1; .net clr 1.1.4322) 360jk yunjiankong':
		$device='360yunjiankong';
		break;
	case 'dnspod-monitor/2.0':
		$device='dnspod-monitor';
		break;
	case 'mediapartners-google':
		$device='google adsense';
		break;
	case 'mozilla/5.0 (compatible; ahrefsbot/5.0; +http://ahrefs.com/robot/)':
		$device='ahref';
		break;
	case 'yisouspider':
		$device='yisouspider';
		break;
	case 'mozilla/5.0 (compatible; mj12bot/v1.4.5; http://www.majestic12.co.uk/bot.php?+)':
		$device='mj12bot';
		break;
	case 'mozilla/4.0 (compatible; msie 6.0; windows nt 5.1; sv1; jiankongbao monitor 1.1)':
		$device='jiankongbao monitor';
		break;
	case 'mozilla/5.0 (compatible; easouspider; +http://www.easou.com/search/spider.html)':
		$device='easouspider';
		break;
	case 'mozilla/5.0 (compatible; msie 9.0; windows nt 6.1; trident/5.0); 360spider(zh-CN)':
		$device='360spider';
		break;
	default:
		$device='User';
		break;
}

$DBS->query("INSERT INTO `jnubbs_logs`(`id`, `ip`, `url`, `referer`, `ua`, `spider`, `device`, `date`,`time`) VALUES (null,'".$onlineip."','http://".addslashes(trim($_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"]))."','".addslashes(trim($_SERVER["HTTP_REFERER"]))."','".addslashes(trim($user_agent))."','".addslashes(trim($is_spider))."','".addslashes(trim($device))."','".date('Y-m-d')."',".$timestamp.")");
?>