<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh-CN" lang="zh-CN">
<head>
<meta charset="UTF-8" />
<meta content="True" name="HandheldFriendly" />
<title><?php
echo $CurUserID && $CurUserInfo['NewMessage']?'('.$CurUserInfo['NewMessage'].'条消息) ':'';
echo $PageTitle;
echo $UrlPath=='index'?'':'-'.$Config['SiteName']; ?></title>
<script>
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
	<!-- content wrapper start -->
	<div class="wrapper">
		<div class="navPanel">
			<div class="innerNavPanel">
				<div class="buttons">
				<a href="<?php echo $Config['WebsitePath']; ?>/"<?php echo $UrlPath=='index'?' class="buttons-active"':''; ?>>首页</a>
				<!--a href="<?php echo $Config['WebsitePath']; ?>/explore"<?php echo $UrlPath=='explore'?' class="buttons-active"':''; ?>>发现</a-->
				<?php if($CurUserID){ ?>
				<a href="<?php echo $Config['WebsitePath']; ?>/new"<?php echo $UrlPath=='new'?' class="buttons-active"':''; ?>>发新帖</a>
				<a href="<?php echo $Config['WebsitePath']; ?>/settings"<?php echo $UrlPath=='settings'?' class="buttons-active"':''; ?> style="float:right;"><span class="icon icon-action-settings"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
				<a href="<?php echo $Config['WebsitePath']; ?>/notifications#notifications2"<?php echo $UrlPath=='notifications'?' class="buttons-active"':''; ?> style="float:right;"><span style="position: relative;"><span class="icon icon-action-newmessages"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $CurUserInfo['NewMessage']?'<span style="color:#FFFFFF;position: absolute;left: 60%;margin-left: 1px;top: -8px;font-size: 10px;line-height: 1.4;background-color: #ff3b30;padding: 1px 5px !important;border-radius: 50%;">'.$CurUserInfo['NewMessage'].'</span>':'';?></span></a>
				<?php if($CurUserRole==5){?>
				<a href="<?php echo $Config['WebsitePath']; ?>/dashboard"<?php echo $UrlPath=='dashboard'?' class="buttons-active"':''; ?> style="float:right;"><span class="icon icon-action-dashboard"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
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
		</div>
		<!-- main end -->
		<div class="c"></div>

		<!-- footer start -->
		<div class="Copyright">
			<p>
			Power By <a href="http://www.94cb.com" target="_blank">Carbon Forum V<?php echo $Config['Version']; ?></a> © 2006-2014
			<?php
			if ($IsMobie) {
			?>
			&nbsp;&nbsp;<!--a href="http://<?php echo $Config['MobileDomainName']; ?>/">手机模式</a-->
			<?php
			}
			?>
			<br />
			<?php
			$mtime     = explode(' ', microtime());
			$totaltime = number_format(($mtime[1] + $mtime[0] - $starttime), 6) * 1000;
			?>
			Processed in <?php
			echo $totaltime;
			?> ms, <?php
			echo $DB->querycount;
			?> SQL Queries<!--, Memory usage: <?php
			echo FormatBytes(memory_get_usage(false));
			?>-->
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