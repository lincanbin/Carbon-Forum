<?php
include(dirname(__FILE__) . '/common.php');
header("Content-Type: text/plain");
?>User-agent: *
Disallow: <?php echo $Config['WebsitePath']; ?>/dashboard/
Disallow: <?php echo $Config['WebsitePath']; ?>/favorites
Disallow: <?php echo $Config['WebsitePath']; ?>/favorites/
Disallow: <?php echo $Config['WebsitePath']; ?>/json/
Disallow: <?php echo $Config['WebsitePath']; ?>/login
Disallow: <?php echo $Config['WebsitePath']; ?>/manage
Disallow: <?php echo $Config['WebsitePath']; ?>/new
Disallow: <?php echo $Config['WebsitePath']; ?>/notifications
Disallow: <?php echo $Config['WebsitePath']; ?>/register
Disallow: <?php echo $Config['WebsitePath']; ?>/reply
Disallow: <?php echo $Config['WebsitePath']; ?>/settings
Disallow: <?php echo $Config['WebsitePath']; ?>/tags/following
Disallow: <?php echo $Config['WebsitePath']; ?>/tags/following/
Disallow: <?php echo $Config['WebsitePath']; ?>/users/following
Disallow: <?php echo $Config['WebsitePath']; ?>/users/following/
Disallow: <?php echo $Config['WebsitePath']; ?>/upload_controller
<?php
//Crawl-delay: 1
$CurHost        = 'http://' . $_SERVER['HTTP_HOST'] . $Config['WebsitePath'];
$ItemPerSitemap = 30000;
//sitemap 索引
echo 'Sitemap: ', $CurHost, "/sitemap-index.xml\n";
//帖子
for ($i = 1; $i <= ceil($Config['NumTopics'] / $ItemPerSitemap); $i++)
	echo 'Sitemap: ', $CurHost, '/sitemap-topics-', $i, ".xml\n";
//帖子页
for ($i = 1; $i <= ceil(ceil($Config['NumTopics'] / $Config['TopicsPerPage']) / $ItemPerSitemap); $i++)
	echo 'Sitemap: ', $CurHost, '/sitemap-pages-', $i, ".xml\n";
//话题
for ($i = 1; $i <= ceil($Config['NumTags'] / $ItemPerSitemap); $i++)
	echo 'Sitemap: ', $CurHost, '/sitemap-tags-', $i, ".xml\n";
//用户
for ($i = 1; $i <= ceil($Config['NumUsers'] / $ItemPerSitemap); $i++)
	echo 'Sitemap: ', $CurHost, '/sitemap-users-', $i, ".xml\n";
?>