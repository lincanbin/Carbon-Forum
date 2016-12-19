/*
 * Carbon-Forum
 * https://github.com/lincanbin/Carbon-Forum
 *
 * Copyright 2006-2015 Canbin Lin (lincanbin@hotmail.com)
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A high performance open-source forum software written in PHP. 
 */

function InitNewTopicEditor(){
	UE.delEditor('editor');
	//Initialize editor
	//建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
	window.UEDITOR_CONFIG['textarea'] = 'Content';
	window.UEDITOR_CONFIG['toolbars'] = [['fullscreen', 'source', '|', 'bold', 'italic', 'underline', 'paragraph', 'fontsize', 'fontfamily', 'forecolor', '|', 'justifyleft','justifycenter', 'justifyright', 'justifyjustify', '|','undo', 'redo'],['insertcode', 'link','inserttable', 'blockquote', 'insertorderedlist', 'insertunorderedlist', '|', 'emotion', 'simpleupload', 'insertimage', 'scrawl', 'insertvideo', 'music', 'attachment', '|', 'removeformat', 'autotypeset']];
	UE.getEditor('editor',{onready:function(){
		//从草稿中恢复
		if(window.localStorage){
			if( typeof SaveDraftTimer != "undefined" ){
				clearInterval(SaveDraftTimer);
				console.log('StopTopicAutoSave');
			}
			if( typeof SavePostDraftTimer != "undefined"){
				clearInterval(SavePostDraftTimer);
				console.log('StopAutoSave');
			}
			//Try to recover previous article from draft
			RecoverTopicContents();
			SaveDraftTimer = setInterval(function() {//Global
				SaveTopicDraft();
			},
			1000); //每隔N秒保存一次
		}
		//二次提交，恢复现场
		if(Content){
			this.setContent(Content);
		}
		//编辑器内Ctrl + Enter提交回复
		var ueditor_id='ueditor_0';
		var frames=document.getElementsByTagName('iframe');
		for(var i=0;i<frames.length;i++){
			if(frames[i].id.indexOf('ueditor_')>-1){
				ueditor_id=frames[i].id;
				break;
			}
		}
		document.getElementById(ueditor_id).contentWindow.document.body.onkeydown = function(Event){
			CtrlAndEnter(Event, false);
		};
	}});
	//编辑器外Ctrl + Enter提交回复
	document.body.onkeydown = function(Event){
		CtrlAndEnter(Event, true);
	};

	//话题自动补全
	// Initialize ajax autocomplete:
	$("#AlternativeTag").autocomplete({
		serviceUrl: WebsitePath + '/json/tag_autocomplete',
		minChars: 2,
		type: 'post'
		/*,
		lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
			var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
			return re.test(suggestion.value);
		},
		onSelect: function(suggestion) {
			//AddTag(document.NewForm.AlternativeTag.value, Math.round(new Date().getTime()/1000));
		}, 
		onHint: function (hint) {
			alert(hint);
		},
		*/
	});
	$("#AlternativeTag").keydown(function(e) {
		var e = e || event;
		switch (e.keyCode) {
		case 13:
			if ($("#AlternativeTag").val().length != 0) {
				AddTag($("#AlternativeTag").val(), Math.round(new Date().getTime() / 1000));
			}
			break;
		case 8:
			if ($("#AlternativeTag").val().length == 0) {
				var LastTag = $("#SelectTags").children().last();
				TagRemove(LastTag.children().attr("value"), LastTag.attr("id").replace("Tag", ""));
			}
			break;
		default:
			return true;
		}
	});
}



