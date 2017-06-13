<?php
if (PHP_SAPI !== "cli") {
	header('HTTP/1.1 403 Forbidden');
	exit('error: 403 Access Denied');
}

if (PHP_OS === "WINNT") {
	exec('chcp 65001');
}

define('TestHTTPPort', '8099');
define('TestMainDomainName', 'local.94cb.com:' . TestHTTPPort);
define('TestMobileDomainName', 'local-m.94cb.com:' . TestHTTPPort);
define('TestAppDomainName', 'local-api.94cb.com:' . TestHTTPPort);
$Passed = 0;
$Failed = 0;

require(__DIR__ . "/config.php");
require(__DIR__ . "/library/PDO.class.php");
$DB = new Db(DBHost, DBPort, DBName, DBUser, DBPassword);

function AutoTest($Method, $View, $URL, $Parameters = [], $ExpectedStatusCode = 200, $Callback = null)
{
	global $APISignature, $Passed, $Failed;
	$Pass = true;
	echo "\n\033[33m --------$Method  $URL expect $ExpectedStatusCode-------- \033[0m\n\n";
	switch ($View) {
		case 'api':
			$URL = "http://" . TestAppDomainName . $URL;
			$Parameters['SKey'] = array_keys($APISignature)[0];
			$Parameters['STime'] = time();
			$Parameters['SValue'] = md5($Parameters['SKey'] . $APISignature[$Parameters['SKey']] . $Parameters['STime']);
			break;
		case 'mobile':
			$URL = "http://" . TestMobileDomainName . $URL;
			break;
		default:
			$URL = "http://" . TestMainDomainName . $URL;
			break;
	}


	//echo $URL . "\n";
	//var_dump($Parameters);
	if ($Method === 'GET') {
		$URL .= '?' . http_build_query($Parameters);
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0) CarbonForum/5.0");
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $Method);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($Parameters));
	curl_setopt($ch, CURLOPT_URL, $URL);

	$StartTime = microtime(true);
	$Response = curl_exec($ch);

	$Info = curl_getinfo($ch);
	curl_close($ch);
	//var_dump($Response);
	if ($Callback == null) {
		$Callback = function ($Response, &$Pass) use ($View) {
			if ($View === 'api') {
				$Result = json_decode($Response, true);
				if ($Result === false) {
					$Pass = false;
				} else {
					if (isset($Result['Status']) && $Result['Status'] === 0) {
						$Pass = false;
					}
					echo json_encode($Result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
				}
			} else {
				echo implode("\n", array_filter(explode("\n", strip_tags($Response)), 'trim')) . "\n";
			}
		};
	}
	$Callback($Response, $Pass);

	if ($Info['http_code'] !== $ExpectedStatusCode) {
		$Pass = false;
		echo "\n\033[31m expect $ExpectedStatusCode but got {$Info['http_code']}\033[0m\n";
	}
	if ($Pass === false) {
		$Failed += 1;
		echo "\n\033[31m failed\033[0m\n";
	} else {
		$Passed += 1;
		echo "\n\033[32m passed\033[0m\n";
	}
	echo "\n\033[36m " . number_format((microtime(true) - $StartTime) * 1000, 3) . "ms \033[0m\n";
	echo "\n\033[33m -------------------------------------------------------- \033[0m\n";
	echo "\n\n\n\n\n\n\n";
	return json_decode($Response, true);
}

// 设置域名
$DB->query("UPDATE `" . PREFIX . "config` SET `ConfigValue`='" . TestMainDomainName . "' WHERE `ConfigName`='MainDomainName'");
$DB->query("UPDATE `" . PREFIX . "config` SET `ConfigValue`='" . TestMobileDomainName . "' WHERE `ConfigName`='MobileDomainName'");
$DB->query("UPDATE `" . PREFIX . "config` SET `ConfigValue`='" . TestAppDomainName . "' WHERE `ConfigName`='AppDomainName'");

// 开始测试
AutoTest('GET', 'api', '/404', [], 404);

//Register Test
$DB->query("DELETE FROM `" . PREFIX . "users` WHERE UserName = ?;", ['lincanbin_test']);

foreach (range(1, 15 + mt_rand(-5, 5)) as $i) {
	$RegisterData = [
		'UserName' => 'lincanbin_test' . $i,
		'Email' => 'lincanbin' . $i . '@hotmail.com',
		'Password' => md5('password+1s'),
		'VerifyCode' => 1234
	];
	AutoTest('POST', 'api', '/register', $RegisterData, 200);
}


//Log in Test
$LoginData = [
	'UserName' => 'lincanbin_test1',
	'Password' => md5('password+1s'),
	'VerifyCode' => 1234
];
$UserAuthenticationArray = AutoTest('POST', 'api', '/login', $LoginData, 200);
$UserAuthenticationParameters = [
	'AuthUserID' => $UserAuthenticationArray['UserID'],
	'AuthUserExpirationTime' => $UserAuthenticationArray['UserExpirationTime'],
	'AuthUserCode' => $UserAuthenticationArray['UserCode']
];

$LastestTopicID = '1';
// Create New Topic Test
foreach (range(1, 50 + mt_rand(-15, 15)) as $i) {
	//sleep(9);
	$NewData = array_merge($UserAuthenticationParameters, [
		'Title' => 'The test was carried out by Canbin Lin.' . $i,
		'Tag[]' => 'lincanbin' . mt_rand(1, 5),
		'Content' => $i . 'aaa<br />bbb<p>ccc</p><script></script>'
	]);
	$LastestTopicInfo = AutoTest('POST', 'api', '/new', $NewData, 200);
	$LastestTopicID = $LastestTopicInfo['TopicID'];

	// Reply to topic Test
	foreach (range(1, mt_rand(1, 50)) as $j) {
		//sleep(9);
		$ReplyData = array_merge($UserAuthenticationParameters, [
			'TopicID' => $LastestTopicID,
			'Content' => $j . 'reply<br />aaa<br />bbb<p>ccc</p><script></script>'
		]);
		$LastestTopicInfo = AutoTest('POST', 'api', '/reply', $ReplyData, 200);
	}
}
// Get the Topic Information and Replies Test
AutoTest('GET', 'api', '/t/' . $LastestTopicID, [], 200);

// Homepage
AutoTest('GET', 'api', '/', [], 200);
AutoTest('GET', 'api', '/page/2', [], 200);
AutoTest('GET', 'pc', '/', [], 200);
AutoTest('GET', 'pc', '/page/2', [], 200);


// 安装完后打印数据库信息
foreach ($DB->query("show table status;") as $Table) {
	echo sprintf("%20s | %10d | %20s \n", $Table["Name"], $Table["Rows"], $Table["Update_time"]);
}

$DB->CloseConnection();

echo "\n\n\033[32m $Passed passed \033[0m\n\n";

if ($Failed === 0) {
	exit(0);
} else {
	echo "\033[31m $Failed failed \033[0m\n\n";
	exit(1);
}
