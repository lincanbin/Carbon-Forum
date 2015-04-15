/*
 * Carbon-Forum
 * https://github.com/lincanbin/Carbon-Forum
 *
 * Copyright 2014, Lin Canbin
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A high performance open-source forum software written in PHP. 
 */


//$(document).ready(function(){
//实例化编辑器
window.UEDITOR_CONFIG['textarea'] = 'Content';
//window.UEDITOR_CONFIG['initialFrameHeight'] = 160;
window.UEDITOR_CONFIG['elementPathEnabled'] = false;
window.UEDITOR_CONFIG['toolbars'] = [['fullscreen', 'source', '|', 'bold', 'italic', 'underline', '|' , 'blockquote', 'insertcode', 'insertorderedlist', 'insertunorderedlist', '|', 'emotion', 'simpleupload', 'insertimage', 'scrawl', 'insertvideo', 'music', 'attachment', '|', 'removeformat', 'autotypeset']];
UE.getEditor('editor',{onready:function(){
	if(window.localStorage){
		//从草稿中恢复
		RecoverContents();
	}
	//编辑器内Ctrl + Enter提交回复
	var EditorIframe = document.getElementsByTagName("iframe");
	console.log(EditorIframe);
	for (var i = EditorIframe.length - 1; i >= 0; i--) {
		EditorIframe[i].contentWindow.document.body.onkeydown = function(Event){
			CtrlAndEnter(Event);
		};
		console.log(EditorIframe[i].contentWindow.document);
	};
}});
//});


//编辑器外Ctrl + Enter提交回复
document.body.onkeydown = function(Event){
	CtrlAndEnter(Event);
};

//Ctrl + Enter操作接收函数
function CtrlAndEnter(Event) {
	//console.log("keydown");
	if (Event.ctrlKey && Event.keyCode == 13) {
		document.getElementById("ReplyButton").click();
		Event.preventDefault ? Event.preventDefault() : Event.returnValue = false;//阻止回车的默认操作
	}
}


//可以去除tab的trim
function trim3(str) {
	str = str.replace(/^(\s|\u00A0)+/, '');
	for (var i = str.length - 1; i >= 0; i--) {
		if (/\S/.test(str.charAt(i))) {
			str = str.substring(0, i + 1);
			break;
		}
	}
	return str;
}

//编辑帖子
function EditPost(PostID) {
	//初始化编辑器
	document.getElementById('p' + PostID).style.visibility = "hidden";
	document.getElementById('p' + PostID).style.height = "0";
	window.UEDITOR_CONFIG['textarea'] = 'PostContent' + PostID;
	UE.getEditor('edit' + PostID, {
		onready: function() {
			UE.getEditor('edit' + PostID).setContent(PostContentLists['p' + PostID]); //将帖子内容放到编辑器里
		}
	});
	$("#edit" + PostID).append('<p></p><p><input type="button" value=" ' + Lang['Edit'] + ' " class="textbtn" id="EditButton' + PostID + '" onclick="JavaScript:SubmitEdit(' + PostID + ');">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value=" ' + Lang['Cancel'] + ' " class="textbtn" onclick="JavaScript:DestoryEditor(' + PostID + ');"></p>');

	document.getElementById('edit' + PostID).style.visibility = "visible";
}

//取消编辑（销毁编辑器）
function DestoryEditor(PostID) {
	UE.getEditor('edit' + PostID).destroy();
	document.getElementById('p' + PostID).style.visibility = "visible";
	document.getElementById('p' + PostID).style.height = "auto";
	document.getElementById('edit' + PostID).style.height = "0";
	document.getElementById('edit' + PostID).style.padding = "0";
	document.getElementById('edit' + PostID).style.visibility = "hidden";
}

//提交编辑修改
function SubmitEdit(PostID) {
	var EditCallbackObj = new EditPostCallback(PostID);
	$.ajax({
		url: WebsitePath + "/manage",
		data: {
			ID: PostID,
			Type: 2,
			Action: 'Edit',
			Content: UE.getEditor('edit' + PostID).getContent()
		},
		cache: false,
		dataType: "json",
		type: "POST",
		success: EditCallbackObj.Success
	});

}

//编辑帖子的回调函数
function EditPostCallback(PostID) {
	this.Success = function(Json) {
		if (Json.Status == 1) {
			document.getElementById('p' + PostID).innerHTML = UE.getEditor('edit' + PostID).getContent();
			PostContentLists['p' + PostID] = UE.getEditor('edit' + PostID).getContent();
			DestoryEditor(PostID);
			uParse('.main-content', {
				'rootPath': WebsitePath + '/static/editor/',
				'liiconpath': WebsitePath + '/static/editor/themes/ueditor-list/'
			});
		} else {
			alert(Json.ErrorMessage);
		}
	};
}

//回复帖子提交前的检查
function ReplyToTopic() {
	if (!UE.getEditor('editor').getContent().length) {
		alert(Lang['Content_Empty']);
		UE.getEditor('editor').focus();
	} else {
		$("#ReplyButton").val(Lang['Replying']);
		UE.getEditor('editor').setDisabled('fullscreen');
		$.ajax({
			url: WebsitePath + '/reply',
			data: {
				FormHash: document.reply.FormHash.value,
				TopicID: document.reply.TopicID.value,
				Content: UE.getEditor('editor').getContent()
			},
			type: 'post',
			cache: false,
			dataType: 'json',
			async: false,
			//阻塞防止干扰
			success: function(data) {
				if (data.Status == 1) {
					$("#ReplyButton").val(Lang['Reply_Success']);
					location.href = WebsitePath + "/t/" + data.TopicID + (data.Page > 1 ? "-" + data.Page: "") + "?cache=" + Math.round(new Date().getTime() / 1000) + "#reply";
					if (window.localStorage) {
						//清空草稿箱
						StopAutoSave();
					}
				} else {
					alert(data.ErrorMessage);
					UE.getEditor('editor').setEnabled();
				}
			},
			error: function() {
				alert(Lang['Submit_Failure']);
				UE.getEditor('editor').setEnabled();
				$("#ReplyButton").val(Lang['Submit_Again']);
			}
		});
	}
	return true;
}

//回复某人
function Reply(UserName, PostFloor, PostID) {
	UE.getEditor('editor').setContent('<p>' + Lang['Reply_To'] + '<a href="' + location.pathname + '#Post' + PostID + '">#' + PostFloor + '</a> @' + UserName + ' :<br /></p>', false);
	UE.getEditor('editor').focus(true);
}

if (window.localStorage) {
	var saveTimer = setInterval(function() {
		if (UE.getEditor('editor').getContent().length >= 10) {
			localStorage.setItem(Prefix + "PostContent", UE.getEditor('editor').getContent());
		}
	},
	1000); //每隔N秒保存一次
	function StopAutoSave() {
		clearInterval(saveTimer); //停止保存
		localStorage.removeItem(Prefix + "PostContent"); //清空内容
		UE.getEditor('editor').execCommand("clearlocaldata"); //清空Ueditor草稿箱
	}

	function RecoverContents() {
		var DraftContent = localStorage.getItem(Prefix + "PostContent");
		if (DraftContent) {
			UE.getEditor('editor').setContent(DraftContent);
		}
	}
}