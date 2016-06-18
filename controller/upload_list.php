<?php
include(LibraryPath . 'Uploader.class.php');

SetStyle('api', 'API');
//header("Content-Type: text/html; charset=utf-8");
Auth(1, 0, true);

$Size = isset($_GET['size']) ? intval($_GET['size']) : 20;
$Page = isset($_GET['start']) ? (intval($_GET['start']) / $Size) : 1;

/* 判断类型 */
switch ($_GET['action']) {
	/* 列出文件 */
	case 'listfile':
		$SQL = 'SELECT FilePath as url, Created as mtime FROM ' . PREFIX . 'upload WHERE UserName=:UserName ORDER BY Created DESC LIMIT ' . $Page * $Size . ',' . $Size;
		break;
	/* 列出图片 */
	case 'listimage':
		$SQL = 'SELECT FilePath as url, Created as mtime FROM ' . PREFIX . 'upload WHERE UserName=:UserName and FileType like "image/%" ORDER BY Created DESC LIMIT ' . $Page * $Size . ',' . $Size;
		break;
	default:
		AlertMsg('Bad Request', 'Bad Request');
		break;
		
}
$files = $DB->query($SQL, array(
	'UserName' => $CurUserName
));
if (!count($files)) {
	$result = json_encode(array(
		"state" => "no match file",
		"list" => array(),
		"start" => $Page * $Size,
		"total" => count($files)
	));
} else {
	/* 返回数据 */
	$result = json_encode(array(
		"state" => "SUCCESS",
		"list" => $files,
		"start" => $Page * $Size,
		"total" => count($files)
	));
}
return $result;