<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<?php if(!$CurUserID && $UrlPath != 'login' && $UrlPath != 'register' && $UrlPath != 'oauth'){ ?>
<div class="sider-box">
	<div class="sider-box-title"><?php echo $Lang['Log_In']; ?></div>
	<div class="sider-box-content">
		<div class="center-align" style="width:226px;">
			<form action="<?php echo $Config['WebsitePath']; ?>/login" method="post" onsubmit="JavaScript:this.Password.value=md5(this.Password.value);">
				<input type="hidden" value="<?php echo $RequestURI; ?>" name="ReturnUrl" />
				<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
				<input type="hidden" name="Expires" value="30" />
				<p><label><input type="text" name="UserName" id="UserName" class="w200" value="" placeholder="<?php echo $Lang['UserName']; ?>" onblur="CheckUserNameExist()" /></label></p>
				<p><label><input type="password" name="Password" class="w200" value="" placeholder="<?php echo $Lang['Password']; ?>" /></label></p>
				<p><label><input type="text" name="VerifyCode" class="w100" onfocus="document.getElementById('Verification_Code_Img').src='<?php echo $Config['WebsitePath']; ?>/seccode.php';document.getElementById('Verification_Code_Img').style.display='inline';" value="" placeholder="<?php echo $Lang['Verification_Code']; ?>" /></label> 
				<img src="" id="Verification_Code_Img" style="cursor: pointer;display:none;" onclick="this.src+=''" alt="<?php echo $Lang['Verification_Code']; ?>" align="middle" /></p>
				<p><input type="submit" value="<?php echo $Lang['Log_In']; ?>" name="submit" class="textbtn" style="margin:0 78px;" /></p>
				<p class="fs14 text-center">
					<a href="<?php echo $Config['WebsitePath']; ?>/register"><?php echo $Lang['Sign_Up']; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="<?php echo $Config['WebsitePath']; ?>/forgot"><?php echo $Lang['Forgot_Password']; ?></a>
				</p>
			</form>
		</div>
				<p>
<?php
$OauthData = json_decode($Config['CacheOauth'], true);
$OauthData = $OauthData?$OauthData:array();
$OauthDataOffset = 0;
foreach ($OauthData as $Value) {
	if ($Value['AppKey']) {
?>
<a href="<?php echo $Config['WebsitePath']; ?>/oauth-<?php echo $Value['ID']; ?>">
	<img src="<?php echo $Config['WebsitePath'] . $Value[$OauthDataOffset===0?'ButtonImageUrl':'LogoUrl']; ?>" alt="<?php echo $Value['Alias'] . ' ' . $Lang['Log_In']; ?>" />
</a>&nbsp;
<?php
		$OauthDataOffset++;
	}
}
?>
				</p>
	</div>
</div>
<?php }else if($CurUserID && $UrlPath != 'register'){ ?>
<div class="sider-box">
	<div class="sider-box-title">
		<?php echo $Lang['User_Panel']; ?>
		<span class="float-right"><a href="<?php echo $Config['WebsitePath']; ?>/login?logout=<?php echo $CurUserCode; ?>"><?php echo $Lang['Log_Out']; ?></a></span>
	</div>
	<div class="sider-box-content">
		<div class="user-pannel-avatar">
			<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($CurUserName); ?>">
				<?php echo GetAvatar($CurUserID, $CurUserName, 'large'); ?>
			</a>
		</div>
		<div class="user-pannel">
			<div class="user-pannel-name">
				<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo urlencode($CurUserName); ?>"><?php echo $CurUserName; ?></a>
			</div>
			<ul>
				<li>
					<a href="<?php echo $Config['WebsitePath']; ?>/favorites">
						<strong><?php echo $CurUserInfo['NumFavTopics']; ?></strong>
						<span><?php echo $Lang['Favorite_Topics']; ?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo $Config['WebsitePath']; ?>/tags/following">
						<strong><?php echo $CurUserInfo['NumFavTags']; ?></strong>
						<span><?php echo $Lang['Tags_Followed']; ?></span>
					</a>
				</li>
				<li>
					<a href="<?php echo $Config['WebsitePath']; ?>/users/following">
						<strong><?php echo $CurUserInfo['NumFavUsers']; ?></strong>
						<span><?php echo $Lang['Users_Followed']; ?></span>
					</a>
				</li>
			</ul>
		</div>
		<div class="c"></div>
	</div>
</div>
<?php
}
if($HotTagsArray) {
?>
<div class="sider-box">
	<div class="sider-box-title">
		<?php echo $Lang['Hot_Tags']; ?>
		<span class="float-right"><a href="<?php echo $Config['WebsitePath']; ?>/tags"><?php echo $Lang['Show_More']; ?></a></span>
	</div>
	<div class="sider-box-content btn">
		<?php foreach ($HotTagsArray as $Tag) {?>
		<a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag['Name']); ?>"><?php echo $Tag['Name']; ?></a>
		<?php } ?>
	</div>
</div>
<?php
}
if($Config['PageSiderContent']) {
?>
<div class="sider-box">
	<div class="sider-box-title"><?php echo $Lang['Information_Bar']; ?></div>
	<div class="sider-box-content">
		<?php echo $Config['PageSiderContent']; ?>
		<div class="c"></div>
	</div>
</div>
<?php
}
?>