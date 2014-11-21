<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<div class="main-content">
	<div class="title">
		<a href="<?php echo $Config['WebsitePath']; ?>/"><?php echo $Config['SiteName']; ?></a> &raquo; 登陆
	</div>
	<div class="main-box">
		<?php if($error){ ?>
		<p class="red fs12" style="margin-left:60px;">
		› <?php echo $error; ?> <br/></p>
		<?php } ?>
		<form action="?" method="post">
			<input type="hidden" value="<?php echo $ReturnUrl; ?>" name="ReturnUrl" />
			<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>" />
			<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
				<tbody>
					<tr>
						<td width="180" align="right">用户名</td>
						<td width="auto" align="left"><input type="text" name="UserName" class="sl w200" value="<?php echo htmlspecialchars($UserName); ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right">密码</td>
						<td width="auto" align="left"><input type="password" name="Password" class="sl w200" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right">登陆有效期</td>
						<td width="auto" align="left">
							<select name="Expires">
								<option value="365">保存一年</option>
								<option value="30">保存一月</option>
								<option value="7">保存一周</option>
								<option value="1">保存一天</option>
								<option value="0">不保存</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="180" align="right">验证码</td>
						<td width="auto" align="left">
							<input type="text" name="VerifyCode" class="sl w100" value="" /> <img src="<?php echo $Config['WebsitePath']; ?>/seccode.php" align="absmiddle" />
						</td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="登 陆" name="submit" class="textbtn" />&nbsp;&nbsp;&nbsp;&nbsp;还没来过？<a href="<?php echo $Config['WebsitePath']; ?>/register">现在注册</a></td>
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