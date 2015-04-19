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

//话题自动补全
$(function() {
	//'use strict';
	// Initialize ajax autocomplete:
	$("#AlternativeTag").autocomplete({
		serviceUrl: WebsitePath + '/json/tag_autocomplete',
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
        },*/
	});

});

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
		alert(Lang['Tags_Empty']);
		document.NewForm.AlternativeTag.focus();
		return false;
	} else {
		UE.getEditor('editor').setDisabled('fullscreen');
		$.ajax({
			url: WebsitePath + '/new',
			data: {
				FormHash: document.NewForm.FormHash.value,
				Title: document.NewForm.Title.value,
				Content: document.NewForm.Content.value,
				Tag: $("input[name='Tag[]']").map(function() {
					return $(this).val();
				}).get()
			},
			type: 'post',
			cache: false,
			dataType: 'json',
			async: false,
			//阻塞防止干扰
			success: function(data) {
				if (data.Status == 1) {
					$("#PublishButton").val(Lang['Submit_Success']);
					location.href = WebsitePath + "/t/" + data.TopicID;
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
				$("#PublishButton").val(Lang['Submit_Again']);
			}
		});
	}
	return true;
}

function CheckTag(TagName, IsAdd) {
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
		i++;
	});
	return show;
}

function GetTags() {
	var CurrentContentHash = md5(document.NewForm.Title.value + document.NewForm.Content.value);
	//取Title与Content 联合Hash值，与之前input的内容比较，不同则开始获取话题，随后保存进hidden input。
	if (CurrentContentHash != document.NewForm.ContentHash.value) {
		if (document.NewForm.Title.value.length || document.NewForm.Content.value.length) {
			$.ajax({
				url: WebsitePath + '/json/get_tags',
				data: {
					Title: document.NewForm.Title.value,
					Content: document.NewForm.Content.value
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
	$("#TagsList").append('<a href="###" onclick="javascript:AddTag(\'' + TagName + '\',' + id + ');" id="TagsList' + id + '">' + TagName + '&nbsp;+</a>');
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

$(function() {
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
});

function TagRemove(TagName, id) {
	$("#Tag" + id).remove();
	TagsListAppend(TagName, id);
	if ($("input[name='Tag[]']").length < MaxTagNum) {
		$("#AlternativeTag").attr("disabled", false);
		$("#AlternativeTag").attr("placeholder", Lang['Add_Tags']);
	}
	document.NewForm.AlternativeTag.focus();
}

if (window.localStorage) {
	var saveTimer = setInterval(function() {
		var TagsList = JSON.stringify($("input[name='Tag[]']").map(function() {
			return $(this).val();
		}).get());
		if (document.NewForm.Title.value.length >= 4) {
			localStorage.setItem(Prefix + "TopicTitle", document.NewForm.Title.value);
		}
		if (document.NewForm.Content.value.length >= 10) {
			localStorage.setItem(Prefix + "TopicContent", document.NewForm.Content.value);
		}
		if (TagsList) {
			localStorage.setItem(Prefix + "TopicTagsList", TagsList);
		}
	},
	1000); //每隔N秒保存一次
	function StopAutoSave() {
		clearInterval(saveTimer); //停止保存
		localStorage.removeItem(Prefix + "TopicTitle"); //清空标题
		localStorage.removeItem(Prefix + "TopicContent"); //清空内容
		localStorage.removeItem(Prefix + "TopicTagsList"); //清空标签
		UE.getEditor('editor').execCommand("clearlocaldata"); //清空Ueditor草稿箱
	}

	function RecoverContents() {
		var DraftTitle = localStorage.getItem(Prefix + "TopicTitle");
		var DraftContent = localStorage.getItem(Prefix + "TopicContent");
		var DraftTagsList = JSON.parse(localStorage.getItem(Prefix + "TopicTagsList"));
		if (DraftTitle) {
			document.NewForm.Title.value = DraftTitle;
		}
		if (DraftContent) {
			UE.getEditor('editor').setContent(DraftContent);
		}
		if (DraftTagsList) {
			for (var i = DraftTagsList.length - 1; i >= 0; i--) {
				AddTag(DraftTagsList[i], Math.round(new Date().getTime() / 1000) + i * 314159);
			};
		}
	}
}