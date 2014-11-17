<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<?php if(!$CurUserID && $UrlPath!='login'){ ?>
<div class="sider-box">
	<div class="sider-box-title">登陆</div>
	<div class="sider-box-content">
		<div class="center-align" style="width:226px;">
			<form action="<?php echo $Config['WebsitePath']; ?>/login" method="post">
				<input type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="ReturnUrl" />
				<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
				<input type="hidden" name="Expires" value="30" />
				<p><label><input type="text" name="UserName" style="width:200px;" value="" placeholder="用户名" /></label></p>
				<p><label><input type="password" name="Password" style="width:200px;" value="" placeholder="密码" /></label></p>
				<p><label><input type="text" name="VerifyCode" style="width:100px;" value="" placeholder="验证码" /></label> <img src="<?php echo $Config['WebsitePath']; ?>/seccode.php" align="absmiddle" /></p>
				<p><input type="submit" value=" 登  陆 " name="submit" class="textbtn" style="margin:0 78px;" /></p>
				<p class="fs14 text-center">还没来过？<a href="<?php echo $Config['WebsitePath']; ?>/register">现在注册</a></p>
			</form>
		</div>
	</div>
</div>
<?php }else if($CurUserID){ ?>
<div class="sider-box">
	<div class="sider-box-title">用户面板<span class="float-right"><a href="<?php echo $Config['WebsitePath']; ?>/login?logout=1">退出</a></span></div>
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
						<span>帖子收藏</span>
					</a>
				</li>
				<li>
					<a href="<?php echo $Config['WebsitePath']; ?>/tags/following">
						<strong><?php echo $CurUserInfo['NumFavTags']; ?></strong>
						<span>关注话题</span>
					</a>
				</li>
				<li>
					<a href="<?php echo $Config['WebsitePath']; ?>/users/following">
						<strong><?php echo $CurUserInfo['NumFavUsers']; ?></strong>
						<span>关注用户</span>
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
	<div class="sider-box-title">信息栏</div>
	<div class="sider-box-content">
		<?php echo $Config['PageSiderContent']; ?>
		<div class="c"></div>
	</div>
</div>
<?php } ?>