<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<!-- main-content start -->
<script>
$(document).ready(function(){
	$("#dashboard").easyResponsiveTabs({
		type: 'default', //Types: default, vertical, accordion           
		width: 'auto', //auto or any custom width
		fit: true,   // 100% fits in a container
		closed: false, // Close the panels on start, the options 'accordion' and 'tabs' keep them closed in there respective view types
		activate: function() {}  // Callback function, gets called if tab is switched
	});
});
</script>
<div class="main-content">
	<div id="dashboard" class="tab-container">
		<ul class='resp-tabs-list'>
			<li>基本设置</li>
			<li>页面设置</li>
			<li>高级设置</li>
			<li>更新缓存</li>
		</ul>
		<div class="resp-tabs-container main-box">

			<div>
				<p class="red text-center"><?php echo $BasicMessage; ?></p>
				<form method="post" action="<?php echo $Config['WebsitePath']; ?>/dashboard#dashboard1">
				<input type="hidden" name="Action" value="Basic" />
				<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
				<tbody>
					<tr>
						<td width="180" align="right">论坛名称</td>
						<td width="auto" align="left"><input type="text" class="w300" name="SiteName" value="<?php echo $Config['SiteName']; ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right">论坛描述<br /><br />给搜索引擎看的<br />150字符以内</td>
						<td width="auto" align="left"><textarea class="w300 h160" name="SiteDesc"><?php echo $Config['SiteDesc']; ?></textarea></td>
					</tr>
					<tr>
						<td width="180" align="right">每页显示</td>
						<td width="auto" align="left">
							<select name="TopicsPerPage">
								<option value="<?php echo $Config['TopicsPerPage']; ?>">不修改</option>
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="50">50</option>
								<option value="100">100</option>
							</select>个主题
						</td>
					</tr>
					<tr>
						<td width="180" align="right">每个主题显示</td>
						<td width="auto" align="left">
							<select name="PostsPerPage">
								<option value="<?php echo $Config['PostsPerPage']; ?>">不修改</option>
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="50">50</option>
								<option value="100">100</option>
							</select>个回帖
						</td>
					</tr>
					<tr>
						<td width="180" align="right">每个主题最多</td>
						<td width="auto" align="left">
							<select name="MaxTagsNum">
								<option value="<?php echo $Config['MaxTagsNum']; ?>">不修改</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
							</select>个标签
						</td>
					</tr>
					<tr>
						<td width="180" align="right">单个标签最大</td>
						<td width="auto" align="left">
							<select name="MaxTagChars">
								<option value="<?php echo $Config['MaxTagChars']; ?>">不修改</option>
								<option value="32">32</option>
								<option value="64">64</option>
								<option value="128">128</option>
								<option value="256">256</option>
							</select>个字符
						</td>
					</tr>
					<tr>
						<td width="180" align="right">每个帖子最多</td>
						<td width="auto" align="left">
							<select name="MaxPostChars">
								<option value="<?php echo $Config['MaxPostChars']; ?>">不修改</option>
								<option value="1024">1024</option>
								<option value="4096">4096</option>
								<option value="16384">16384</option>
								<option value="65536">65536</option>
								<option value="262144">262144</option>
							</select>个字符
						</td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="保存设置" name="submit" class="textbtn" /></td>
					</tr>
				</tbody></table>
				</form>
			</div>
			<div>
				<p class="red text-center"><?php echo $AdvancedMessage; ?></p>
				<form method="post" action="<?php echo $Config['WebsitePath']; ?>/dashboard#dashboard2">
				<input type="hidden" name="Action" value="Page" />
				<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
					<tbody>
					<tr>
						<td width="180" align="right"> &lt;Head&gt;标签之间的内容</td>
						<td width="auto" align="left"><textarea class="w300 h160" name="PageHeadContent"><?php echo $Config['PageHeadContent']; ?></textarea></td>
					</tr>
					<tr>
						<td width="180" align="right">  &lt;/Body&gt;标签之前的内容<br />一般放置统计代码</td>
						<td width="auto" align="left"><textarea class="w300 h160" name="PageBottomContent"><?php echo $Config['PageBottomContent']; ?></textarea></td>
					</tr>
					<tr>
						<td width="180" align="right"> 侧边栏的内容<br /></td>
						<td width="auto" align="left"><textarea class="w300 h160" name="PageSiderContent"><?php echo $Config['PageSiderContent']; ?></textarea></td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="保存设置" name="submit" class="textbtn" /></td>
					</tr>
					</tbody></table>
				</form>
			</div>
			<div>
				<p class="red text-center"><?php echo $AdvancedMessage; ?></p>
				<form method="post" action="<?php echo $Config['WebsitePath']; ?>/dashboard#dashboard3">
				<input type="hidden" name="Action" value="Advanced" />
				<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
				<tbody>
					<tr>
						<td width="180" align="right">jQuery库地址</td>
						<td width="auto" align="left">
							<select name="LoadJqueryUrl">
								<option value="<?php echo $Config['LoadJqueryUrl']; ?>">不修改</option>
								<option value="<?php echo $Config['WebsitePath']; ?>/static/js/jquery.js"><?php echo $Config['WebsitePath']; ?>/static/js/jquery.js(本地)</option>
								<option value="http://lib.sinaapp.com/js/jquery/1.10.2/jquery-1.10.2.min.js">http://lib.sinaapp.com/js/jquery/1.10.2/jquery-1.10.2.min.js(新浪CDN)</option>
								<option value="http://libs.baidu.com/jquery/1.10.2/jquery.min.js">http://libs.baidu.com/jquery/1.10.2/jquery.min.js(百度CDN)</option>
								<option value="http://libs.useso.com/js/jquery/1.10.2/jquery.min.js">http://libs.useso.com/js/jquery/1.10.2/jquery.min.js(360 CDN)</option>
								<option value="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js">http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js(微软 CDN)</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="180" align="right">PC端主站域名<br >(Example:&nbsp;&nbsp;www.94cb.com)</td>
						<td width="auto" align="left"><input type="text" class="w300" name="MainDomainName" value="<?php echo $Config['MainDomainName']; ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right">手机站域名<br >(Example:&nbsp;&nbsp;m.94cb.com)</td>
						<td width="auto" align="left"><input type="text" class="w300" name="MobileDomainName" value="<?php echo $Config['MobileDomainName']; ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right">客户端API域名<br >(Example:&nbsp;&nbsp;app.94cb.com)</td>
						<td width="auto" align="left"><input type="text" class="w300" name="AppDomainName" value="<?php echo $Config['AppDomainName']; ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="保存设置" name="submit" class="textbtn" /></td>
					</tr>
				</tbody></table>
				</form>
			</div>
			<div>
				<p class="red text-center"><?php echo $CacheMessage; ?></p>
				<p class="grey text-center">更新缓存的操作可能需要较长时间，请耐心等待<br />如果数据正确，则没有必要清理缓存</p>
				<form method="post" action="<?php echo $Config['WebsitePath']; ?>/dashboard#dashboard4">
					<input type="hidden" name="Action" value="Cache" />
					<div class="div-align"><input type="submit" value="更新全站缓存" name="submit" class="textbtn" /></div>
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