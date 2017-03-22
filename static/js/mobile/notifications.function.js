/*
 * Carbon-Forum
 * https://github.com/lincanbin/Carbon-Forum
 *
 * Copyright 2006-2017 Canbin Lin (lincanbin@hotmail.com)
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A high performance open-source forum software written in PHP. 
 */

function renderTemplate(template, list) {
	var buffer = [];
	var temp = '';
	for (var i = 0; i < list.length; i++) {
		temp = template;
		$.each(list[i], function(k, v) {
			temp = temp.replace(new RegExp('{{' + k + '}}', 'g'), v);
		});
		buffer.push(temp);
	}
	return buffer.join("");
}

function loadMoreReply(forceToShow) {
	var RepliedToMeList = $("#RepliedToMeList");
	var RepliedToMePage = $("#RepliedToMePage");
	var RepliedToMeLoading = $("#RepliedToMeLoading");

	if (forceToShow || (RepliedToMeList.is(":visible") && RepliedToMeLoading.val() != "1")) {
		RepliedToMeLoading.val("1");
		$.ajax({
			url: WebsitePath + '/notifications/reply/page/' + RepliedToMePage.val(),
			type: 'GET',
			dataType: 'json',
			success: function(Result) {
				RepliedToMeLoading.val("0");
				if (Result.Status == 1) {
					var Template = $("#RepliedToMePostTemplate").html();
					RepliedToMeList.append(renderTemplate(Template, Result.ReplyArray));
					RepliedToMePage.val(parseInt(RepliedToMePage.val()) + 1);
					if (Result.ReplyArray.length === 0) {
						RepliedToMeLoading.val("1");
					}
				}
			},
			error: function() {
				RepliedToMeLoading.val("0");
			}
		});
	}
}

function loadMoreMention(forceToShow) {
	var MentionedMeList = $("#MentionedMeList");
	var MentionedMePage = $("#MentionedMePage");
	var MentionedMeLoading = $("#MentionedMeLoading");

	if (forceToShow || (MentionedMeList.is(":visible") && MentionedMeLoading.val() != "1")) {
		MentionedMeLoading.val("1");
		$.ajax({
			url: WebsitePath + '/notifications/mention/page/' + MentionedMePage.val(),
			type: 'GET',
			dataType: 'json',
			success: function(Result) {
				MentionedMeLoading.val("0");
				if (Result.Status == 1) {
					var Template = $("#MentionedMePostTemplate").html();
					MentionedMeList.append(renderTemplate(Template, Result.MentionArray));
					MentionedMePage.val(parseInt(MentionedMePage.val()) + 1);
					if (Result.MentionArray.length === 0) {
						MentionedMeLoading.val("1");
					}
				}
			},
			error: function() {
				MentionedMeLoading.val("0");
			}
		});
	}
}


function loadNotificationsList(_this) {
	var url = window.document.location.pathname;
	var endStr = '/notifications/list';
	var d = url.length - endStr.length;
	if (d >= 0 && url.lastIndexOf(endStr) == d) {
		var top = _this.scrollTop();
		if (top + $(".panel.active[selected=true]").height() + 20 >= _this[0].scrollHeight && top > 20) {
			loadMoreReply(false);
			loadMoreMention(false);
		}
	} else {
		return false;
	}
}
