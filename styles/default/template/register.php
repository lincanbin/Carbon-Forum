<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/"><?php echo $Config['SiteName']; ?></a> &raquo; 注册
	</div>
	<div class="main-box">
		<?php if($Message){ ?>
		<p class="red fs14" style="margin-left:60px;">
		› <?php echo $Message; ?> <br/></p>
		<?php } ?>
		<form action="?" method="post">
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
				<tbody>
					<tr>
						<td width="180" align="right">用户名</td>
						<td width="auto" align="left"><input type="text" name="UserName" class="sl w200" value="<?php echo htmlspecialchars($UserName); ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right">电子邮箱</td>
						<td width="auto" align="left"><input type="text" name="Email" class="sl w200" value="<?php echo htmlspecialchars($Email); ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right">密码</td>
						<td width="auto" align="left"><input type="password" name="Password" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right">密码</td>
						<td width="auto" align="left"><input type="password" name="Password2" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right">验证码</td>
						<td width="auto" align="left">
							<input type="text" name="VerifyCode" class="sl w100" value="" /> <img src="<?php echo $Config['WebsitePath']; ?>/seccode.php" align="absmiddle" />
						</td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="注 册" name="submit" class="textbtn" />&nbsp;&nbsp;&nbsp;&nbsp;已经注册？<a href="<?php echo $Config['WebsitePath']; ?>/login">马上登陆</a></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>
	<!-- main-content end -->
	<!-- main-sider start -->
	<div class="main-sider">
	<?php include($TemplatePath.'sider.php'); ?>
	</div>
	<!-- main-sider end -->