<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta content="True" name="HandheldFriendly" />
<?php
if($Config['MobileDomainName']){
?>
<meta http-equiv="mobile-agent" content="format=xhtml; url=http://<?php echo $Config['MobileDomainName'] . $RequestURI; ?>" />
<?php } ?>
<title><?php
echo $CurUserID && $CurUserInfo['NewMessage']?str_replace('{{NewMessage}}', $CurUserInfo['NewMessage'], $Lang['New_Message']):'';
echo $PageTitle;
echo $UrlPath=='index'?'':' - '.$Config['SiteName']; ?></title>
<script type="text/javascript">
var Prefix="<?php echo $Prefix; ?>";
var WebsitePath="<?php echo $Config['WebsitePath'];?>";
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['LoadJqueryUrl']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/global.js?version=<?php echo $Config['Version']; ?>"></script>
<link href="<?php echo $Config['WebsitePath']; ?>/styles/default/theme/style.css?version=<?php echo $Config['Version']; ?>" rel="stylesheet" type="text/css" />
<?php
if ($Config['PageHeadContent']) {
	echo $Config['PageHeadContent'];
}
if (isset($PageMetaKeyword) && $PageMetaKeyword) {
	echo '<meta name="keywords" content="', $PageMetaKeyword, '" />
';
}
if (isset($PageMetaDesc) && $PageMetaDesc) {
	echo '<meta name="description" content="', $PageMetaDesc, '" />
';
}
?>
</head>
<body>
	<!-- content wrapper start -->
	<div class="wrapper">
		<div class="navPanel">
			<div class="innerNavPanel">
				<div class="buttons">
				<a href="<?php echo $Config['WebsitePath']; ?>/"<?php echo $UrlPath=='index'?' class="buttons-active"':''; ?>><?php echo $Lang['Home']; ?></a>
				<!--a href="<?php echo $Config['WebsitePath']; ?>/explore"<?php echo $UrlPath=='explore'?' class="buttons-active"':''; ?>>发现</a-->
				<?php if($CurUserID){ ?>
				<a href="<?php echo $Config['WebsitePath']; ?>/new"<?php echo $UrlPath=='new'?' class="buttons-active"':''; ?>><?php echo $Lang['Create_New_Topic']; ?></a>
				<a href="<?php echo $Config['WebsitePath']; ?>/tags/following"<?php echo $UrlPath=='favorite_tags'?' class="buttons-active"':''; ?>><?php echo $Lang['Tags_Followed']; ?></a>
				<a href="<?php echo $Config['WebsitePath']; ?>/users/following"<?php echo $UrlPath=='favorite_users'?' class="buttons-active"':''; ?>><?php echo $Lang['Users_Followed']; ?></a>

				<a href="<?php echo $Config['WebsitePath']; ?>/settings"<?php echo $UrlPath=='settings'?' class="buttons-active"':''; ?> title="<?php echo $Lang['Settings']; ?>" class="float-right"><div class="icon icon-settings"></div></a>
				<a href="<?php echo $Config['WebsitePath']; ?>/notifications#notifications1"<?php echo $UrlPath=='notifications'?' class="buttons-active"':''; ?> title="<?php echo $Lang['Notifications']; ?>" class="float-right"><div class="icon icon-notifications"></div><?php echo $CurUserInfo['NewMessage']?'<span class="icon-messages-num">'.$CurUserInfo['NewMessage'].'</span>':'';?></a>
				<?php if($CurUserRole==5){?>
				<a href="<?php echo $Config['WebsitePath']; ?>/dashboard"<?php echo $UrlPath=='dashboard'?' class="buttons-active"':''; ?> title="<?php echo $Lang['System_Settings']; ?>" class="float-right"><div class="icon icon-dashboard"></div></a>
				<?php }
				} ?>
				</div>
			</div>
		</div>

		<div class="shadowStrip">&nbsp;</div>

		<!-- main start -->
		<div class="main">
			<?php
			include($ContentFile);
			?>
			<div class="c"></div>
			<a style="display: none; " rel="nofollow" href="#top" id="go-to-top">▲</a>
		</div>
		<!-- main end -->
		<div class="c"></div>

		<!-- footer start -->
		<div class="Copyright">
			<p>
			Power By <a href="http://<?php echo $Lang['Language']; ?>.94cb.com" target="_blank">Carbon Forum V<?php echo $Config['Version']; ?></a> © 2006-2015
			<?php
			if ($IsMobie && $Config['MobileDomainName']) {
			?>
			&nbsp;&nbsp;<a href="http://<?php echo $Config['MainDomainName']; ?>/view-mobile?cookie_prefix=<?php echo urlencode($Config['CookiePrefix']); ?>&website_path=<?php echo urlencode($Config['WebsitePath']); ?>&callback=<?php echo urlencode($Config['MobileDomainName'].$RequestURI); ?>"><?php echo $Lang['Mobile_Version']; ?></a>
			<?php
			}
			?>
			<br />
<?php
$mtime     = explode(' ', microtime());
$totaltime = number_format(($mtime[1] + $mtime[0] - $starttime), 6) * 1000;
?>
			Processed in <?php echo $totaltime; ?> ms, 
			<?php echo $DB->querycount; ?> SQL Queries, 
			<?php echo FormatBytes(memory_get_usage(false)); ?> Memory Usage
			</p>
		</div>
		<!-- footer end -->
	</div>
	<!-- content wrapper end -->
<?php
if ($Config['PageBottomContent']) {
	echo $Config['PageBottomContent'];
}
?>
</body>
</html>
<?php
ob_end_flush();
?>