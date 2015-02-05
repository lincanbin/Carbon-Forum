<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
if(!$IsAjax){
?>
<!-- this is the header div at the top -->
<div id="header">
	<!--a href="javascript:$.ui.toggleLeftSideMenu()" class="button" style="float:left">Toggle Nav</a-->
	<a href="###" class="button" style="float:right;" class="icon home" onclick="JavaScript:Reply('<?php echo $topic['UserName'];?>', 1, 0, '<?php echo $FormHash;?>', <?php echo $id; ?>);"><?php echo $Lang['Reply']; ?></a>
</div>
<div id="content">
	<!-- here is where you can add your panels -->
<?php } ?>
	<div data-title="<?php echo $PageTitle; ?>" id="Topic<?php echo $id.'-'.$Page; ?>" class="panel" selected="true">
<?php
if($Page>1){
?>
	<ul class="list topic-list">
		<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $id.'-'.($Page-1); ?>" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Page_Previous']; ?></a></li>
	</ul>
<?php
}
if($Page==1){
?>
<div class="topic-title">
	<div class="topic-title-main">
		<h1><?php  echo $topic['Topic']; ?></h1>
		<div class="topic-title-date">
		By <a href="<?php echo $Config['WebsitePath'].'/u/'.$topic['UserName']; ?>"><?php echo $topic['UserName']; ?></a>
 at <?php echo FormatTime($topic['PostTime']); ?><?php echo $topic['Favorites']; ?><?php echo $Lang['People_Collection']; ?> • <?php echo ($topic['Views']+1); ?><?php echo $Lang['People_Have_Seen']; ?>
		</div>
	</div>
	<div class="c"></div>
</div>
<div class="topic-content">
<p><?php echo $PostsArray[0]['Content']; ?></p>
</div>
<div class="TagLists">
<?php
if($topic['Tags']){
	foreach (explode("|", $topic['Tags']) as $Tag) {
?><a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag); ?>" target="_blank" class="button"><?php echo $Tag; ?></a>
<?php
	}
}?></div>
<div class="Manage">
<?php
if($CurUserRole>=4){
	if($topic['IsDel']==0){
	?>
<a href="###" onclick="javascript:Manage(<?php echo $id; ?>, 1, 'Delete', true, this);" class="button red"><?php echo $Lang['Delete']; ?></a>
<?php
	}else{
?>
<a href="###" onclick="javascript:Manage(<?php echo $id; ?>, 1, 'Recover', false, this);" class="button green"><?php echo $Lang['Recover']; ?></a>
<a href="###" onclick="javascript:Manage(<?php echo $id; ?>, 1, 'PermanentlyDelete', true, this);" class="button red"><?php echo $Lang['Permanently_Delete']; ?></a>
<?php
	}
?>
<a href="###" onclick="javascript:Manage(<?php echo $id; ?>, 1, 'Lock', true, this);" class="button"><?php echo $topic['IsLocked']?$Lang['Unlock']:$Lang['Lock']; ?></a>
<a href="###" onclick="javascript:Manage(<?php echo $id; ?>, 1, 'Sink', true, this);" class="button"><?php echo $Lang['Sink']; ?></a>
<a href="###" onclick="javascript:Manage(<?php echo $id; ?>, 1, 'Rise', true, this);" class="button"><?php echo $Lang['Rise']; ?></a>
<?php
}
?>
<a href="###" onclick="javascript:Manage(<?php echo $id; ?>, 4, 1, false, this);" class="button"><?php echo $IsFavorite?$Lang['Unsubscribe']:$Lang['Collect']; ?></a>
<div class="c"></div>
</div>
<!-- post main content end -->
<?php
	unset($PostsArray[0]);
}
if($topic['Replies']!=0)
{
?>
<!-- comment list start -->
<div class="title">
	<?php echo $topic['Replies']; ?> <?php echo $Lang['Replies']; ?>  |  <?php echo $Lang['Last_Updated_In']; ?> <?php echo FormatTime($topic['LastTime']); ?>
</div>
<div class="commentList">
<?php
foreach($PostsArray as $key => $post)
{
	$PostFloor = ($Page-1)*$Config['PostsPerPage']+$key;
?>
	<table width="100%" border="0">
		<tr>
			<td class="portrait">
				<a href="<?php echo $Config['WebsitePath'].'/u/'.$post['UserName']; ?>">
					<?php echo GetAvatar($post['UserID'], $post['UserName'], 'middle'); ?>
				</a>
			</td>
			<td class="body">
				<div class="r_title"><?php echo $post['UserName'];?>&nbsp;&nbsp; <?php echo FormatTime($post['PostTime']); ?> <span class="commonet-count">#<?php echo $PostFloor; ?></span></div>
				<div class="TextContent">
					<?php echo $post['Content']; ?>
				</div>
				<div class="opts">
					<?php if($CurUserID){ ?>
						<div class="manage">
						<?php if($CurUserRole>=4){ ?><a href="###" onclick="javascript:Manage(<?php echo $post['ID']; ?>, 2, 'Delete', true, this);"><?php echo $Lang['Delete']; ?></a><?php } ?>
						</div>
						<div class="reply">
							<a href="#reply" title="<?php echo $Lang['Reply']; ?>" onclick="JavaScript:Reply('<?php echo $post['UserName'];?>', <?php echo $PostFloor; ?>, <?php echo $post['ID'];?>, '<?php echo $FormHash;?>', <?php echo $id;?>);"><?php echo $Lang['Reply']; ?></a>
						</div>
						<div class="c"></div>
					<?php } ?>
				</div>
			</td>
		</tr>
	</table>
<?php
}
?>
</div>
<!-- comment list end -->
<?php
}
?>
<!-- editor start -->
<ul class="list topic-list">
<?php
if(!$topic['IsLocked'] && !$CurUserInfo){
?>
<li class="pagination"><?php echo $Lang['Requirements_For_Login']; ?></li>
<?php
}else if($topic['IsLocked']){
?>
<li class="pagination"><?php echo $Lang['Topic_Has_Been_Locked']; ?></li>
<?php
}else{
?>
<li class="pagination"><a href="###" onclick="JavaScript:Reply('<?php echo $topic['UserName'];?>', 1, 0, '<?php echo $FormHash;?>', <?php echo $id; ?>);"><?php echo $Lang['Reply']; ?></a></li>
<?php
}
if($Page<$TotalPage){
?>
	
	<li class="pagination"><a href="<?php echo $Config['WebsitePath']; ?>/t/<?php echo $id.'-'.($Page+1); ?>" data-transition="slide" data-persist-ajax="true"><?php echo $Lang['Page_Next']; ?></a></li>
<?php } ?>
</ul>
<!-- editor end -->
<?php
if(!$IsAjax){
?>
</div>
<!-- this is the default left side nav menu.  If you do not want any, do not include these -->
<nav>
	<!--header class="header"><h1>Left Menu</h1></header-->
	<ul class="list">
		<?php include($TemplatePath.'sider.php'); ?>
	</ul>
</nav>
<?php } ?>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.parse.min.js?version=<?php echo $Config['Version']; ?>"> </script>
<script type="text/javascript">
uParse('.main-content',{
	'rootPath': '<?php echo $Config['WebsitePath']; ?>/static/editor/',
	'liiconpath':'<?php echo $Config['WebsitePath']; ?>/static/editor/themes/ueditor-list/'//使用 '/' 开头的绝对路径
});
//强制所有链接在新窗口中打开
var AllPosts = document.getElementsByClassName("comment-content");
AllPosts[AllPosts.length]=document.getElementsByClassName("topic-content")[0];
for (var j=0; j<=AllPosts.length; j++) {
	var AllLinks = AllPosts[j].getElementsByTagName("a");
	for(var i=0; i<AllLinks.length; i++)
	{
		var a = AllLinks[i];
		console.log(a);
		a.target="_blank";
	};
};

</script>