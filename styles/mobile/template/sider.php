<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<header class="header" id="menu_header"></header>
<ul class="list">
	<li class="divider">
		<?php echo $Lang['Information_Bar']; ?>
	</li>
	<li>
		<a class="icon home" href="<?php echo $Config['WebsitePath']; ?>/" data-transition="slide" data-persist-ajax="true" data-refresh="true"><?php echo $Lang['Home']; ?></a>
	</li>
	<li>
		<a class="icon pin" href="<?php echo $Config['WebsitePath']; ?>/tags" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Hot_Tags']; ?></a>
	</li>
	<li>
		<a class="icon question" href="JavaScript:Search();" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Search']; ?></a>
	</li>
<?php
if($CurUserID){
?>
	<li>
		<a class="icon add" href="<?php echo $Config['WebsitePath']; ?>/new" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Create_New_Topic']; ?></a>
	</li>
<?php 
}
if(!$CurUserID && $UrlPath!='login'){
?>
	<form action="<?php echo $Config['WebsitePath']; ?>/login" method="post" onsubmit="JavaScript:this.Password.value=md5(this.Password.value);">
		<input type="hidden" value="<?php echo $RequestURI; ?>" name="ReturnUrl" id="ReturnUrl" />
		<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
		<input type="hidden" name="Expires" value="30" />
		<li class="divider"><?php echo $Lang['Log_In']; ?></li>
		<li>
			<input type="text" name="UserName" value="" placeholder="<?php echo $Lang['UserName']; ?>" />
			<br />
			<input type="password" name="Password" value="" placeholder="<?php echo $Lang['Password']; ?>" /><br />
			<input type="text" style="width:60%;" name="VerifyCode" onfocus="document.getElementById('Verification_Code_Img').src='<?php echo $Config['WebsitePath']; ?>/seccode.php';document.getElementById('Verification_Code_Img').style.display='inline';" value="" placeholder="<?php echo $Lang['Verification_Code']; ?>" /> <img src="" id="Verification_Code_Img" style="cursor: pointer;display:none;" onclick="this.src+=''" align="middle" />
			<br />
			<input type="submit" class="button" value="<?php echo $Lang['Log_In']; ?>" name="submit" />
			<br />
<?php
$OauthData = json_decode($Config['CacheOauth'], true);
$OauthData = $OauthData?$OauthData:array();
foreach ($OauthData as $Value) {
	if ($Value['AppKey']) {
?>
	<a href="<?php echo $Config['WebsitePath']; ?>/oauth-<?php echo $Value['ID']; ?>" data-ignore="True">
		<img src="<?php echo $Config['WebsitePath'] . $Value['LogoUrl']; ?>" alt="<?php echo $Value['Alias'] . ' ' . $Lang['Log_In']; ?>" />
	</a>&nbsp;
<?php
	}
}
?>
		</li>
	</form>
	<li><a class="icon new" href="<?php echo $Config['WebsitePath']; ?>/register" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Sign_Up']; ?></a></li>
	<?php }else if($CurUserID){ ?>
	<li class="divider"><?php echo $Lang['User_Panel']; ?></li>
	<li>
		<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($CurUserName); ?>">
			<?php echo GetAvatar($CurUserID, $CurUserName, 'small'); ?>&nbsp;&nbsp;<span style="line-height:24px;vertical-align: top;font-size:20px;font-weight:bold;"><?php echo $CurUserName; ?></span>
		</a>
	</li>
	<li>
		<a class="icon message" href="<?php echo $Config['WebsitePath']; ?>/notifications" data-refresh="true">
			<?php echo $Lang['Notifications']; ?>
			<span class="af-badge" id="MessageNumber" style="visibility:hidden;"><?php echo $CurUserInfo['NewMessage']; ?></span>
		</a>
	</li>
	<li>
		<a class="icon settings" href="<?php echo $Config['WebsitePath']; ?>/settings" data-transition="slide" data-persist-ajax="true">
			<?php echo $Lang['Settings']; ?>
		</a>
	</li>
	<li>
		<a class="icon star" href="<?php echo $Config['WebsitePath']; ?>/favorites" data-transition="slide" data-persist-ajax="true">
			<?php echo $Lang['Favorite_Topics']; ?>(<?php echo $CurUserInfo['NumFavTopics']; ?>)
		</a>
	</li>
	<li>
		<a class="icon tag" href="<?php echo $Config['WebsitePath']; ?>/tags/following" data-transition="slide" data-persist-ajax="true">
			<?php echo $Lang['Tags_Followed']; ?>(<?php echo $CurUserInfo['NumFavTags']; ?>)
		</a>
	</li>
	<li>
		<a class="icon user" href="<?php echo $Config['WebsitePath']; ?>/users/following" data-transition="slide" data-persist-ajax="true">
			<?php echo $Lang['Users_Followed']; ?>(<?php echo $CurUserInfo['NumFavUsers']; ?>)
		</a>
	</li>

	<li>
		<a class="icon close" href="<?php echo $Config['WebsitePath']; ?>/login?logout=<?php echo $CurUserCode; ?>" data-ignore="True"><?php echo $Lang['Log_Out']; ?></a>
	</li>
<?php
}
?>
	<li>
		<a class="icon tv" href="//<?php echo $Config['MainDomainName'].$Config['WebsitePath']; ?>/view-desktop?cookie_prefix=<?php echo urlencode($Config['CookiePrefix']); ?>&website_path=<?php echo urlencode($Config['WebsitePath']); ?>&callback=<?php echo urlencode($Config['MainDomainName'].$RequestURI); ?>" data-ignore="True">
			<?php echo $Lang['Desktop_Version']; ?>
		</a>
	</li>
</ul>