<?php
require(dirname(__FILE__)."/common.php");
$id = intval($_GET['id']);
$Page = intval(Request('Get', 'page'));
$topic = $DB->row("SELECT * FROM ".$Prefix."topics force index(PRI) Where ID=:id",array("id"=>$id));
if(!$topic || ($topic['IsDel'] && $CurUserRole<3))
{
	AlertMsg('帖子不存在','帖子不存在',404);
}
$TotalPage = ceil(($topic['Replies']+1)/$Config['PostsPerPage']);
if($Page<0 || $Page==1){
	header('location: '.$Config['WebsitePath'].'/t/'.$id);
	exit;
}
if($Page>$TotalPage){
	header('location: '.$Config['WebsitePath'].'/t/'.$id.'-'.$TotalPage);
	exit;
}
if($Page == 0) $Page = 1;
$PostsArray = $DB->query("SELECT * FROM ".$Prefix."posts force index(TopicID) Where TopicID=:id ORDER BY PostTime ASC LIMIT ".($Page-1)*$Config['PostsPerPage'].",".$Config['PostsPerPage'],array("id"=>$id));
$IsFavorite = $DB->single("SELECT ID FROM ".$Prefix."favorites Where UserID=:UserID and Type=1 and FavoriteID=:FavoriteID",array('UserID'=>$CurUserID, 'FavoriteID'=>$id));
$DB->query("UPDATE ".$Prefix."topics force index(PRI) SET Views = Views+1,LastViewedTime = :LastViewedTime Where ID=:id",array("LastViewedTime"=>$TimeStamp,"id"=>$id));
$DB->CloseConnection();
$PageTitle = $topic['Topic'];
$PageTitle .= $Page>1?' Page'.$Page:'';
$PageMetaDesc = htmlspecialchars(strip_tags(mb_substr($PostsArray[0]['Content'], 0, 150, 'utf-8')));
$PageMetaKeyword = str_replace('|',',',$topic['Tags']);
$ContentFile = $TemplatePath.'topic.php';
include($TemplatePath.'layout.php');
?>