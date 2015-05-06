<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/reply.function.js?version=<?php echo $Config['Version']; ?>"></script>
<script type="text/javascript">
	var TopicID = <?php echo $ID; ?>;
</script>
<!-- main-content start -->
<div class="main-content">
<?php
if($Page==1)
{
?>
<!-- post main content start -->
<div class="main-box without-title">
<div class="topic-title">
	<div class="topic-title-main float-left">
		<h1><?php  echo $Topic['Topic']; ?></h1>
		<div class="topic-title-date">
		By <a href="<?php echo $Config['WebsitePath'].'/u/'.$Topic['UserName']; ?>"><?php echo $Topic['UserName']; ?></a>
 at <?php echo FormatTime($Topic['PostTime']); ?> • <?php echo $Topic['Favorites']; ?><?php echo $Lang['People_Collection']; ?> • <?php echo ($Topic['Views']+1); ?><?php echo $Lang['People_Have_Seen']; ?>
		</div>
	</div>
	<div class="detail-avatar"><a href="<?php echo $Config['WebsitePath'].'/u/'.$Topic['UserName']; ?>">
<?php echo GetAvatar($Topic['UserID'], $Topic['UserName'], 'large'); ?>

	</a></div>
	<div class="c"></div>
</div>
<div class="topic-content">
	<div id="p<?php echo $PostsArray[0]['ID']; ?>" style="visibility:visible;">
		<?php echo $PostsArray[0]['Content']; ?>
	</div>
	<div id="edit<?php echo $PostsArray[0]['ID']; ?>" style="width:648px;height:auto;visibility:hidden;"></div>
</div>
<div class="topic-tags btn">
<div class="w400">
<?php
if($Topic['Tags']){
	foreach (explode("|", $Topic['Tags']) as $Tag) {
?><a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag); ?>" target="_blank"><?php echo $Tag; ?></a>
<?php
	}
}?></div>
<?php
if($CurUserRole>=4){

	if($Topic['IsDel']==0){
	?>
<a href="###" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'Delete', true, this);" style="float:right;"><?php echo $Lang['Delete']; ?></a>
<?php
	}else{
?>
<a href="###" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'Recover', false, this);" style="float:right;"><?php echo $Lang['Recover']; ?></a>
<a href="###" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'PermanentlyDelete', true, this);" style="float:right;"><?php echo $Lang['Permanently_Delete']; ?></a>
<?php
	}
