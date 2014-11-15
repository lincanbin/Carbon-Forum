<?php
include(dirname(__FILE__) . '/common.php');
header("Content-Type: text/xml");
$Action = Request('POST','action',false);
$Page = intval($_GET['page']);
$CurHost = 'http://' . $_SERVER['HTTP_HOST'];
$ItemPerSitemap  = 30000;
?><?xml version="1.0" encoding="UTF-8" ?>
<?php
if($_SERVER['HTTP_HOST'] == $Config['MobileDomainName'])
	$MobileTag = '<mobile:mobile type="mobile"/>';
else
	$MobileTag = '';
switch ($Action) {
	case 'topics':
	?>
	<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">
	<?php if($Page==1){ ?>
	<url>
		<loc><?php echo $CurHost.'/'; ?></loc>
		<?php echo $MobileTag; ?>
		<lastmod><?php echo date("Y-m-d", $TimeStamp); ?></lastmod>
		<priority>1.0</priority>
	</url>
	<?php
	}
		$TopicsArray = $DB->query('SELECT `ID`, `LastTime`, `Replies` FROM '.$Prefix.'topics force index(LastTime) WHERE IsDel=0 ORDER BY LastTime DESC LIMIT '.($Page-1)*$ItemPerSitemap.','.$ItemPerSitemap);
		foreach ($TopicsArray as $Topic) {?>
	<url>
		<loc><?php echo $CurHost.'/t/'.$Topic['ID']; ?></loc>
		<?php echo $MobileTag; ?>
		<lastmod><?php echo date("Y-m-d", $Topic['LastTime']); ?></lastmod>
		<priority>0.<?php echo $Topic['Replies']>=70?'8':ceil(($Topic['Replies']+10)/10); ?></priority>
	</url>
		<?php } ?>
	</urlset><?php
		break;


	case 'pages':
		?>
	<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/"> 
	<?php
		if(($Page*$ItemPerSitemap) <= ceil($Config['NumTopics']/$Config['TopicsPerPage'])){
			for($i = ($Page-1)*$ItemPerSitemap+1; $i <= $Page*$ItemPerSitemap; $i++)
			{?>
	<url>
		<loc><?php echo $CurHost.'/page/'.$i; ?></loc>
		<?php echo $MobileTag; ?>
		<priority>0.3</priority>
	</url>
	<?php
			}
		}
	?>
	</urlset><?php
		break;
	case 'tags':
		?>
	<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">
	<?php
		$TagsArray = $DB->query('SELECT `Name`, `MostRecentPostTime`, `TotalPosts` FROM '.$Prefix.'tags force index(PRI) ORDER BY ID DESC LIMIT '.($Page-1)*$ItemPerSitemap.','.$ItemPerSitemap);
		foreach ($TagsArray as $Tags) {?>
	<url>
		<loc><?php echo $CurHost.'/tag/'.urlencode($Tags['Name']); ?></loc>
		<?php echo $MobileTag; ?>
		<lastmod><?php echo date("Y-m-d", $Tags['MostRecentPostTime']); ?></lastmod>
		<priority>0.<?php echo $Tags['TotalPosts']>=50?'6':ceil(($Tags['TotalPosts']+10)/10); ?></priority>
	</url>
	<?php } ?>
	</urlset><?php
		break;
	case 'users':
		?>
	<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.baidu.com/schemas/sitemap-mobile/1/">
	<?php
		$UsersArray = $DB->query('SELECT `UserName`, `Topics`, `Replies`, `LastPostTime` FROM '.$Prefix.'users force index(PRI) WHERE UserAccountStatus=1 ORDER BY ID DESC LIMIT '.($Page-1)*$ItemPerSitemap.','.$ItemPerSitemap);
		foreach ($UsersArray as $User) {?>
	<url>
		<loc><?php echo $CurHost.'/u/'.urlencode($User['UserName']); ?></loc>
		<?php echo $MobileTag; ?>
		<lastmod><?php echo date("Y-m-d", $User['LastPostTime']); ?></lastmod>
		<priority>0.<?php echo $User['Topics']+$User['Replies']>=40?'5':ceil(($User['Topics']+$User['Replies'])/10); ?></priority>
	</url>
	<?php } ?>
	</urlset><?php
		break;
	default:
		?>
	<sitemapindex>
	<?php
	//帖子
	for($i = 1; $i <= ceil($Config['NumTopics']/$ItemPerSitemap); $i++)
		echo '<sitemap><loc>',$CurHost,'/sitemap-topics-',$i,".xml</loc></sitemap>\n";
	//帖子页
	for($i = 1; $i <= ceil(ceil($Config['NumTopics']/$Config['TopicsPerPage'])/$ItemPerSitemap); $i++)
		echo '<sitemap><loc>',$CurHost,'/sitemap-pages-',$i,".xml</loc></sitemap>\n";
	//话题
	for($i = 1; $i <= ceil($Config['NumTags']/$ItemPerSitemap); $i++)
		echo '<sitemap><loc>',$CurHost,'/sitemap-tags-',$i,".xml</loc></sitemap>\n";
	//用户
	for($i = 1; $i <= ceil($Config['NumUsers']/$ItemPerSitemap); $i++)
		echo '<sitemap><loc>',$CurHost,'/sitemap-users-',$i,".xml</loc></sitemap>\n";
	?>
	</sitemapindex><?php
		break;
}
?>