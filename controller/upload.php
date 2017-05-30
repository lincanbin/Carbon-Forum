<?php
//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
//error_reporting(E_ERROR);
if (is_file(LibraryPath . 'Uploader.config.json')) {
	$UploadConfig = JsonDecode(file_get_contents(LibraryPath . 'Uploader.config.json'));
} else {
	$UploadConfig = JsonDecode(file_get_contents(LibraryPath . 'Uploader.config.template.json'));
}
$action = $_GET['action'];

switch ($action) {
	case 'config':
		$result = json_encode($UploadConfig);
		break;
	/* 上传图片 */
	case 'uploadimage':
		/* 上传涂鸦 */
	case 'uploadscrawl':
		/* 上传视频 */
	case 'uploadvideo':
		/* 上传文件 */
	case 'uploadfile':
		$result = include("upload_file.php");
		break;
	/* 列出图片 */
	case 'listimage':
		$result = include("upload_list.php");
		break;
	/* 列出文件 */
	case 'listfile':
		$result = include("upload_list.php");
		break;
	/* 抓取远程文件 */
	case 'catchimage':
		$result = include("upload_crawler.php");
		break;

	default:
		$result = json_encode(array(
			'state' => '请求地址出错'
		));
		break;
}

/* 输出结果 */
if (isset($_GET["callback"])) {
	if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
		echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
	} else {
		echo json_encode(array(
			'state' => 'callback参数不合法'
		));
	}
} else {
	echo $result;
}