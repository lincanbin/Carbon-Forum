<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<script>
$(document).ready(function(){
	$("#settings").easyResponsiveTabs({
		type: 'default', //Types: default, vertical, accordion           
		width: 'auto', //auto or any custom width
		fit: true,   // 100% fits in a container
		closed: false, // Close the panels on start, the options 'accordion' and 'tabs' keep them closed in there respective view types
		activate: function() {}  // Callback function, gets called if tab is switched
	});
});
</script>
<div class="main-content">
	<div id="settings" class="tab-container">
		<ul class='resp-tabs-list'>
			<li>头像设置</li>
			<li>资料设置</li>
			<li>密码更改</li>
		</ul>
		<div class="resp-tabs-container main-box">
			<div>
				<div>
					<div class="float-left w300 img-center"><img id="CurAvatar" src="<?php echo $Config['WebsitePath']; ?>/upload/avatar/large/<?php echo $CurUserID; ?>.png?cache=<?php echo $TimeStamp; ?>" alt="<?php echo $CurUserName; ?>" /></div>
					
					<div class="float-right w300">
						<p class="grey">
							
							你可以在这里修改你的头像
							<br /><br />
							头像格式支持jpg/jpeg/png/gif
						</p>
						<hr /><br />
						<form method="post" enctype="multipart/form-data" action="<?php echo $Config['WebsitePath']; ?>/settings#settings1">
							<input type="hidden" name="Action" value="UploadAvatar">
								<span class="red"><?php echo $UploadAvatarMessage; ?></span>
								<br />
								<input type="file" id="Avatar" name="Avatar">
								<br />
								
							<hr /><br />
							<div class="grey">头像最大不得超过1MiB</div>
							<br />
							<input type="submit" value="上传头像" name="submit" class="textbtn" />
						</form>
					</div>
					<div class="c"></div>
				</div>
			</div>
			<div>
				<p class="red text-center"><?php echo $UpdateUserInfoMessage; ?></p>
				<form method="post" action="<?php echo $Config['WebsitePath']; ?>/settings#settings2">
				<input type="hidden" name="Action" value="UpdateUserInfo" />
				<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
					<tbody>
					<tr>
						<td width="180" align="right">用户名称</td>
						<td width="auto" align="left"><?php echo $CurUserName; ?></td>
					</tr>
					<tr>
						<td width="180" align="right">用户性别</td>
						<td width="auto" align="left">
							<select name="UserSex">
								<option value="<?php echo $CurUserInfo['UserSex']; ?>">不修改</option>
								<option value="0">不明确</option>
								<option value="1">男</option>
								<option value="2">女</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="180" align="right">电子邮件</td>
						<td width="auto" align="left"><input type="text" class="w300" name="UserMail" value="<?php echo $CurUserInfo['UserMail']; ?>" /> <br />不公开，仅供取回密码，务必正确填写且记住。</td>
					</tr>
					<tr>
						<td width="180" align="right">个人网站</td>
						<td width="auto" align="left"><input type="text" class="w300" name="UserHomepage" value="<?php echo $CurUserInfo['UserHomepage']; ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right">个人简介</td>
						<td width="auto" align="left"><textarea class="w300 h160" name="UserIntro"><?php echo $CurUserInfo['UserIntro']; ?></textarea></td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="保存设置" name="submit" class="textbtn" /></td>
					</tr>
					
				</tbody></table>
				</form>
			</div>
			<div>
				<p class="red text-center"><?php echo $ChangePasswordMessage; ?></p>
				<form method="post" action="<?php echo $Config['WebsitePath']; ?>/settings#settings3">
				<input type="hidden" name="Action" value="ChangePassword" />
				<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
					<tbody>
					<tr>
						<td width="180" align="right">当前密码</td>
						<td width="auto" align="left"><input type="password" class="w300" name="OriginalPassword" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right">新密码</td>
						<td width="auto" align="left"><input type="password" class="w300" name="NewPassword" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right">再次输入新密码</td>
						<td width="auto" align="left"><input type="password" class="w300" name="NewPassword2" value="" /></td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="更改密码" name="submit" class="textbtn" /></td>
					</tr>
					
				</tbody></table>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
	<?php include($TemplatePath.'sider.php'); ?>
</div>
<!-- main-sider end -->