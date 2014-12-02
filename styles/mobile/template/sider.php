<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<?php if(!$CurUserID && $UrlPath!='login'){ ?>
<form action="<?php echo $Config['WebsitePath']; ?>/login" method="post">
	<input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="ReturnUrl" />
	<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
	<input type="hidden" name="Expires" value="30" />
	<li class="divider"><?php echo $Lang['Log_In']; ?></li>
	<li>
		<input type="text" name="UserName" value="" placeholder="<?php echo $Lang['UserName']; ?>" />
		<br />
		<input type="password" name="Password" value="" placeholder="<?php echo $Lang['Password']; ?>" /><br />
		<input type="text" style="width:60%;" name="VerifyCode" onclick="document.getElementById('Verification_Code_Img').src='<?php echo $Config['WebsitePath']; ?>/seccode.php';" value="" placeholder="<?php echo $Lang['Verification_Code']; ?>" /> <img src="" id="Verification_Code_Img" style="cursor: pointer;" onclick="this.src+=''" align="absmiddle" />
		<br />
		<input type="submit" value="<?php echo $Lang['Log_In']; ?>" name="submit" />
	</li>
	<li><a href="<?php echo $Config['WebsitePath']; ?>/register" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Sign_Up']; ?></a></li>
</form>
<?php }else if($CurUserID){ ?>
<li class="divider"><?php echo $Lang['User_Panel']; ?></li>
<li>
	<a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $CurUserName; ?>">
		<?php echo GetAvatar($CurUserID, $CurUserName, 'small'); ?>&nbsp;&nbsp;<span style="line-height:24px;vertical-align: top;font-size:20px;font-weight:bold;"><?php echo $CurUserName; ?></span>
	</a>
</li>
<li>
	<a href="<?php echo $Config['WebsitePath']; ?>/notifications" data-transition="slide" data-persist-ajax="true">
		<?php echo $Lang['Notifications']; ?>(<?php echo $CurUserInfo['NewMessage']; ?>)
	</a>
</li>
<li>
	<a href="<?php echo $Config['WebsitePath']; ?>/favorites" data-transition="slide" data-persist-ajax="true">
		<?php echo $Lang['Favorite_Topics']; ?>(<?php echo $CurUserInfo['NumFavTopics']; ?>)
	</a>
</li>
<li>
	<a href="<?php echo $Config['WebsitePath']; ?>/tags/following" data-transition="slide" data-persist-ajax="true">
		<?php echo $Lang['Tags_Followed']; ?>(<?php echo $CurUserInfo['NumFavTags']; ?>)
	</a>
</li>
<li>
	<a href="<?php echo $Config['WebsitePath']; ?>/users/following" data-transition="slide" data-persist-ajax="true">
		<?php echo $Lang['Users_Followed']; ?>(<?php echo $CurUserInfo['NumFavUsers']; ?>)
	</a>
</li>

<li>
	<a href="<?php echo $Config['WebsitePath']; ?>/login?logout=1" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Log_Out']; ?></a>
</li>
<?php }
?>
<li class="divider">Navi</li>
<li>
	<a class="icon home mini" href="<?php echo $Config['WebsitePath']; ?>/" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Home']; ?></a>
</li>