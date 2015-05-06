<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
ob_start();
$IsAjax = (array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')?true:false;
if(!$IsAjax){
?><!DOCTYPE html>
<html lang="<?php echo $Lang['Language']; ?>">
<head>
<title><?php
echo $CurUserID && $CurUserInfo['NewMessage']?str_replace('{{NewMessage}}', $CurUserInfo['NewMessage'], $Lang['New_Message']):'';
echo $PageTitle;
echo $UrlPath=='index'?'':' - '.$Config['SiteName']; ?></title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="mobile-web-app-capable" content="yes" />
<meta name="full-screen" content="yes" />
<meta name="browsermode" content="application" />
<meta name="x5-fullscreen" content="true" />
<meta name="msapplication-TileColor" content="#F9F9F9" />
<meta name="msapplication-TileImage" content="<?php echo $Config['WebsitePath']; ?>/static/img/retinahd_icon.png" />
<meta name="theme-color" content="#F9F9F9" />
<link rel="icon" sizes="192x192" href="<?php echo $Config['WebsitePath']; ?>/static/img/nice-highres.png" />
<link rel="apple-touch-icon-precomposed" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-57x57-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-72x72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-114x114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-144x144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="180x180" href="<?php echo $Config['WebsitePath']; ?>/static/img/retinahd_icon.png" />
<link rel="shortcut icon" type="image/ico" href="<?php echo $Config['WebsitePath']; ?>/favicon.ico" />
<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/styles/mobile/theme/appframework.css?version=<?php echo $Config['Version']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/styles/mobile/theme/icons.css?version=<?php echo $Config['Version']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/styles/mobile/theme/style.css?version=<?php echo $Config['Version']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/styles/mobile/theme/appframework.popup.css?version=<?php echo $Config['Version']; ?>" />
<link rel="search" type="application/opensearchdescription+xml" title="<?php echo mb_substr($Config['SiteName'], 0, 15, 'utf-8'); ?>" href="<?php echo $Config['WebsitePath']; ?>/search.xml" />
<script type="text/javascript">
var Prefix="<?php echo $Prefix; ?>";
var WebsitePath="<?php echo $Config['WebsitePath'];?>";
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.min.js?version=<?php echo $Config['Version']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.ui.min.js?version=<?php echo $Config['Version']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.slidemenu.js?version=<?php echo $Config['Version']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.popup.js?version=<?php echo $Config['Version']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/mobile.global.js?version=<?php echo $Config['Version']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/language/<?php echo ForumLanguage; ?>/<?php echo ForumLanguage; ?>.js?version=<?php echo $Config['Version']; ?>"></script>
<?php
/*
if ($Config['PageHeadContent']) {
	echo $Config['PageHeadContent'];
}
*/
if (isset($PageMetaKeyword) && $PageMetaKeyword) {
	echo '<meta name="keywords" content="', $PageMetaKeyword, '" />
';
}
if (isset($PageMetaDesc) && $PageMetaDesc) {
	echo '<meta name="description" content="', $PageMetaDesc, '" />
';
}
if($Config['MobileDomainName']){
?>
<meta http-equiv="mobile-agent" content="format=xhtml; url=http://<?php echo $Config['MobileDomainName'] . $RequestURI; ?>" />
<?php } ?>
</head>

<body>
	<!-- this is the main container div.  This way, you can have only part of your app use UI -->
	<div id="afui" class="ios7">
		<?php include($ContentFile); ?>
	</div>
</body>
</html>
<?php
}else{
	include($ContentFile);
	//Pjax
?>
<script>
PageAjaxLoad("<?php echo $PageTitle; ?>", "http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>");
</script>
<?php
}
ob_end_flush();
?>