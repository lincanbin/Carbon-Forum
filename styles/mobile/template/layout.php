<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!DOCTYPE html>
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
<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/styles/mobile/theme/icons.css?version=<?php echo $Config['Version']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/styles/mobile/theme/style.css?version=<?php echo $Config['Version']; ?>" />
<script>
var Prefix="<?php echo $Prefix; ?>";
var WebsitePath="<?php echo $Config['WebsitePath'];?>";
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.min.js?version=<?php echo $Config['Version']; ?>"></script>

<script type="text/javascript" charset="utf-8" src="<?php echo $Config['LoadJqueryUrl']; ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.ui.jquery.js?version=<?php echo $Config['Version']; ?>"></script>

<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.ui.min.js?version=<?php echo $Config['Version']; ?>"></script>

<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/appframework.slidemenu.js?version=<?php echo $Config['Version']; ?>"></script>

<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/mobile.global.js?version=<?php echo $Config['Version']; ?>"></script>


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
	<div id="afui" class="ios7"> <!-- this is the main container div.  This way, you can have only part of your app use UI -->
		<!-- this is the header div at the top -->
		<div id="header">
			<a href="javascript:$.ui.toggleLeftSideMenu()" class="button" style="float:left">Toggle Nav</a>
		</div>
		<div id="content">
			<!-- here is where you can add your panels -->
			<div data-title='Welcome' id="main" class="panel" selected="true">This is a basic skeleton UI sample</div>
		</div>
		<!-- bottom navbar. Add additional tabs here -->
		<div id="navbar">
			<div class="horzRule"></div>
			<a href="#main" id='navbar_home' class='icon home'>home</a>
		</div>
		<!-- this is the default left side nav menu.  If you do not want any, do not include these -->
		<nav>
			<header class="header"><h1>Left Menu</h1></header>
			<?php
			//include($ContentFile);
			?>
			<ul class="list">
				<li class="divider">Home</li>
				<li>
					<a  class="icon home mini" href="#main">Selectors</a>
				</li>
			</ul>
		</nav>
	</div>
</body>
</html>