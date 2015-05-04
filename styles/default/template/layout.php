<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
ob_start();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $Lang['Language']; ?>" lang="<?php echo $Lang['Language']; ?>">
<head>
	<meta name="renderer" content="webkit" />
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<?php
if($Config['MobileDomainName']){
?>
	<meta http-equiv="mobile-agent" content="format=xhtml; url=http://<?php echo $Config['MobileDomainName'] . $RequestURI; ?>" />
<?php
}
if (isset($PageMetaKeyword) && $PageMetaKeyword) {
	echo '	<meta name="keywords" content="', $PageMetaKeyword, '" />
';
}
if (isset($PageMetaDesc) && $PageMetaDesc) {
	echo '	<meta name="description" content="', $PageMetaDesc, '" />
';
}
?>
	<meta name="msapplication-TileImage" content="<?php echo $Config['WebsitePath']; ?>/static/img/retinahd_icon.png" />
	<title><?php
echo $CurUserID && $CurUserInfo['NewMessage']?str_replace('{{NewMessage}}', $CurUserInfo['NewMessage'], $Lang['New_Message']):'';
echo $PageTitle;
echo $UrlPath=='index'?'':' - '.$Config['SiteName']; ?></title>
	<link rel="apple-touch-icon-precomposed" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-57x57-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-72x72-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-114x114-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-144x144-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="180x180" href="<?php echo $Config['WebsitePath']; ?>/static/img/retinahd_icon.png" />
	<link rel="shortcut icon" type="image/ico" href="<?php echo $Config['WebsitePath']; ?>/favicon.ico" />
	<link href="<?php echo $Config['WebsitePath']; ?>/styles/default/theme/style.css?version=<?php echo $Config['Version']; ?>" rel="stylesheet" type="text/css" />
	<link rel="search" type="application/opensearchdescription+xml" title="<?php echo mb_substr($Config['SiteName'], 0, 15, 'utf-8'); ?>" href="<?php echo $Config['WebsitePath']; ?>/search.xml" />
	<script type="text/javascript">
	var Prefix="<?php echo $Prefix; ?>";
	var WebsitePath="<?php echo $Config['WebsitePath'];?>";
	</script>
	<script type="text/javascript" charset="utf-8" src="<?php echo $Config['LoadJqueryUrl']; ?>"></script>
	<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/global.js?version=<?php echo $Config['Version']; ?>"></script>
	<?php echo $Config['PageHeadContent']; ?>
</head>
<body>
	<!-- content wrapper start -->
	<div class="wrapper">
		<div class="navPanel">
			<div class="innerNavPanel">
				<div class="logo">
					<a href="<?php echo $Config['WebsitePath']; ?>/">
						<img src="<?php echo $Config['WebsitePath']; ?>/static/img/logo.png" alt="<?php echo $Lang['Home']; ?>" />
					</a>
				</div>
				<div class="buttons">
				<div class="searchbox">
					<input type="text" id="SearchInput" onkeydown="javascript:if((event.keyCode==13)&&(this.value!='')){$('#SearchButton').trigger('click');}" placeholder="<?php echo $Lang['Search']; ?>"<?php echo $UrlPath=='search'&&!empty($Keyword)?' value="'.$Keyword.'"':'';?> />
					<a href="###" id="SearchButton"><div class="icon icon-search"></div></a>
				</div>
				
				
<?php
if($CurUserID){
?>
				
				
				

				<a href="<?php echo $Config['WebsitePath']; ?>/settings" title="<?php echo $Lang['Settings']; ?>"<?php echo $UrlPath=='settings'?' class="buttons-active"':''; ?>><div class="icon icon-settings"></div></a>
				<a href="<?php echo $Config['WebsitePath']; ?>/notifications#notifications1" title="<?php echo $Lang['Notifications']; ?>"<?php echo $UrlPath=='notifications'?' class="buttons-active"':''; ?>><div class="icon icon-notifications"></div><?php echo $CurUserInfo['NewMessage']?'<span class="icon-messages-num">'.$CurUserInfo['NewMessage'].'</span>':'';?></a>
<?php
if($CurUserRole==5){
?>
				<a href="<?php echo $Config['WebsitePath']; ?>/dashboard" title="<?php echo $Lang['System_Settings']; ?>"<?php echo $UrlPath=='dashboard'?' class="buttons-active"':''; ?>><div class="icon icon-dashboard"></div></a>
<?php }
?>
				<a href="<?php echo $Config['WebsitePath']; ?>/users/following"<?php echo $UrlPath=='favorite_users'?' class="buttons-active"':''; ?>><?php echo $Lang['Users_Followed']; ?></a>
				<a href="<?php echo $Config['WebsitePath']; ?>/tags/following"<?php echo $UrlPath=='favorite_tags'?' class="buttons-active"':''; ?>><?php echo $Lang['Tags_Followed']; ?></a>
				<a href="<?php echo $Config['WebsitePath']; ?>/new"<?php echo $UrlPath=='new'?' class="buttons-active"':''; ?>><?php echo $Lang['Create_New_Topic']; ?></a>
<?php
}else{
?>
				<a href="<?php echo $Config['WebsitePath']; ?>/register"<?php echo $UrlPath=='register'?' class="buttons-active"':''; ?>>
					<?php echo $Lang['Sign_Up']; ?>
				</a>
				<a href="<?php echo $Config['WebsitePath']; ?>/login"<?php echo $UrlPath=='login'?' class="buttons-active"':''; ?>>
					<?php echo $Lang['Log_In']; ?>
				</a>
<?php
}
?>
				<!--a href="<?php echo $Config['WebsitePath']; ?>/explore"<?php echo $UrlPath=='explore'?' class="buttons-active"':''; ?>>发现</a-->
				<a href="<?php echo $Config['WebsitePath']; ?>/"<?php echo $UrlPath=='index'?' class="buttons-active"':''; ?>>
					<?php echo $Lang['Home']; ?>
				</a>
				</div>
				<div class="c"></div>
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
			Power By <a href="http://www.94cb.com" target="_blank">Carbon Forum</a> V<?php echo $Config['Version']; ?> © 2006-2015
			<?php
			if ($IsMobile && $Config['MobileDomainName']) {
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