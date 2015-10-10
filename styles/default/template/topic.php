<?php
if (!defined('InternalAccess')) exit('error: 403 Access Denied');
?>
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
		By <a href="<?php echo $Config['WebsitePath'].'/u/'.urlencode($Topic['UserName']); ?>"><?php echo $Topic['UserName']; ?></a>
 at <?php echo FormatTime($Topic['PostTime']); ?> • <?php echo $Topic['Favorites']; ?><?php echo $Lang['People_Collection']; ?> • <?php echo ($Topic['Views']+1); ?><?php echo $Lang['People_Have_Seen']; ?>
		</div>
	</div>
	<div class="detail-avatar"><a href="<?php echo $Config['WebsitePath'].'/u/'.urlencode($Topic['UserName']); ?>">
<?php echo GetAvatar($Topic['UserID'], $Topic['UserName'], 'large'); ?>

	</a></div>
	<div class="c"></div>
</div>
<div class="topic-content">
	<div id="p<?php echo $PostsArray[0]['ID']; ?>">
		<?php echo $PostsArray[0]['Content']; ?>
	</div>
	<div id="edit<?php echo $PostsArray[0]['ID']; ?>" style="width:648px;height:auto;" class="hide"></div>
</div>
<div class="topic-tags btn">

<div id="TagsList">
<div id="TagsElements">
<?php
if($Topic['Tags']){
	foreach (explode("|", $Topic['Tags']) as $Tag) {
?><a href="<?php echo $Config['WebsitePath']; ?>/tag/<?php echo urlencode($Tag); ?>" id="Tag<?php echo md5($Tag); ?>"><?php echo $Tag; ?></a>
<?php
	}
}
?>
</div>
<?php
	if($CurUserRole>=4 || $Topic['UserID']==$CurUserID){ ?>
<a href="###" class="edittag" onclick="javascript:EditTags();"><?php echo $Lang['Edit_Tags']; ?></a>
<?php
	}
?></div>

<div id="EditTags" style="display:none;">
<div id="EditTagsElements">
<?php
if($Topic['Tags']){
	foreach (explode("|", $Topic['Tags']) as $Tag) {
?><a href="###"  onclick="javascript:DeleteTag(<?php echo $ID; ?>, this, '<?php echo $Tag; ?>');"><?php echo $Tag; ?>&nbsp;×</a>
<?php
	}
}
?>
</div>
<div class="c"></div>
<input type="text" name="AlternativeTag" id="AlternativeTag" value="" class="float-left w200" placeholder="<?php echo $Lang['Add_Tags']; ?>" />
<a href="###" class="edittag" onclick="javascript:CompletedEditingTags();"><?php echo $Lang['Complete_Edit_Tags']; ?></a>
</div>


<div style="float:right;">
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
<?php
//if($CurUserRole>=4 || $Topic['UserID']==$CurUserID){
if($CurUserRole>=4){
?>
<a href="###" onclick="javascript:EditPost(<?php echo $PostsArray[0]['ID']; ?>);" style="float:right;"><?php echo $Lang['Edit']; ?></a>
<?php
}
if($CurUserID){
?>
<a href="###" onclick="javascript:Manage(<?php echo $ID; ?>, 4, 1, false, this);" style="float:right;"><?php echo $IsFavorite?$Lang['Unsubscribe']:$Lang['Collect']; ?></a>
<?php
}
?>
</div>
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
			<a href="<?php echo $Config['WebsitePath'].'/u/'.urlencode($Post['UserName']); ?>">
			<?php echo GetAvatar($Post['UserID'], $Post['UserName'], 'middle'); ?>
			</a>
		</div>
		<div class="comment-data">
			<div class="comment-content">
				<div>
					<div class="float-left text-bold fs14"><a href="<?php echo $Config['WebsitePath'].'/u/'.urlencode($Post['UserName']); ?>"><?php echo $Post['UserName'];?></a></div>
					<span class="float-right grey fs12">
						<?php echo FormatTime($Post['PostTime']); ?>&nbsp;&nbsp;
						<a href="#Post<?php echo $Post['ID']; ?>"><span class="commonet-count">#<?php echo $PostFloor; ?></span></a>
					</span>
				</div>
				<div class="c"></div>
				<div id="p<?php echo $Post['ID']; ?>">
					<?php echo $Post['Content']; ?>
				</div>
				<div id="edit<?php echo $Post['ID']; ?>" style="width:588px;height:auto;" class="hide"></div>
			</div>
			<?php if($CurUserID){ ?>
			<div class="comment-button">
				<div class="float-left">
<?php
// if($CurUserRole>=4 || $Post['UserID']==$CurUserID){
if($CurUserRole>=4){
?><a href="###" onclick="javascript:EditPost(<?php echo $Post['ID']; ?>);" title="<?php echo $Lang['Edit']; ?>"><div class="icon icon-edit"></div></a>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
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
if($Topic['IsLocked'] || (!$Topic['IsLocked'] && !$CurUserInfo)){
?>
<script type="text/javascript">
loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/reply.function.js?version=<?php echo $Config['Version']; ?>",function() {
	loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.parse.min.js?version=<?php echo $Config['Version']; ?>", function(){
		RenderTopic();
	});
});
</script>
<script type="text/javascript" charset="utf-8" src="<?php echo $Config['WebsitePath']; ?>/static/js/reply.function.js?version=<?php echo $Config['Version']; ?>"></script>
<div class="ad">
	<p><?php echo $Topic['IsLocked']?$Lang['Topic_Has_Been_Locked']:$Lang['Requirements_For_Login'];; ?></p>
</div>
<?php
}else{
?>

<a name="reply"></a> 
	<script type="text/javascript">
	var MaxPostChars = <?php echo $Config['MaxPostChars']; ?>;//主题内容最多字节数
	loadScript("<?php echo $Config['WebsitePath']; ?>/static/js/reply.function.js?version=<?php echo $Config['Version']; ?>",function() {
		InitNewTagsEditor();
		loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.config.js?version=<?php echo $Config['Version']; ?>",function() {
			loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.all.min.js?version=<?php echo $Config['Version']; ?>",function(){
				loadScript("<?php echo $Config['WebsitePath']; ?>/language/<?php echo ForumLanguage; ?>/<?php echo ForumLanguage; ?>.js?version=<?php echo $Config['Version']; ?>",function(){
					$("#editor").empty();
					InitEditor();
					loadScript("<?php echo $Config['WebsitePath']; ?>/static/editor/ueditor.parse.min.js?version=<?php echo $Config['Version']; ?>", function(){
						RenderTopic();
					});
				});
			})
		});
	});
	</script>
	<form name="reply">
		<input type="hidden" name="FormHash" value="<?php echo $FormHash; ?>">
		<input type="hidden" name="TopicID" value="<?php echo $ID; ?>">
		<p>
			<div id="editor" style="width:100%;height:180px;">Loading……</div>
		</p>
		<div class="float-right"><input type="button" value="<?php echo $Lang['Reply']; ?>(Ctrl+Enter)" class="textbtn" id="ReplyButton" onclick="JavaScript:ReplyToTopic();"/></div>
		<div class="c"></div> 
		<p></p>
	</form>

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
<?php // reply mouse tip start?>
<div class="reply-mouse-tip sider-box" id="reply-mouse-tip">
	<a class="author" href="javascript:;"></a>
	<div class="content">Loading...</div>
</div>