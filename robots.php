<?php
include(dirname(__FILE__) . '/common.php');
header("Content-Type: text/plain");
?>User-agent: *
Disallow: /dashboard/
Disallow: /favorites
Disallow: /favorites/
Disallow: /json/
Disallow: /login
Disallow: /manage
Disallow: /new
Disallow: /notifications
Disallow: /register
Disallow: /reply
Disallow: /settings
Disallow: /tags/following
Disallow: /tags/following/
Disallow: /users/following
Disallow: /users/following/
Disallow: /upload_controller
<?php
//Crawl-delay: 1
$CurHost = 'http://'.$_SERVER['HTTP_HOST'];
$ItemPerSitemap = 30000;
//帖子
for($i = 1; $i <= ceil($Config['NumTopics']/$ItemPerSitemap); $i++)
	echo 'Sitemap: ',$CurHost,'/sitemap-topics-',$i,".xml\n";
for($i = 1; $i <= ceil(ceil($Config['NumTopics']/$Config['TopicsPerPage'])/$ItemPerSitemap); $i++)
	echo 'Sitemap: ',$CurHost,'/sitemap-pages-',$i,".xml\n";
for($i = 1; $i <= ceil($Config['NumTags']/$ItemPerSitemap); $i++)
	echo 'Sitemap: ',$CurHost,'/sitemap-tags-',$i,".xml\n";
for($i = 1; $i <= ceil($Config['NumUsers']/$ItemPerSitemap); $i++)
	echo 'Sitemap: ',$CurHost,'/sitemap-users-',$i,".xml\n";
?>
