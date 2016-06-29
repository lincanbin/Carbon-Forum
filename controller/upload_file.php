<?php
include(LibraryPath . 'Uploader.class.php');

SetStyle('api', 'API');
header("Content-Type: text/html; charset=utf-8");
Auth(1, 0, true);

/* 上传配置 */
$base64 = "upload";
switch (htmlspecialchars($_GET['action'])) {
	case 'uploadimage':
		$config    = array(
			"pathFormat" => $Config['WebsitePath'] . $UploadConfig['imagePathFormat'],
			"maxSize" => $UploadConfig['imageMaxSize'],
			"allowFiles" => $UploadConfig['imageAllowFiles']
		);
		$fieldName = $UploadConfig['imageFieldName'];
		break;
	case 'uploadscrawl':
		$config    = array(
			"pathFormat" => $Config['WebsitePath'] . $UploadConfig['scrawlPathFormat'],
			"maxSize" => $UploadConfig['scrawlMaxSize'],
			"allowFiles" => $UploadConfig['scrawlAllowFiles'],
			"oriName" => "scrawl.png"
		);
		$fieldName = $UploadConfig['scrawlFieldName'];
		$base64    = "base64";
		break;
	case 'uploadvideo':
		$config    = array(
			"pathFormat" => $Config['WebsitePath'] . $UploadConfig['videoPathFormat'],
			"maxSize" => $UploadConfig['videoMaxSize'],
			"allowFiles" => $UploadConfig['videoAllowFiles']
		);
		$fieldName = $UploadConfig['videoFieldName'];
		break;
	case 'uploadfile':
	default:
		$config    = array(
			"pathFormat" => $Config['WebsitePath'] . $UploadConfig['filePathFormat'],
			"maxSize" => $UploadConfig['fileMaxSize'],
			"allowFiles" => $UploadConfig['fileAllowFiles']
		);
		$fieldName = $UploadConfig['fileFieldName'];
		break;
}

/* 生成上传实例对象并完成上传 */
$up = new Uploader($fieldName, $config, $base64, $CurUserName, $DB);

/**
 * 得到上传文件所对应的各个参数,数组结构
 * array(
 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
 *     "url" => "",            //返回的地址
 *     "title" => "",          //新文件名
 *     "original" => "",       //原始文件名
 *     "type" => ""            //文件类型
 *     "size" => "",           //文件大小
 * )
 */

/* 返回数据 */
return json_encode($up->getFileInfo());