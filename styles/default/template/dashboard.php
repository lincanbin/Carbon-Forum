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
			<li><?php echo $Lang['Basic_Settings']; ?></li>
			<li><?php echo $Lang['Page_Settings']; ?></li>
			<li><?php echo $Lang['Advanced_Settings']; ?></li>
			<li><?php echo $Lang['Refresh_Cache']; ?></li>
		</ul>
		<div class="resp-tabs-container main-box">

			<div>
				<p class="red text-center"><?php echo $BasicMessage; ?></p>
				<form method="post" action="<?php echo $Config['WebsitePath']; ?>/dashboard#dashboard1">
				<input type="hidden" name="Action" value="Basic" />
				<table cellpadding="5" cellspacing="8" border="0" width="100%" class="fs14">
				<tbody>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Forum_Name']; ?></td>
						<td width="auto" align="left"><input type="text" class="w300" name="SiteName" value="<?php echo $Config['SiteName']; ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Forum_Descriptions']; ?></td>
						<td width="auto" align="left"><textarea class="w300 h160" name="SiteDesc"><?php echo $Config['SiteDesc']; ?></textarea></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Page_Show']; ?></td>
						<td width="auto" align="left">
							<select name="TopicsPerPage">
								<option value="<?php echo $Config['TopicsPerPage']; ?>"><?php echo $Config['TopicsPerPage']; ?></option>
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="50">50</option>
								<option value="100">100</option>
							</select><?php echo $Lang['Page_Topics']; ?>
						</td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Topic_Show']; ?></td>
						<td width="auto" align="left">
							<select name="PostsPerPage">
								<option value="<?php echo $Config['PostsPerPage']; ?>"><?php echo $Config['PostsPerPage']; ?></option>
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="50">50</option>
								<option value="100">100</option>
							</select><?php echo $Lang['Topic_Posts']; ?>
						</td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Topic_Max']; ?></td>
						<td width="auto" align="left">
							<select name="MaxTagsNum">
								<option value="<?php echo $Config['MaxTagsNum']; ?>"><?php echo $Config['MaxTagsNum']; ?></option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
							</select><?php echo $Lang['Topic_Max_Tags']; ?>
						</td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Tag_Max']; ?></td>
						<td width="auto" align="left">
							<select name="MaxTagChars">
								<option value="<?php echo $Config['MaxTagChars']; ?>"><?php echo $Config['MaxTagChars']; ?></option>
								<option value="32">32</option>
								<option value="64">64</option>
								<option value="128">128</option>
								<option value="256">256</option>
							</select><?php echo $Lang['Tag_Max_Chars']; ?>
						</td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Post_Max']; ?></td>
						<td width="auto" align="left">
							<select name="MaxPostChars">
								<option value="<?php echo $Config['MaxPostChars']; ?>"><?php echo $Config['MaxPostChars']; ?></option>
								<option value="1024">1024</option>
								<option value="4096">4096</option>
								<option value="16384">16384</option>
								<option value="65536">65536</option>
								<option value="262144">262144</option>
							</select><?php echo $Lang['Post_Max_Chars']; ?>
						</td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="<?php echo $Lang['Save']; ?>" name="submit" class="textbtn" /></td>
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
						<td width="180" align="right"><?php echo $Lang['Html_Between_Head']; ?> </td>
						<td width="auto" align="left"><textarea class="w300 h160" name="PageHeadContent"><?php echo $Config['PageHeadContent']; ?></textarea></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Html_Before_Body']; ?></td>
						<td width="auto" align="left"><textarea class="w300 h160" name="PageBottomContent"><?php echo $Config['PageBottomContent']; ?></textarea></td>
					</tr>
					<tr>
						<td width="180" align="right"> <?php echo $Lang['Html_SiderBar']; ?><br /></td>
						<td width="auto" align="left"><textarea class="w300 h160" name="PageSiderContent"><?php echo $Config['PageSiderContent']; ?></textarea></td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="<?php echo $Lang['Save']; ?>" name="submit" class="textbtn" /></td>
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
						<td width="180" align="right"><?php echo $Lang['jQuery_CDN']; ?></td>
						<td width="auto" align="left">
							<select name="LoadJqueryUrl">
								<option value="<?php echo $Config['LoadJqueryUrl']; ?>"><?php echo $Config['LoadJqueryUrl']; ?></option>
								<option value="<?php echo $Config['WebsitePath']; ?>/static/js/jquery.js"><?php echo $Config['WebsitePath']; ?>/static/js/jquery.js(Local)</option>
								<option value="//lib.sinaapp.com/js/jquery/1.10.2/jquery-1.10.2.min.js">lib.sinaapp.com/js/jquery/1.10.2/jquery-1.10.2.min.js(Sina CDN)</option>
								<option value="//libs.baidu.com/jquery/1.10.2/jquery.min.js">libs.baidu.com/jquery/1.10.2/jquery.min.js(Baidu CDN)</option>
								<option value="//libs.useso.com/js/jquery/1.10.2/jquery.min.js">libs.useso.com/js/jquery/1.10.2/jquery.min.js(Qihoo360 CDN)</option>
								<option value="//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js">ajax.aspnetcdn.com/ajax/jQuery/jquery-1.10.2.min.js(Microsoft CDN)</option>
								<option value="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js(Google CDN)</option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Main_Domainname']; ?><br >(Example:&nbsp;&nbsp;www.94cb.com)</td>
						<td width="auto" align="left"><input type="text" class="w300" name="MainDomainName" value="<?php echo $Config['MainDomainName']; ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['Mobile_Domainname']; ?><br >(Example:&nbsp;&nbsp;m.94cb.com)</td>
						<td width="auto" align="left"><input type="text" class="w300" name="MobileDomainName" value="<?php echo $Config['MobileDomainName']; ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right"><?php echo $Lang['API_Domainname']; ?><br >(Example:&nbsp;&nbsp;app.94cb.com)</td>
						<td width="auto" align="left"><input type="text" class="w300" name="AppDomainName" value="<?php echo $Config['AppDomainName']; ?>" /></td>
					</tr>
					<tr>
						<td width="180" align="right"></td>
						<td width="auto" align="left"><input type="submit" value="<?php echo $Lang['Save']; ?>" name="submit" class="textbtn" /></td>
					</tr>
				</tbody></table>
				</form>
			</div>
			<div>
				<p class="red text-center"><?php echo $CacheMessage; ?></p>
				<p class="grey text-center"><?php echo $Lang['Refresh_Cache_Notice']; ?></p>
				<form method="post" action="<?php echo $Config['WebsitePath']; ?>/dashboard#dashboard4">
					<input type="hidden" name="Action" value="Cache" />
					<div class="div-align"><input type="submit" value="<?php echo $Lang['Refresh_All_Cache']; ?>" name="submit" class="textbtn" /></div>
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