<?php
require(dirname(__FILE__)."/common.php");

$UserName = Request('Get', 'username');
//$UserID = intval(Request('Get', 'userid'));
$UserInfo = array();
/*if($UserName)
{*/
	$UserInfo = $DB->row('SELECT * FROM '.$Prefix.'users Where UserName=:UserName',array('UserName'=>$UserName));
/*}else if($UserID){
	$UserInfo = $DB->row('SELECT * FROM '.$Prefix.'users Where ID=:UserID',array('UserID'=>$UserID));
}*/
if(!$UserInfo)
{
	AlertMsg('用户不存在','用户不存在',404);
}
$PostsArray = $DB->query('SELECT * FROM '.$Prefix.'posts Where UserName=:UserName ORDER BY PostTime DESC LIMIT 30',array('UserName'=>$UserInfo['UserName']));
$DB->CloseConnection();
// 页面变量
$PageTitle = $UserInfo['UserName'];
$PageMetaDesc = $UserInfo['UserName'].' - '.htmlspecialchars(strip_tags(mb_substr($UserInfo['UserIntro'], 0, 150, 'utf-8')));
$ContentFile = $TemplatePath.'user.php';
include($TemplatePath.'layout.php');
?>