?>
<a href="###" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'Lock', true, this);" style="float:right;"><?php echo $Topic['IsLocked']?$Lang['Unlock']:$Lang['Lock']; ?></a>
<a href="###" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'Sink', true, this);" style="float:right;"><?php echo $Lang['Sink']; ?></a>
<a href="###" onclick="javascript:Manage(<?php echo $ID; ?>, 1, 'Rise', true, this);" style="float:right;"><?php echo $Lang['Rise']; ?></a>
<?php
}
?>
<?php if($CurUserRole>=4 || $Topic['UserID']==$CurUserID){ ?>
<a href="###" onclick="javascript:EditPost(<?php echo $PostsArray[0]['ID']; ?>);" style="float:right;"><?php echo $Lang['Edit']; ?></a>
<?php } ?>
<?php if($CurUserID){ ?>
<a href="###" onclick="javascript:Manage(<?php echo $ID; ?>, 4, 1, false, this);" style="float:right;"><?php echo $IsFavorite?$Lang['Unsubscribe']:$Lang['Collect']; ?></a>
<?php } ?>
<div class="c"></div>
</div>
</div>
<!-- post main content end -->
<?php
	unset($PostsArray[0]);
}
if($Topic['Replies']!=0)
{
?>
<!-- comment list start -->
<div class="title">
	<?php echo $Topic['Replies']; ?> <?php echo $Lang['Replies']; ?>  |  <?php echo $Lang['Last_Updated_In']; ?> <?php echo FormatTime($Topic['LastTime']); ?>
</div>
<div class="main-box home-box-list">
<?php
foreach($PostsArray as $key => $Post)
{
	$PostFloor = ($Page-1)*$Config['PostsPerPage']+$key;
?>
	<div class="comment-item">
		<a name="Post<?php echo $Post['ID'];?>"></a>
		<div class="comment-avatar">
			<a href="<?php echo $Config['WebsitePath'].'/u/'.$Post['UserName']; ?>" target="_blank">
			<?php echo GetAvatar($Post['UserID'], $Post['UserName'], 'middle'); ?>
			</a>
		</div>
		<div class="comment-data">
			<div class="comment-content">
				<div>
					<div class="float-left text-bold fs14"><a href="<?php echo $Config['WebsitePath'].'/u/'.$Post['UserName']; ?>"><?php echo $Post['UserName'];?></a></div>
					<span class="float-right grey fs12">
						<?php echo FormatTime($Post['PostTime']); ?>&nbsp;&nbsp;
						<span class="commonet-count">#<?php echo $PostFloor; ?></span>
						
					</span>
				</div>
				<div class="c"></div>
				<div id="p<?php echo $Post['ID']; ?>" style="visibility:visible;">
					<?php echo $Post['Content']; ?>
				</div>
				<div id="edit<?php echo $Post['ID']; ?>" style="width:588px;height:auto;visibility:hidden;"></div>
			</div>
			<?php if($CurUserID){ ?>
			<div class="comment-button">
				<div class="float-left">
				<?php if($CurUserRole>=4 || $Post['UserID']==$CurUserID){ ?><a href="###" onclick="javascript:EditPost(<?php echo $Post['ID']; ?>);" title="<?php echo $Lang['Edit']; ?>"><div class="icon icon-edit"></div></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
				<?php if($CurUserRole>=4){ ?><a href="###" onclick="javascript:Manage(<?php echo $Post['ID']; ?>, 2, 'Delete', true, this);" title="<?php echo $Lang['Delete']; ?>"><div class="icon icon-delete"></div></a><?php } ?>
				</div>
				<div class="float-right">
					<a href="#reply" title="<?php echo $Lang['Reply']; ?>" onclick="JavaScript:Reply('<?php echo $Post['UserName'];?>', <?php echo $PostFloor; ?>, <?php echo $Post['ID'];?>);"><div class="icon icon-reply"></div></a>
<?php
if($EnableQuote){
?>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="#reply" title="<?php echo $Lang['Quote']; ?>" onclick="JavaScript:Quote('<?php echo $Post['UserName'];?>', <?php echo $PostFloor; ?>, <?php echo $Post['ID'];?>);"><div class="icon icon-quote"></div></a>
<?php
}
?>	
				</div>
				<div class="c"></div>
			</div>
			<?php } ?>
			<div class="c"></div>
		</div>
		<div class="c"></div>
	</div>
<?php
}
if($TotalPage>1){
?>
<div class="pagination">
	<?php Pagination("/t/".$ID."-",$Page,$TotalPage); ?>
<div class="c"></div>
</div>
<?php
}
?>
</div>
<!-- comment list end -->
<?php
}
?>
<!-- editor start -->
<?php
if(!$Topic['IsLocked'] && !$CurUserInfo){
?>
<div class="ad"><p><?php echo $Lang['Requirements_For_Login']; ?></p></div>
<?php
}else if($Topic['IsLocked']){
?>
<div class="ad"><p><?php echo $Lang['Topic_Has_Been_Locked']; ?></p></div>
<?php
}else{
?>

<div class="title">
	<div class="float-left"><?php echo $Lang['Reply']; ?><a name="reply"></a></div>
	<div class="float-right"><a href="#">↑ Top</a></div>
	<div class="c"></div>    
</div>
<div class="main-box">
	<script>
	var MaxPostChars = <?php echo $Config['MaxPostChars']; ?>;//主题内容最多字节数
	</script>
	<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.config.js?version=<?php echo $Config['Version']; ?>"></script>
	<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.all.min.js?version=<?php echo $Config['Version']; ?>"> </script>
	<!--建议手动加载语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
	<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
	<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/language/<?php echo ForumLanguage; ?>/<?php echo ForumLanguage; ?>.js?version=<?php echo $Config['Version']; ?>"></script>
	<form name="reply">
		<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>">
		<input type="hidden" name="TopicID" value="<?php echo $ID; ?>">
		<p>
			<div id="editor" style="width:648px;height:160px;"></div>
			<script type="text/javascript">
			InitEditor();
			</script>
		</p>
		<div class="float-left"><input type="button" value="<?php echo $Lang['Reply']; ?>" class="textbtn" id="ReplyButton" onclick="JavaScript:ReplyToTopic();"/></div>
		<div class="c"></div> 
		<p></p>
	</form>
</div>
<?php
}
?>
<!-- editor end -->
</div>
<!-- main-content end -->
<!-- main-sider start -->
<div class="main-sider">
	<?php include($TemplatePath.'sider.php'); ?>
</div>
<!-- main-sider end -->