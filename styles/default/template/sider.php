<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<?php if(!$CurUserID && $UrlPath!='login'){ ?>
<div class="sider-box">
	<div class="sider-box-title"><?php echo $Lang['Log_In']; ?></div>
	<div class="sider-box-content">
		<div class="center-align" style="width:226px;">
			<form action="<?php echo $Config['WebsitePath']; ?>/login" method="post">
				<input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="ReturnUrl" />
				<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
				<input type="hidden" name="Expires" value="30" />
				<p><label><input type="text" name="UserName" style="width:200px;" value="" placeholder="<?php echo $Lang['UserName']; ?>" /></label></p>
				<p><label><input type="password" name="Password" style="width:200px;" value="" placeholder="<?php echo $Lang['Password']; ?>" /></label></p>
				<p><label><input type="text" name="VerifyCode" class="w100" onclick="document.getElementById('Verification_Code_Img').src='<?php echo $Config['WebsitePath']; ?>/seccode.php';" value="" placeholder="<?php echo $Lang['Verification_Code']; ?>" /></label> <img src="" id="Verification_Code_Img" style="cursor: pointer;" onclick="this.src+=''" align="absmiddle" /></p>
				<p><input type="submit" value="<?php echo $Lang['Log_In']; ?>" name="submit" class="textbtn" style="margin:0 78px;" /></p>
				<p class="fs14 text-center"><a href="<?php echo $Config['WebsitePath']; ?>/register"><?php echo $Lang['Sign_Up']; ?></a></p>
			</form>
		</div>
	</div>
</div>
<?php }else if($CurUserID){ ?>
<div class="sider-box">
	<div class="sider-box-title"><?php echo $Lang['User_Panel']; ?><span class="float-right"><a href="<?php echo $Config['WebsitePath']; ?>/login?logout=1"><?php echo $Lang['Log_Out']; ?></a></span></div>
	<div class="sider-box-content">
		<div class="User-Pannel-Avatar">
			<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $CurUserName; ?>">
				<?php echo GetAvatar($CurUserID, $CurUserName, 'large'); ?>
			</a>
		</div>
		<div class="User-Pannel">
			<div class="User-Pannel-Name">
				<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $CurUserName; ?>"><?php echo $CurUserName; ?></a>
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
<?php }
if($Config['PageSiderContent'])
{
?>
<div class="sider-box">
	<div class="sider-box-title"><?php echo $Lang['Information_Bar']; ?></div>
	<div class="sider-box-content">
		<?php echo $Config['PageSiderContent']; ?>
		<div class="c"></div>
	</div>
</div>
<?php } ?>