//Ctrl + Enter操作接收函数
function CtrlAndEnter(Event, IsPreventDefault) {
	//console.log("keydown");
	if (Event.keyCode == 13) {
		if(IsPreventDefault){
			//屏蔽Tag输入框的回车提交，阻止回车的默认操作
			Event.preventDefault ? Event.preventDefault() : Event.returnValue = false;
		}
		if(Event.ctrlKey){
			$("#PublishButton").click();
		}
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

//提交前的检查
function CreateNewTopic() {
	if (!document.NewForm.Title.value.length) {
		alert(Lang['Title_Can_Not_Be_Empty']);
		document.NewForm.Title.focus();
		return false;
	} else if (document.NewForm.Title.value.replace(/[^\x00-\xff]/g, "***").length > MaxTitleChars) {
		alert(Lang['Title_Too_Long'].replace("{{MaxTitleChars}}", MaxTitleChars).replace("{{Current_Title_Length}}", document.NewForm.Title.value.replace(/[^\x00-\xff]/g, "***").length));
		document.NewForm.Title.focus();
		return false;
	} else if (!$("#SelectTags").html()) {
		if ($("#AlternativeTag").val().length != 0) {
			AddTag($("#AlternativeTag").val(), Math.round(new Date().getTime() / 1000));
		}else{
			alert(Lang['Tags_Empty']);
			document.NewForm.AlternativeTag.focus();
			return false;
		}
	} else {
		$("#PublishButton").val(Lang['Submitting']);
		UE.getEditor('editor').setDisabled('fullscreen');
		$.ajax({
			url: WebsitePath + '/new',
			data: {
				FormHash: document.NewForm.FormHash.value,
				Title: document.NewForm.Title.value,
				Content: UE.getEditor('editor').getContent(),
				Tag: $("input[name='Tag[]']").map(function() {
					return $(this).val();
				}).get()
			},
			type: 'post',
			cache: false,
			dataType: 'json',
			async: true,
			success: function(data) {
				if (data.Status == 1) {
					$("#PublishButton").val(Lang['Submit_Success']);
					$.pjax({
						url: WebsitePath + "/t/" + data.TopicID, 
						container: '#main'
					});
					//location.href = WebsitePath + "/t/" + data.TopicID;
					if (window.localStorage) {
						//清空草稿箱
						StopTopicAutoSave();
					}
				} else {
					alert(data.ErrorMessage);
					UE.getEditor('editor').setEnabled();
				}
			},
			error: function() {
				alert(Lang['Submit_Failure']);
				UE.getEditor('editor').setEnabled();
				$("#PublishButton").val(Lang['Submit_Again']);
			}
		});
	}
	return true;
}

function CheckTag(TagName, IsAdd) {
	TagName = $.trim(TagName);
	var show = true;
	var i = 1;
	$("input[name='Tag[]']").each(function(index) {
		if (IsAdd && i >= MaxTagNum) {
			alert(Lang['Tags_Too_Much'].replace("{{MaxTagNum}}", MaxTagNum));
			show = false;
		}
		if (TagName == $(this).val() || TagName == '') {
			show = false;
		}
		//简单的前端过滤，后端有更严格的白名单过滤所以这里随便写个正则应付下了。
		if (TagName.match(/[&|<|>|"|']/g) != null) {
			//alert('Invalid input! ')
			show = false;
		}
		i++;
	});
	return show;
}

function GetTags() {
	var CurrentContentHash = md5(document.NewForm.Title.value + UE.getEditor('editor').getContentTxt());
	//取Title与Content 联合Hash值，与之前input的内容比较，不同则开始获取话题，随后保存进hidden input。
	if (CurrentContentHash != document.NewForm.ContentHash.value) {
		if (document.NewForm.Title.value.length || UE.getEditor('editor').getContentTxt().length) {
			$.ajax({
				url: WebsitePath + '/json/get_tags',
				data: {
					Title: document.NewForm.Title.value,
					Content: UE.getEditor('editor').getContentTxt()
				},
				type: 'post',
				dataType: 'json',
				success: function(data) {
					if (data.status) {
						$("#TagsList").html('');
						for (var i = 0; i < data.lists.length; i++) {
							if (CheckTag(data.lists[i], 0)) {
								TagsListAppend(data.lists[i], i);
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

function TagsListAppend(TagName, id) {
	$("#TagsList").append('<a href="###" onclick="javascript:AddTag(\'' + TagName + '\',' + id + ');GetTags();" id="TagsList' + id + '">' + TagName + '&nbsp;+</a>');
	//document.NewForm.AlternativeTag.focus();
}

function AddTag(TagName, id) {
	if (CheckTag(TagName, 1)) {
		$("#SelectTags").append('<a href="###" onclick="javascript:TagRemove(\'' + TagName + '\',' + id + ');" id="Tag' + id + '">' + TagName + '&nbsp;×<input type="hidden" name="Tag[]" value="' + TagName + '" /></a>');
		$("#TagsList" + id).remove();
	}
	//document.NewForm.AlternativeTag.focus();
	$("#AlternativeTag").val("");
	if ($("input[name='Tag[]']").length == MaxTagNum) {
		$("#AlternativeTag").attr("disabled", true);
		$("#AlternativeTag").attr("placeholder", Lang['Tags_Too_Much'].replace("{{MaxTagNum}}", MaxTagNum));
	}
}


function TagRemove(TagName, id) {
	$("#Tag" + id).remove();
	TagsListAppend(TagName, id);
	if ($("input[name='Tag[]']").length < MaxTagNum) {
		$("#AlternativeTag").attr("disabled", false);
		$("#AlternativeTag").attr("placeholder", Lang['Add_Tags']);
	}
	document.NewForm.AlternativeTag.focus();
}

//Save Draft
function SaveTopicDraft() {
	try{
		var TagsList = JSON.stringify($("input[name='Tag[]']").map(function() {
			return $(this).val();
		}).get());
		if (document.NewForm.Title.value.length >= 4) {
			localStorage.setItem(Prefix + "TopicTitle", document.NewForm.Title.value);
		}
		if (UE.getEditor('editor').getContent().length >= 10) {
			localStorage.setItem(Prefix + "TopicContent", UE.getEditor('editor').getContent());
		}
		if (TagsList) {
			localStorage.setItem(Prefix + "TopicTagsList", TagsList);
		}
	}catch(oException){
		if(oException.name == 'QuotaExceededError'){
			console.log('Draft Overflow! ');
			localStorage.clear();//Clear all draft
			SaveTopicDraft();//Save draft again
		}
	}
	
}

function StopTopicAutoSave() {
	clearInterval(SaveDraftTimer); //停止保存
	localStorage.removeItem(Prefix + "TopicTitle"); //清空标题
	localStorage.removeItem(Prefix + "TopicContent"); //清空内容
	localStorage.removeItem(Prefix + "TopicTagsList"); //清空标签
	UE.getEditor('editor').execCommand("clearlocaldata"); //清空Ueditor草稿箱
}

function RecoverTopicContents() {
	var DraftTitle = localStorage.getItem(Prefix + "TopicTitle");
	var DraftContent = localStorage.getItem(Prefix + "TopicContent");
	var DraftTagsList = JSON.parse(localStorage.getItem(Prefix + "TopicTagsList"));
	if (DraftTitle) {
		document.NewForm.Title.value = DraftTitle;
	}
	if (DraftContent) {
		UE.getEditor('editor').setContent(DraftContent);
	}else{
		UE.getEditor('editor').execCommand('cleardoc');
	}
	if (DraftTagsList) {
		for (var i = DraftTagsList.length - 1; i >= 0; i--) {
			AddTag(DraftTagsList[i], Math.round(new Date().getTime() / 1000) + i * 314159);
		};
	}
}
