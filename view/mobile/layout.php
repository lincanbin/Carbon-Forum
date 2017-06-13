<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
ob_start();
if(!$IsAjax){
?><!DOCTYPE html>
<html lang="<?php echo $Lang['Language']; ?>">
<head>
	<title><?php
	echo $CurUserID && $CurUserInfo['NewNotification']?str_replace('{{NewMessage}}', $CurUserInfo['NewNotification'], $Lang['New_Message']):'';
	echo $PageTitle;
	echo $UrlPath=='home'?'':' - '.$Config['SiteName']; ?></title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Cache-Control" content="no-siteapp" />
	<meta http-equiv="cleartype" content="on" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui" />
	<meta name="MobileOptimized" content="320" />
	<meta name="HandheldFriendly" content="True" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="mobile-web-app-capable" content="yes" />
	<meta name="full-screen" content="yes" />
	<meta name="browsermode" content="application" />
	<meta name="x5-fullscreen" content="true" />
	<meta name="msapplication-TileColor" content="#0088D1" />
	<meta name="msapplication-TileImage" content="<?php echo $Config['WebsitePath']; ?>/static/img/retinahd_icon.png" />
	<meta name="theme-color" content="#0088D1" />
	<link rel="icon" sizes="192x192" href="<?php echo $Config['WebsitePath']; ?>/static/img/nice-highres.png" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-57x57-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-72x72-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-114x114-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $Config['WebsitePath']; ?>/static/img/apple-touch-icon-144x144-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="180x180" href="<?php echo $Config['WebsitePath']; ?>/static/img/retinahd_icon.png" />
	<link rel="shortcut icon" type="image/ico" href="<?php echo $Config['WebsitePath']; ?>/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/static/css/mobile/appframework.css?version=<?php echo CARBON_FORUM_VERSION; ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo $Config['WebsitePath']; ?>/static/css/mobile/style.css?version=<?php echo CARBON_FORUM_VERSION; ?>" />
	<link rel="search" type="application/opensearchdescription+xml" title="<?php echo mb_substr($Config['SiteName'], 0, 15, 'utf-8'); ?>" href="<?php echo $Config['WebsitePath']; ?>/search.xml" />
	<script type="text/javascript">
		var Prefix="<?php echo PREFIX; ?>";
		var WebsitePath="<?php echo $Config['WebsitePath'];?>";
	</script>
	<script type="text/javascript" charset="utf-8" src="<?php echo $Config['LoadJqueryUrl']; ?>"></script>
	<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/mobile/appframework.ui.min.js?version=<?php echo CARBON_FORUM_VERSION; ?>"></script>
	<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/mobile/global.js?version=<?php echo CARBON_FORUM_VERSION; ?>"></script>
	<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/language/<?php echo ForumLanguage; ?>/global.js?version=<?php echo CARBON_FORUM_VERSION; ?>"></script>
<?php
if ($Config['PageHeadContent']) {
	echo $Config['PageHeadContent'].'
';
}
if (isset($PageMetaKeyword) && $PageMetaKeyword) {
	echo '	<meta name="keywords" content="', $PageMetaKeyword, '" />
';
}
if (isset($PageMetaDesc) && $PageMetaDesc) {
	echo '	<meta name="description" content="', $PageMetaDesc, '" />
';
}
if ( IsSSL() ) {
	echo '	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
';
}
if($Config['MobileDomainName']){
?>
	<meta http-equiv="mobile-agent" content="format=xhtml; url=<?php echo $CurProtocol . $Config['MobileDomainName'] . $RequestURI; ?>" />
<?php } ?>
</head>

<body>
	<!-- this is the main container div.  This way, you can have only part of your app use UI -->
	<div id="mainview" class="view splitview">
		<header>
			<h1><?php echo $PageTitle; ?></h1>
			<a class="menuButton" onclick="javascript:slideout.toggle();"></a>
		</header>
		<div class="pages">
			<div data-title="<?php echo $PageTitle; ?>" id="ID<?php echo md5($PageTitle); ?>" class="panel" selected="true">
				<?php include($ContentFile); ?>
			</div>
		</div>
		<!-- this is the default left side nav menu.  If you do not want any, do not include these -->
		<nav id="menu">
			 <div class="view active" id="navView">
				<header class="header" id="menuHeader"></header>
				<div class="pages">
					 <div class="panel active" id="navPage" style="padding:0!important;" data-title="navPage">
						<?php include($TemplatePath.'sider.php'); ?>
					</div>
				</div>
			</div>
		</nav>
	</div>
<?php
if($CurUserID){
?>
	<div class="view" id="ReplyView">
		<div class="pages">
			<div class="panel" id="Reply">
				<p>
					<br />
					<h1 id="ReplyViewTitle"></h1>
					<br />
				</p>
				<div id="ReplyViewHTML">
				</div>
				<p><a class="button green block" href="#main" data-transition="up-reveal:dismiss" id="ReplyViewSubmitButton"></a></p>
				<p><a class="button block" href="#main" data-transition="up-reveal:dismiss" id="ReplyViewCancelButton"></a></p>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	GetNotification();
	</script>
<?php
}elseif( $UrlPath!='login' ){
?>
	<div class="view" id="LoginView">
		<div class="pages">
			<div class="panel" id="LoginPanel">
				<p>
					<br />
					<h1><?php echo $Lang['Log_In']; ?></h1>
					<br />
				</p>
				<form action="<?php echo $Config['WebsitePath']; ?>/login" method="post" onsubmit="JavaScript:this.Password.value=md5(this.Password.value);">
					<div class="input-group">
						<input type="hidden" value="<?php echo $RequestURI; ?>" name="ReturnUrl" id="ReturnUrl" />
						<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
						<input type="hidden" name="Expires" value="30" />
						<p>
						<input type="text" name="UserName" id="UserName" placeholder="<?php echo $Lang['UserName']; ?>" value="" />
						</p>
						<p>
						<input type="password" name="Password" id="Password" placeholder="<?php echo $Lang['Password']; ?>" value="" />
						</p>
						<p>
							<input type="text" name="VerifyCode" id="VerifyCode" placeholder="<?php echo $Lang['Verification_Code']; ?>" onclick="document.getElementById('Verification_Code_Img').src='<?php echo $Config['WebsitePath']; ?>/seccode.php';" value="" placeholder="<?php echo $Lang['Verification_Code']; ?>" style="width:66%;"/>
							<img src="" id="Verification_Code_Img" style="cursor: pointer;" onclick="this.src+=''" style="width:33%;" align="middle" />
							<br style="clear:both" />
						</p>
						<p>
							<a href="<?php echo $Config['WebsitePath']; ?>/register" class="button"><?php echo $Lang['Sign_Up']; ?></a>
							<input type="submit" class="button" value="<?php echo $Lang['Log_In']; ?>" name="submit" style="float:right;" />
						</p>
						<p>
<?php
$OauthData = json_decode($Config['CacheOauth'], true);
$OauthData = $OauthData?$OauthData:array();
foreach ($OauthData as $Value) {
	if ($Value['AppKey']) {
?>
						<a href="<?php echo $Config['WebsitePath']; ?>/oauth-<?php echo $Value['ID']; ?>" data-ignore="True">
							<img src="<?php echo $Config['WebsitePath'] . $Value['ButtonImageUrl']; ?>" alt="<?php echo $Value['Alias'] . ' ' . $Lang['Log_In']; ?>" />
						</a>&nbsp;&nbsp;
<?php
	}
}
?>
						</p>
						<p><a class="button block" href="#main" data-transition="up-reveal:dismiss" id="LoginViewCancelButton"></a></p>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
}
?>
</body>
</html>
<?php
}else{
?>
<title><?php
	echo $CurUserID && $CurUserInfo['NewNotification']?str_replace('{{NewMessage}}', $CurUserInfo['NewNotification'], $Lang['New_Message']):'';
	echo $PageTitle;
	echo $UrlPath=='home'?'':' - '.$Config['SiteName']; ?></title>
<?php
	include($ContentFile);
	//Pjax
?>
<script>
PageAjaxLoad("<?php echo $PageTitle; ?>", "//<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>");
</script>
<?php
}
$MicroTime = explode(' ', microtime());
$TotalTime = number_format((microtime(true) - $StartTime) * 1000, 3);
header("X-Response-Time: " . $TotalTime . "ms");
ob_end_flush();
?>