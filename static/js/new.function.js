//屏蔽Tag输入框的回车提交
document.body.onkeydown = function(e) {
	if (13 == e.keyCode) {
		e.preventDefault ? e.preventDefault() : e.returnValue = false;
	}
}

/*
//添加标题、内容输入框失焦监听器
$(document).ready(function(){
	if(document.attachEvent){ 
		document.getElementsByName("Content")[0].attachEvent("onblur",function(){GetTags();});
	} 
	else if(document.addEventListener){
		document.getElementsByName("Content")[0].addEventListener("blur",function(){GetTags();},false);
	} 
});
*/


//话题自动补全
$(function () {
	//'use strict';

	// Initialize ajax autocomplete:
	$("#AlternativeTag").autocomplete({
		serviceUrl: WebsitePath+'/json/tag_autocomplete',
		type: 'post',
		/*lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
			var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
			return re.test(suggestion.value);
		},
		onSelect: function(suggestion) {
			//AddTag(document.NewForm.AlternativeTag.value, Math.round(new Date().getTime()/1000));
		}, 
		onHint: function (hint) {
            alert(hint);
        },*/
	});

});

//提交前的检查
function SubmitCheck()
{
	if(!document.NewForm.Title.value.length)
	{
		alert("标题不能为空！");
		document.NewForm.Title.focus();
		return false;
	}else if(document.NewForm.Title.value.replace(/[^\x00-\xff]/g,"***").length > MaxTitleChars)
	{
		alert("标题长度不能超过"+MaxTitleChars+"字节，当前标题长度为"+document.NewForm.Title.value.replace(/[^\x00-\xff]/g,"***").length+"个字节");
		document.NewForm.Title.focus();
		return false;
	//}else if(!UE.getEditor('editor').getContentTxt().length){
	//	alert("内容不能为空！");
	//	UE.getEditor('editor').focus();
	//	return false;
	}else if(!$("#SelectTags").html()){
		alert("话题不能为空！");
		UE.getEditor('editor').focus();
		return false;
	}else{
		if(window.localStorage){
			//清空草稿箱
			StopAutoSave();
		}
		UE.getEditor('editor').setDisabled('fullscreen');
		return true;
	}
}

function CheckTag(TagName,IsAdd)
{
	var show = true;
	var i = 1;
	$("input[name='Tag[]']").each(
		function(index){
			if(IsAdd && i>=MaxTagNum){
				alert('最多只能插入'+MaxTagNum+'个话题！');
				show=false;
			}
			if(TagName==$(this).val() || TagName==''){
				show=false;
			}
			i++;
		});
	return show;
}

function GetTags()
{
	var CurrentContentHash = md5(document.NewForm.Title.value + UE.getEditor('editor').getContentTxt());
	//取Title与Content 联合Hash值，与之前input的内容比较，不同则开始获取话题，随后保存进hidden input。
	if(CurrentContentHash != document.NewForm.ContentHash.value ){
		if(document.NewForm.Title.value.length || UE.getEditor('editor').getContentTxt().length){
			$.ajax({
				url:WebsitePath+'/json/get_tags',
				data:{
					Title: document.NewForm.Title.value,
					Content: UE.getEditor('editor').getContentTxt()
				},
				type:'post',
				dataType:'json',
				success:function(data){
					if(data.status){
						$("#TagsList").html('');
						for(var i=0;i<data.lists.length;i++)
						{
							if(CheckTag(data.lists[i],0))
							{
								TagsListAppend(data.lists[i],i);
							}
						}
						//$("#TagsList").append('<div class="c"></div>');
					}
				}
			});
		}
		document.NewForm.ContentHash.value = CurrentContentHash;
	}
}

function TagsListAppend(TagName,id){
	$("#TagsList").append('<a href="###" onclick="javascript:AddTag(\''+TagName+'\','+id+');" id="TagsList'+id+'">'+TagName+'&nbsp;+</a>');
	//document.NewForm.AlternativeTag.focus();
}

function AddTag(TagName,id)
{
	if(CheckTag(TagName,1))
	{
		$("#SelectTags").append('<a href="###" onclick="javascript:TagRemove(\''+TagName+'\','+id+');" id="Tag'+id+'">'+TagName+'&nbsp;×<input type="hidden" name="Tag[]" value="'+TagName+'"></a>');
		$("#TagsList"+id).remove();
	}
	//document.NewForm.AlternativeTag.focus();
	$("#AlternativeTag").val("");
	if($("input[name='Tag[]']").length==MaxTagNum)
	{
		$("#AlternativeTag").attr("disabled",true);
		$("#AlternativeTag").attr("placeholder","最多添加"+MaxTagNum+"个话题");
	}
}

//
function TagKeydown(InputObj){
	//alert(event.keyCode);
	switch(event.keyCode)
	{
	case 13:
		if(InputObj.value.length!=0){
			AddTag(InputObj.value, Math.round(new Date().getTime()/1000));
		}
		break;
	case 8:
		if(InputObj.value.length==0){
			var LastTag = $("#SelectTags").children().last();
			TagRemove(LastTag.children().attr("value"), LastTag.attr("id").replace("Tag",""));
		}
		break;
	default:
		return true;
	}
}

function TagRemove(TagName,id)
{
	$("#Tag"+id).remove();
	TagsListAppend(TagName,id);
	if($("input[name='Tag[]']").length<MaxTagNum)
	{
		$("#AlternativeTag").attr("disabled",false);
		$("#AlternativeTag").attr("placeholder","添加话题"); 
	}
	document.NewForm.AlternativeTag.focus();
}

if(window.localStorage){
	var saveTimer= setInterval(function(){
		if(document.NewForm.Title.value.length>=4){
			localStorage.setItem(Prefix+"TopicTitle", document.NewForm.Title.value);
		}
		if(UE.getEditor('editor').getContentTxt().length>=4){
			localStorage.setItem(Prefix+"TopicContent", UE.getEditor('editor').getContent());
		}
	},2000); //每隔N秒保存一次

	function StopAutoSave(){
		clearInterval(saveTimer); //停止保存
		localStorage.removeItem(Prefix+"TopicTitle"); //清空标题
		localStorage.removeItem(Prefix+"TopicContent"); //清空内容
		UE.getEditor('editor').execCommand("clearlocaldata");//清空Ueditor草稿箱
	}

	function RecoverContents(){
		var DraftTitle = localStorage.getItem(Prefix+"TopicTitle");
		var DraftContent = localStorage.getItem(Prefix+"TopicContent");
		if(DraftTitle)
		{
			document.NewForm.Title.value = DraftTitle;
		}
		if(DraftContent)
		{
			UE.getEditor('editor').setContent(DraftContent);
		}
	}
}