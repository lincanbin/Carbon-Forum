<?php
if (PHP_SAPI !== "cli") {
	header('HTTP/1.1 403 Forbidden');
	exit('error: 403 Access Denied');
}

if (PHP_OS === "WINNT") {
	exec('chcp 65001');
}

require(__DIR__ . '/library/URL.class.php');

// 安装
$InstallRequestParameter = array(
	"Language" => "zh-cn",
	"DBHost" => "127.0.0.1",
	"DBName" => "carbon",
	"DBUser" => "root",
	"DBPassword" => "",
	"EnableMemcache" => "true",
	"MemCachePrefix" => "carbon_",
	"SearchServer" => "",
	"SearchPort" => ""
);
$InstallResponse = URL::Post("http://localhost:8080/install/index.php", $InstallRequestParameter);

if (file_exists(__DIR__ . '/config.php')) {
	$ConfigContent = file_get_contents(__DIR__ . '/config.php');
	$ConfigContent = str_replace("define('DEBUG_MODE', false)", "define('DEBUG_MODE', true)", $ConfigContent);
	file_put_contents(__DIR__ . '/config.php', $ConfigContent);
	echo "\n\n\033[32m Installation success \033[0m\n\n";
	exit(0);
} else {
	echo "\n\n\033[31m Installation failed \033[0m\n\n";
	exit(1);
}
