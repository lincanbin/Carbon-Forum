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
				<p><input type="submit" value=" 登  陆 " name="submit" class="textbtn" style="margin:0 85px;" /></p>
				<p class="fs14 text-center">还没来过？<a href="<?php echo $Config['WebsitePath']; ?>/register">现在注册</a></p>
			</form>
		</div>
	</div>
</div>
<?php }else if($CurUserID){ ?>
<div class="sider-box">
	<div class="sider-box-title"><?php echo $CurUserName; ?><span class="float-right"><a href="<?php echo $Config['WebsitePath']; ?>/login?logout=1">退出</a></span></div>
	<div class="sider-box-content">
		<div class="cell">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tbody>
					<tr>
					<td width="48" valign="top" class="img-center"><a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $CurUserName; ?>">
						<?php echo GetAvatar($CurUserID, $CurUserName, 'middle'); ?>
					</a></td>
					<td width="10" valign="top"></td>
					<td width="auto" align="left"><h2><a href="<?php echo $Config['WebsitePath']; ?>/u/<?php echo $CurUserID; ?>"><?php echo $CurUserName; ?></a></h2>
					</td>
					</tr>
				</tbody>
			</table>
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