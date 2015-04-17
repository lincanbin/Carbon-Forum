<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
ob_start();
$IsAjax = (array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')?true:false;
if(!$IsAjax){
?><!DOCTYPE html>
<!--HTML5 doctype-->
<html>
<head>
<title><?php
echo $CurUserID && $CurUserInfo['NewMessage']?str_replace('{{NewMessage}}', $CurUserInfo['NewMessage'], $Lang['New_Message']):'';
echo $PageTitle;
echo $UrlPath=='index'?'':' - '.$Config['SiteName']; ?></title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes" />
<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/styles/mobile/theme/appframework.css?version=<?php echo $Config['Version']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/styles/mobile/theme/icons.css?version=<?php echo $Config['Version']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/styles/mobile/theme/style.css?version=<?php echo $Config['Version']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/styles/mobile/theme/appframework.popup.css?version=<?php echo $Config['Version']; ?>" />
<script>
var Prefix="<?php echo $Prefix; ?>";
var WebsitePath="<?php echo $Config['WebsitePath'];?>";
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.min.js?version=<?php echo $Config['Version']; ?>"></script>
<!--script type="text/javascript" charset="utf-8" src="<?php echo $Config['LoadJqueryUrl']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.ui.jquery.js?version=<?php echo $Config['Version']; ?>"></script-->
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.ui.min.js?version=<?php echo $Config['Version']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.slidemenu.js?version=<?php echo $Config['Version']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.popup.js?version=<?php echo $Config['Version']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/mobile.global.js?version=<?php echo $Config['Version']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/editor/lang/<?php echo ForumLanguage; ?>/<?php echo ForumLanguage; ?>.js?version=<?php echo $Config['Version']; ?>"></script>
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
if (isset($canonical)) {
	echo '<link rel="canonical" href="http://', $_SERVER['HTTP_HOST'], $canonical, '" />
';
}
if($Config['MobileDomainName']){
?>
<meta http-equiv="mobile-agent" content="format=xhtml; url=http://<?php echo $Config['MobileDomainName'] . $_SERVER['REQUEST_URI']; ?>" />
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