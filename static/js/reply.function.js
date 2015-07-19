/* global $ */
/*
 * Carbon-Forum
 * https://github.com/lincanbin/Carbon-Forum
 *
 * Copyright 2015, Lin Canbin
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A high performance open-source forum software written in PHP. 
 */


function RenderTopic(){
	//强制所有链接在新窗口中打开
	var AllPosts = document.getElementsByClassName("comment-content");
	PostContentLists = {};//Global
	AllPosts[AllPosts.length]=document.getElementsByClassName("topic-content")[0];
	if(document.getElementsByClassName("topic-content").length>0){
		PostContentLists[document.getElementsByClassName("topic-content")[0].childNodes[1].id] = trim3(document.getElementsByClassName("topic-content")[0].childNodes[1].innerHTML);
	}
	//console.log(PostContentLists);
	for (var j=0; j<AllPosts.length; j++) {
		PostContentLists[document.getElementsByClassName("comment-content")[j].childNodes[5].id] = trim3(document.getElementsByClassName("comment-content")[j].childNodes[5].innerHTML);
		//console.log(PostContentLists);
		var AllLinks = AllPosts[j].getElementsByTagName("a");
		for(var i=0; i<AllLinks.length; i++)
		{
			var a = AllLinks[i];
			//console.log(a);
			if(a.host != location.host){
				a.target="_blank";
			}
		};
	};
	//样式渲染需最后进行
	uParse('.main-content',{
		'rootPath': WebsitePath + '/static/editor/',
		'liiconpath':WebsitePath + '/static/editor/themes/ueditor-list/'//使用 '/' 开头的绝对路径
	});
}

function InitEditor(){
	//Initialize editor
	UE.delEditor('editor');
	window.UEDITOR_CONFIG['textarea'] = 'Content';
	window.UEDITOR_CONFIG['elementPathEnabled'] = false;
	window.UEDITOR_CONFIG['toolbars'] = [['fullscreen', 'source', '|', 'bold', 'italic', 'underline', '|' , 'blockquote', 'insertcode', 'insertorderedlist', 'insertunorderedlist', '|', 'emotion', 'simpleupload', 'insertimage', 'scrawl', 'insertvideo', 'music', 'attachment', '|', 'removeformat', 'autotypeset']];
	UE.getEditor('editor',{onready:function(){
		if(window.localStorage){
			SavePostDraftTimer = setInterval(function() {//Global
				SavePostDraft();
			},
			1000); //每隔N秒保存一次
			//Try to recover previous article from draft
			RecoverContents();
		}
		//Press Ctrl + Enter to submit in editor
		var EditorIframe = document.getElementsByTagName("iframe");
		//console.log(EditorIframe);
		for (var i = EditorIframe.length - 1; i >= 0; i--) {
			EditorIframe[i].contentWindow.document.body.onkeydown = function(Event){
				CtrlAndEnter(Event);
			};
			//console.log(EditorIframe[i].contentWindow.document);
		};
	}});
	//编辑器外Ctrl + Enter提交回复
	document.body.onkeydown = function(Event){
		CtrlAndEnter(Event);
	};
	console.log('editor loaded.');
}



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
	if(str){
		str = str.replace(/^(\s|\u00A0)+/, '');
		for (var i = str.length - 1; i >= 0; i--) {
			if (/\S/.test(str.charAt(i))) {
				str = str.substring(0, i + 1);
				break;
			}
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
	$("#edit" + PostID).html("");
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
			async: true,
			success: function(data) {
				if (data.Status == 1) {
					$("#ReplyButton").val(Lang['Reply_Success']);
					if (window.localStorage) {
						//清空草稿箱
						StopAutoSave();
					}
					location.href = WebsitePath + "/t/" + data.TopicID + (data.Page > 1 ? "-" + data.Page: "") + "?cache=" + Math.round(new Date().getTime() / 1000) + "#Post" + data.PostID;
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
	UE.getEditor('editor').setContent('<p>' + Lang['Reply_To'] + '<a href="' + location.pathname + '#Post' + PostID + '">#' + PostFloor + '</a> @' + UserName + ' :<br /></p><p></p>', false);
	UE.getEditor('editor').focus(true);
}

//引用某人
function Quote(UserName, PostFloor, PostID) {
	UE.getEditor('editor').setContent('<p></p><blockquote><a href="' + location.pathname + '#Post' + PostID + '">#' + PostFloor + '</a> @' + UserName + ' :<br />'+PostContentLists['p' + PostID] + '<blockquote>');
	UE.getEditor('editor').focus(false);
}

//Save Draft
function SavePostDraft() {
	try{
		if (UE.getEditor('editor').getContent().length >= 10) {
			localStorage.setItem(Prefix + "PostContent" + TopicID, UE.getEditor('editor').getContent());
		}
	}catch(oException){
		if(oException.name == 'QuotaExceededError'){
			console.log('Draft Overflow! ');
			localStorage.clear();//Clear all draft
			SavePostDraft();//Save draft again
		}
	}
}

function StopAutoSave() {
	clearInterval(SavePostDraftTimer); //停止保存
	localStorage.removeItem(Prefix + "PostContent" + TopicID); //清空内容
	UE.getEditor('editor').execCommand("clearlocaldata"); //清空Ueditor草稿箱
}

function RecoverContents() {
	var DraftContent = localStorage.getItem(Prefix + "PostContent" + TopicID);
	if (DraftContent) {
		UE.getEditor('editor').setContent(DraftContent);
	}
}

$(document).ready(function () {
	// 回帖内容鼠标提示
	if (TopicID) {
		var postA = $('a[href*="#Post"]');
		var posts = {};
		var tip = $("#reply-mouse-tip");
		var tipAuthor = tip.find("a.author");
		var tipContent = tip.find("div.content");
		var showTip = function (ele, data) {
			if (!data) {
				$("#reply-mouse-tip").hide();
				return false;
			}
			tipAuthor.text(data.UserName);
			tipAuthor.attr("href", WebsitePath + "/u/" + data.UserName);
			tipContent.html(data.Content);
		};
		var attach = function (ele, postId) {
			ele.hover(function () {
				var pos = ele.position();
				pos.top += ele.height();
				tip.css(pos).show();
				tipAuthor.text("");
				tipContent.text("Loading...");
				if (postId in posts) {
					showTip(ele, posts[postId]);
				} else {
					$.post(WebsitePath + "/json/get_post", { PostId: postId })
						.success(function (data) {
						posts[postId] = data;
						showTip(ele, data);
					});
				}
			}, function () {
					$("#reply-mouse-tip").hide();
			});
		};
		for (var index = 0; index < postA.length; index++) {
			var $element = $(postA[index]);
			var postId = $element.attr("href").match(/#Post([0-9]+)/)
			if (postId) {
				postId = postId[1];
				attach($element, postId);
			}
		}
	}
});