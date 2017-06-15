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


/*
// 当使用的jQuery CDN爆炸时，切换到当前服务器的jQuery备胎
if (!window.jQuery) {
	console.log('Switch to jQuery Backup.');
	loadScript(WebsitePath + "/static/js/jquery.js",function(){
		loadScript(WebsitePath + "/static/js/global.js",function(){});
	});
}
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

	if (forceToShow || (RepliedToMeList.is(":visible") && RepliedToMeLoading.val() !== "1")) {
		RepliedToMeLoading.val("1");
		$.ajax({
			url: WebsitePath + '/notifications/reply/page/' + RepliedToMePage.val(),
			type: 'GET',
			dataType: 'json',
			success: function(Result) {
				RepliedToMeLoading.val("0");
				if (Result.Status === 1) {
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

	if (forceToShow || (MentionedMeList.is(":visible") && MentionedMeLoading.val() !== "1")) {
		MentionedMeLoading.val("1");
		$.ajax({
			url: WebsitePath + '/notifications/mention/page/' + MentionedMePage.val(),
			type: 'GET',
			dataType: 'json',
			success: function(Result) {
				MentionedMeLoading.val("0");
				if (Result.Status === 1) {
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

function loadMoreInbox(forceToShow) {
	var InboxList = $("#InboxList");
	var InboxPage = $("#InboxPage");
	var InboxLoading = $("#InboxLoading");

	if (forceToShow || (InboxList.is(":visible") && InboxLoading.val() !== "1")) {
		InboxLoading.val("1");
		$.ajax({
			url: WebsitePath + '/notifications/inbox/page/' + InboxPage.val(),
			type: 'GET',
			dataType: 'json',
			success: function(Result) {
				InboxLoading.val("0");
				if (Result.Status === 1) {
					var Template = $("#InboxTemplate").html();
					InboxList.append(renderTemplate(Template, Result.InboxArray));
					InboxPage.val(parseInt(InboxPage.val()) + 1);
					if (Result.InboxArray.length === 0) {
						InboxLoading.val("1");
					}
				}
			},
			error: function() {
				InboxLoading.val("0");
			}
		});
	}
}

function loadMoreMessages(forceToShow) {
	var MessagesList = $("#MessagesList");
	var InboxID = $("#InboxID").val();
	var MessagesPage = $("#MessagesPage");
	var MessagesLoading = $("#MessagesLoading");

	if (forceToShow || MessagesLoading.val() !== "1") {
		MessagesLoading.val("1");
		$.ajax({
			url: WebsitePath + '/inbox/' + InboxID + '/list/page/' + MessagesPage.val(),
			type: 'GET',
			dataType: 'json',
			success: function(Result) {
				MessagesLoading.val("0");
				if (Result.Status === 1) {
					var Template = $("#MessageTemplate").html();
					for (var i = Result.MessagesArray.length - 1; i >= 0; i--) {
						Result.MessagesArray[i]['Position'] = Result.MessagesArray[i]['IsMe'] ? 'right' : 'left';
					}
					MessagesList.append(renderTemplate(Template, Result.MessagesArray));
					MessagesPage.val(parseInt(MessagesPage.val()) + 1);
					if (Result.MessagesArray.length === 0) {
						MessagesLoading.val("1");
					}
				}
			},
			error: function() {
				MessagesLoading.val("0");
			}
		});
	}
}

$(function() {
	//Button go to top
	function setButtonToTop() {
		//Force to refresh under pjax
		$("#go-to-top").css('left', (Math.max(document.body.clientWidth, 960) - 960) / 2 + 690);
		$("#go-to-top").unbind('click');
		$("#go-to-top").click(function() {
			$("html, body").animate({
				"scrollTop": 0
			},
			400);
			return false;
		});

		isNotificationPage = isUrlEndWith('/notifications/list');
		isInboxPage = (new RegExp("/inbox/[0-9]+$")).test(window.document.location.pathname);
	}

	function isUrlEndWith(endStr) {
		var url = window.document.location.pathname;
		var d = url.length - endStr.length;
		return d >= 0 && url.lastIndexOf(endStr) === d;
	}

	function loadNotificationsList() {
		var top = $(this).scrollTop();
		if (top + $(window).height() + 20 >= $(document).height() && top > 20) {
			loadMoreReply(false);
			loadMoreMention(false);
			loadMoreInbox(false);
		}
	}
	
	function loadMessagesList() {
		var top = $(this).scrollTop();
		if (top + $(window).height() + 20 >= $(document).height() && top > 20) {
			loadMoreMessages(false);
		}
	}

	//Initialize position of button
	setButtonToTop();
	var isNotificationPage = isUrlEndWith('/notifications/list');
	var isInboxPage = (new RegExp("/inbox/[0-9]+$")).test(window.document.location.pathname);
	$(window).scroll(function() {
		var top = $(document).scrollTop();
		var g = $("#go-to-top");
		if (top > 500 && g.is(":hidden")) {
			g.fadeIn();
		} else if (top < 500 && g.is(":visible")) {
			g.fadeOut();
		}
		if (isNotificationPage) {
			loadNotificationsList();
		}
		if (isInboxPage) {
			loadMessagesList();
		}
	});
	$(window).resize(function() {
		$("#go-to-top").css('left', (Math.max(document.body.clientWidth, 960) - 960) / 2 + 690);
	});
	//Search box
	$("#SearchButton").click(function() {
		if ($("#SearchInput").val()) {
			$.pjax({
				url: WebsitePath + "/search/" + encodeURIComponent($("#SearchInput").val()),
				container: '#main'
			});
			//location.href = WebsitePath + "/search/" + encodeURIComponent($("#SearchInput").val());
		}
		//preventDefault
		return false;
	});
	$("#SearchInput").autocomplete({
		serviceUrl: WebsitePath + '/json/tag_autocomplete',
		minChars: 2,
		type: 'post'
	});
	//For IE
	$.ajaxSetup({
		cache: false
	});
	//Pjax
	$(document).pjax('a:not(a[target="_blank"])', '#main', {
		fragment: '#main',
		timeout: 10000,
		maxCacheLength: 0
	});
	$(document).on('pjax:send',
	function() {
		$('#progressBar').show();
		$("#progressBar1").css('width', 0);
		$('#progressBar1').animate({
			width: "100%"
		},
		800, "linear");
	});
	$(document).on('pjax:complete',
	function() {
		$('#progressBar').hide();
		setButtonToTop();
	});
	//$(document).pjax('a', 'body');
	//改变导航栏的点击CSS
	$(".buttons a").click(function(e) {
		//console.log($(this));
		$(".buttons a").removeClass();
		$(this).attr("class", "buttons-active");
	});
});

//登录前检查用户名是否存在
function CheckUserNameExist() {
	if ($("#UserName").val()) {
		$.ajax({
			url: WebsitePath + '/json/user_exist',
			data: {
				UserName: $("#UserName").val()
			},
			type: 'post',
			dataType: 'json',
			success: function(Json) {
				if (Json.Status === 0) {
					$("#UserName").addClass("inputnotice");
				} else {
					$("#UserName").removeClass("inputnotice");
				}
			}
		});
	} else {
		$("#UserName").addClass("inputnotice");
	}
}


//获取实时信息通知
function GetNotification() {
	var NotificationSettings = {
		type: "post",
		cache: false,
		url: WebsitePath + '/json/get_notifications',
		dataType: 'json',
		async: true,
		success: function(Data) {
			if (Data.Status !== 0) {
				ShowNotification(Data.NewMessage);
			}
			//获取到新消息，30秒后再请求
			//没有则3秒后再开新线程
			setTimeout(function() {
				$.ajax(NotificationSettings);
			},
			(Data.NewMessage > 0) ? 30000 : 3000);
		},
		error: function() {
			//遇见错误15秒后重试
			setTimeout(function() {
				$.ajax(NotificationSettings);
			},
			15000);
		}
	};
	$.ajax(NotificationSettings);
	console.log('start getting notification at ' + new Date().toLocaleString());
}

//HTML5的Notification API，用来进行消息提示
function ShowNotification(NewMessageNumber) {
	if (NewMessageNumber > 0) {
		document.title = '(' + Lang['New_Message'].replace('{{NewMessage}}', NewMessageNumber) + ')' + document.title.replace(new RegExp(('\\(' + Lang['New_Message'] + '\\)').replace('{{NewMessage}}', '\\d+'), "g"), '');
		$("#MessageNumber").css("visibility", "visible");
		$("#MessageNumber").html(NewMessageNumber);
		var EnableNotification = true;
		if(window.localStorage){
			var NotificationTime = localStorage.getItem(Prefix + "NotificationTime");
			if(NotificationTime){
				//如果距离上次弹出时间大于30s，才允许弹出通知
				EnableNotification = (Math.round(new Date().getTime()/1000)-parseInt(NotificationTime))>30;
				console.log(EnableNotification);
			}
		}
		if (EnableNotification && window.Notification && Notification.permission !== "denied") {
			Notification.requestPermission(function(Status) { // 请求权限
				if (Status === 'granted') {
					// 弹出一个通知
					var CarbonNotification = new Notification(Lang["New_Message"].replace("{{NewMessage}}", NewMessageNumber), {
						icon: WebsitePath + '/static/img/apple-touch-icon-57x57-precomposed.png',
						body: ""
					});
					CarbonNotification.onclick = function() {
						window.open(document.location.protocol + "//" + location.host + WebsitePath + "/notifications/list#notifications1");
					};
					//设置通知弹出的Unix时间戳，实现多网页同步，以防止多次弹出窗口。
					if(window.localStorage){
						localStorage.setItem(Prefix + "NotificationTime", Math.round(new Date().getTime()/1000));
					}
					// 30秒后关闭通知
					setTimeout(function() {
						CarbonNotification.close();
					},
					30000);
				}
			});

		}
	} else {
		document.title = document.title.replace(new RegExp(('\\(' + Lang['New_Message'] + '\\)').replace('{{NewMessage}}', '\\d+'), "g"), '');
		$("#MessageNumber").html("0");
		$("#MessageNumber").css("visibility", "hidden");
	}
	return NewMessageNumber;
}


//异步非阻塞加载JavaScript脚本文件
function loadScript(url, callback) {
	var script = document.createElement("script");
	script.id = md5(url);
	script.type = "text/javascript";
	if (script.readyState) { //IE
		script.onreadystatechange = function() {
			if (script.readyState === "loaded" || script.readyState === "complete") {
				script.onreadystatechange = null;
				callback();
			}
		};
	} else { //Others
		script.onload = function() {
			callback();
		};
	}
	script.src = url;
	if ($("#" + script.id).length === 0) {
		document.getElementsByTagName("head")[0].appendChild(script);
	} else {
		callback();
		//console.log(url);
	}
}

//管理函数的完成回调
function ManageCallback(TargetTag) {
	this.Success = function(Json) {
		if (Json.Status === 1) {
			//alert(Json.Message);
			$(TargetTag).text(Json.Message);
			//window.location.reload();
		} else {
			$(TargetTag).text(Json.ErrorMessage);
			//alert(Json.ErrorMessage);
		}
	};
}

//管理
function Manage(ID, Type, Action, NeedToConfirm, TargetTag) {
	var Lang = Lang || window.Lang;
	if (NeedToConfirm ? confirm(Lang['Confirm_Operation']) : true) {
		$(TargetTag).text("Loading");
		var CallbackObj = new ManageCallback(TargetTag);
		$.ajax({
			url: WebsitePath + "/manage",
			data: {
				ID: ID,
				Type: Type,
				Action: Action
			},
			cache: false,
			dataType: "json",
			type: "POST",
			success: CallbackObj.Success
		});
	}
}


/*
 * JavaScript MD5 1.0.1
 * https://github.com/blueimp/JavaScript-MD5
 *
 * Copyright 2011, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 * 
 * Based on
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Version 2.2 Copyright (C) Paul Johnston 1999 - 2009
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for more info.
 */
!function(a){"use strict";function b(a,b){var c=(65535&a)+(65535&b),d=(a>>16)+(b>>16)+(c>>16);return d<<16|65535&c}function c(a,b){return a<<b|a>>>32-b}function d(a,d,e,f,g,h){return b(c(b(b(d,a),b(f,h)),g),e)}function e(a,b,c,e,f,g,h){return d(b&c|~b&e,a,b,f,g,h)}function f(a,b,c,e,f,g,h){return d(b&e|c&~e,a,b,f,g,h)}function g(a,b,c,e,f,g,h){return d(b^c^e,a,b,f,g,h)}function h(a,b,c,e,f,g,h){return d(c^(b|~e),a,b,f,g,h)}function i(a,c){a[c>>5]|=128<<c%32,a[(c+64>>>9<<4)+14]=c;var d,i,j,k,l,m=1732584193,n=-271733879,o=-1732584194,p=271733878;for(d=0;d<a.length;d+=16)i=m,j=n,k=o,l=p,m=e(m,n,o,p,a[d],7,-680876936),p=e(p,m,n,o,a[d+1],12,-389564586),o=e(o,p,m,n,a[d+2],17,606105819),n=e(n,o,p,m,a[d+3],22,-1044525330),m=e(m,n,o,p,a[d+4],7,-176418897),p=e(p,m,n,o,a[d+5],12,1200080426),o=e(o,p,m,n,a[d+6],17,-1473231341),n=e(n,o,p,m,a[d+7],22,-45705983),m=e(m,n,o,p,a[d+8],7,1770035416),p=e(p,m,n,o,a[d+9],12,-1958414417),o=e(o,p,m,n,a[d+10],17,-42063),n=e(n,o,p,m,a[d+11],22,-1990404162),m=e(m,n,o,p,a[d+12],7,1804603682),p=e(p,m,n,o,a[d+13],12,-40341101),o=e(o,p,m,n,a[d+14],17,-1502002290),n=e(n,o,p,m,a[d+15],22,1236535329),m=f(m,n,o,p,a[d+1],5,-165796510),p=f(p,m,n,o,a[d+6],9,-1069501632),o=f(o,p,m,n,a[d+11],14,643717713),n=f(n,o,p,m,a[d],20,-373897302),m=f(m,n,o,p,a[d+5],5,-701558691),p=f(p,m,n,o,a[d+10],9,38016083),o=f(o,p,m,n,a[d+15],14,-660478335),n=f(n,o,p,m,a[d+4],20,-405537848),m=f(m,n,o,p,a[d+9],5,568446438),p=f(p,m,n,o,a[d+14],9,-1019803690),o=f(o,p,m,n,a[d+3],14,-187363961),n=f(n,o,p,m,a[d+8],20,1163531501),m=f(m,n,o,p,a[d+13],5,-1444681467),p=f(p,m,n,o,a[d+2],9,-51403784),o=f(o,p,m,n,a[d+7],14,1735328473),n=f(n,o,p,m,a[d+12],20,-1926607734),m=g(m,n,o,p,a[d+5],4,-378558),p=g(p,m,n,o,a[d+8],11,-2022574463),o=g(o,p,m,n,a[d+11],16,1839030562),n=g(n,o,p,m,a[d+14],23,-35309556),m=g(m,n,o,p,a[d+1],4,-1530992060),p=g(p,m,n,o,a[d+4],11,1272893353),o=g(o,p,m,n,a[d+7],16,-155497632),n=g(n,o,p,m,a[d+10],23,-1094730640),m=g(m,n,o,p,a[d+13],4,681279174),p=g(p,m,n,o,a[d],11,-358537222),o=g(o,p,m,n,a[d+3],16,-722521979),n=g(n,o,p,m,a[d+6],23,76029189),m=g(m,n,o,p,a[d+9],4,-640364487),p=g(p,m,n,o,a[d+12],11,-421815835),o=g(o,p,m,n,a[d+15],16,530742520),n=g(n,o,p,m,a[d+2],23,-995338651),m=h(m,n,o,p,a[d],6,-198630844),p=h(p,m,n,o,a[d+7],10,1126891415),o=h(o,p,m,n,a[d+14],15,-1416354905),n=h(n,o,p,m,a[d+5],21,-57434055),m=h(m,n,o,p,a[d+12],6,1700485571),p=h(p,m,n,o,a[d+3],10,-1894986606),o=h(o,p,m,n,a[d+10],15,-1051523),n=h(n,o,p,m,a[d+1],21,-2054922799),m=h(m,n,o,p,a[d+8],6,1873313359),p=h(p,m,n,o,a[d+15],10,-30611744),o=h(o,p,m,n,a[d+6],15,-1560198380),n=h(n,o,p,m,a[d+13],21,1309151649),m=h(m,n,o,p,a[d+4],6,-145523070),p=h(p,m,n,o,a[d+11],10,-1120210379),o=h(o,p,m,n,a[d+2],15,718787259),n=h(n,o,p,m,a[d+9],21,-343485551),m=b(m,i),n=b(n,j),o=b(o,k),p=b(p,l);return[m,n,o,p]}function j(a){var b,c="";for(b=0;b<32*a.length;b+=8)c+=String.fromCharCode(a[b>>5]>>>b%32&255);return c}function k(a){var b,c=[];for(c[(a.length>>2)-1]=void 0,b=0;b<c.length;b+=1)c[b]=0;for(b=0;b<8*a.length;b+=8)c[b>>5]|=(255&a.charCodeAt(b/8))<<b%32;return c}function l(a){return j(i(k(a),8*a.length))}function m(a,b){var c,d,e=k(a),f=[],g=[];for(f[15]=g[15]=void 0,e.length>16&&(e=i(e,8*a.length)),c=0;16>c;c+=1)f[c]=909522486^e[c],g[c]=1549556828^e[c];return d=i(f.concat(k(b)),512+8*b.length),j(i(g.concat(d),640))}function n(a){var b,c,d="0123456789abcdef",e="";for(c=0;c<a.length;c+=1)b=a.charCodeAt(c),e+=d.charAt(b>>>4&15)+d.charAt(15&b);return e}function o(a){return unescape(encodeURIComponent(a))}function p(a){return l(o(a))}function q(a){return n(p(a))}function r(a,b){return m(o(a),o(b))}function s(a,b){return n(r(a,b))}function t(a,b,c){return b?c?r(b,a):s(b,a):c?p(a):q(a)}"function"==typeof define&&define.amd?define(function(){return t}):a.md5=t}(this);



// Easy Responsive Tabs Plugin
// Author: Samson.Onna <Email : samson3d@gmail.com>
// https://github.com/samsono/Easy-Responsive-Tabs-to-Accordion
!function(a){a.fn.extend({easyResponsiveTabs:function(t){var e={type:"default",width:"auto",fit:!0,closed:!1,activate:function(){}},t=a.extend(e,t),s=t,i=s.type,n=s.fit,r=s.width,c="vertical",o="accordion",d=window.location.hash,l=!(!window.history||!history.replaceState);a(this).bind("tabactivate",function(a,e){"function"==typeof t.activate&&t.activate.call(e,a)}),this.each(function(){function e(){i==c&&s.addClass("resp-vtabs"),1==n&&s.css({width:"100%",margin:"0px"}),i==o&&(s.addClass("resp-easy-accordion"),s.find(".resp-tabs-list").css("display","none"))}var s=a(this),p=s.find("ul.resp-tabs-list"),b=s.attr("id");s.find("ul.resp-tabs-list li").addClass("resp-tab-item"),s.css({display:"block",width:r}),s.find(".resp-tabs-container > div").addClass("resp-tab-content"),e();var v;s.find(".resp-tab-content").before("<h2 class='resp-accordion' role='tab'><span class='resp-arrow'></span></h2>");var f=0;s.find(".resp-accordion").each(function(){v=a(this);var t=s.find(".resp-tab-item:eq("+f+")"),e=s.find(".resp-accordion:eq("+f+")");e.append(t.html()),e.data(t.data()),v.attr("aria-controls","tab_item-"+f),f++});var h,u=0;s.find(".resp-tab-item").each(function(){$tabItem=a(this),$tabItem.attr("aria-controls","tab_item-"+u),$tabItem.attr("role","tab");var t=0;s.find(".resp-tab-content").each(function(){h=a(this),h.attr("aria-labelledby","tab_item-"+t),t++}),u++});var C=0;if(""!=d){var m=d.match(new RegExp(b+"([0-9]+)"));null!==m&&2===m.length&&(C=parseInt(m[1],10)-1,C>u&&(C=0))}a(s.find(".resp-tab-item")[C]).addClass("resp-tab-active"),t.closed===!0||"accordion"===t.closed&&!p.is(":visible")||"tabs"===t.closed&&p.is(":visible")?a(s.find(".resp-tab-content")[C]).addClass("resp-tab-content-active resp-accordion-closed"):(a(s.find(".resp-accordion")[C]).addClass("resp-tab-active"),a(s.find(".resp-tab-content")[C]).addClass("resp-tab-content-active").attr("style","display:block")),s.find("[role=tab]").each(function(){var t=a(this);t.click(function(){var t=a(this),e=t.attr("aria-controls");if(t.hasClass("resp-accordion")&&t.hasClass("resp-tab-active"))return s.find(".resp-tab-content-active").slideUp("",function(){a(this).addClass("resp-accordion-closed")}),t.removeClass("resp-tab-active"),!1;if(!t.hasClass("resp-tab-active")&&t.hasClass("resp-accordion")?(s.find(".resp-tab-active").removeClass("resp-tab-active"),s.find(".resp-tab-content-active").slideUp().removeClass("resp-tab-content-active resp-accordion-closed"),s.find("[aria-controls="+e+"]").addClass("resp-tab-active"),s.find(".resp-tab-content[aria-labelledby = "+e+"]").slideDown().addClass("resp-tab-content-active")):(s.find(".resp-tab-active").removeClass("resp-tab-active"),s.find(".resp-tab-content-active").removeAttr("style").removeClass("resp-tab-content-active").removeClass("resp-accordion-closed"),s.find("[aria-controls="+e+"]").addClass("resp-tab-active"),s.find(".resp-tab-content[aria-labelledby = "+e+"]").addClass("resp-tab-content-active").attr("style","display:block")),t.trigger("tabactivate",t),l){var i=window.location.hash,n=b+(parseInt(e.substring(9),10)+1).toString();if(""!=i){var r=new RegExp(b+"[0-9]+");n=null!=i.match(r)?i.replace(r,n):i+"|"+n}else n="#"+n;history.replaceState(null,null,n)}})}),a(window).resize(function(){s.find(".resp-accordion-closed").removeAttr("style")})})}})}(jQuery);


/**
*  Ajax Autocomplete for jQuery, version 1.2.21
*  (c) 2014 Tomas Kirda
*
*  Ajax Autocomplete for jQuery is freely distributable under the terms of an MIT-style license.
*  For details, see the web site: https://github.com/devbridge/jQuery-Autocomplete
*/
!function(a){"use strict";"function"==typeof define&&define.amd?define(["jquery"],a):a("object"==typeof exports&&"function"==typeof require?require("jquery"):jQuery)}(function(a){"use strict";function b(c,d){var e=function(){},f=this,g={ajaxSettings:{},autoSelectFirst:!1,appendTo:document.body,serviceUrl:null,lookup:null,onSelect:null,width:"auto",minChars:1,maxHeight:300,deferRequestBy:0,params:{},formatResult:b.formatResult,delimiter:null,zIndex:9999,type:"GET",noCache:!1,onSearchStart:e,onSearchComplete:e,onSearchError:e,preserveInput:!1,containerClass:"autocomplete-suggestions",tabDisabled:!1,dataType:"text",currentRequest:null,triggerSelectOnValidInput:!0,preventBadQueries:!0,lookupFilter:function(a,b,c){return-1!==a.value.toLowerCase().indexOf(c)},paramName:"query",transformResult:function(b){return"string"==typeof b?a.parseJSON(b):b},showNoSuggestionNotice:!1,noSuggestionNotice:"No results",orientation:"bottom",forceFixPosition:!1};f.element=c,f.el=a(c),f.suggestions=[],f.badQueries=[],f.selectedIndex=-1,f.currentValue=f.element.value,f.intervalId=0,f.cachedResponse={},f.onChangeInterval=null,f.onChange=null,f.isLocal=!1,f.suggestionsContainer=null,f.noSuggestionsContainer=null,f.options=a.extend({},g,d),f.classes={selected:"autocomplete-selected",suggestion:"autocomplete-suggestion"},f.hint=null,f.hintValue="",f.selection=null,f.initialize(),f.setOptions(d)}var c=function(){return{escapeRegExChars:function(a){return a.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g,"\\$&")},createNode:function(a){var b=document.createElement("div");return b.className=a,b.style.position="absolute",b.style.display="none",b}}}(),d={ESC:27,TAB:9,RETURN:13,LEFT:37,UP:38,RIGHT:39,DOWN:40};b.utils=c,a.Autocomplete=b,b.formatResult=function(a,b){var d=a.value.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;"),e="("+c.escapeRegExChars(b)+")";return d.replace(new RegExp(e,"gi"),"<strong>$1</strong>")},b.prototype={killerFn:null,initialize:function(){var c,d=this,e="."+d.classes.suggestion,f=d.classes.selected,g=d.options;d.element.setAttribute("autocomplete","off"),d.killerFn=function(b){0===a(b.target).closest("."+d.options.containerClass).length&&(d.killSuggestions(),d.disableKillerFn())},d.noSuggestionsContainer=a('<div class="autocomplete-no-suggestion"></div>').html(this.options.noSuggestionNotice).get(0),d.suggestionsContainer=b.utils.createNode(g.containerClass),c=a(d.suggestionsContainer),c.appendTo(g.appendTo),"auto"!==g.width&&c.width(g.width),c.on("mouseover.autocomplete",e,function(){d.activate(a(this).data("index"))}),c.on("mouseout.autocomplete",function(){d.selectedIndex=-1,c.children("."+f).removeClass(f)}),c.on("click.autocomplete",e,function(){d.select(a(this).data("index"))}),d.fixPositionCapture=function(){d.visible&&d.fixPosition()},a(window).on("resize.autocomplete",d.fixPositionCapture),d.el.on("keydown.autocomplete",function(a){d.onKeyPress(a)}),d.el.on("keyup.autocomplete",function(a){d.onKeyUp(a)}),d.el.on("blur.autocomplete",function(){d.onBlur()}),d.el.on("focus.autocomplete",function(){d.onFocus()}),d.el.on("change.autocomplete",function(a){d.onKeyUp(a)}),d.el.on("input.autocomplete",function(a){d.onKeyUp(a)})},onFocus:function(){var a=this;a.fixPosition(),0===a.options.minChars&&0===a.el.val().length&&a.onValueChange()},onBlur:function(){this.enableKillerFn()},abortAjax:function(){var a=this;a.currentRequest&&(a.currentRequest.abort(),a.currentRequest=null)},setOptions:function(b){var c=this,d=c.options;a.extend(d,b),c.isLocal=a.isArray(d.lookup),c.isLocal&&(d.lookup=c.verifySuggestionsFormat(d.lookup)),d.orientation=c.validateOrientation(d.orientation,"bottom"),a(c.suggestionsContainer).css({"max-height":d.maxHeight+"px",width:d.width+"px","z-index":d.zIndex})},clearCache:function(){this.cachedResponse={},this.badQueries=[]},clear:function(){this.clearCache(),this.currentValue="",this.suggestions=[]},disable:function(){var a=this;a.disabled=!0,clearInterval(a.onChangeInterval),a.abortAjax()},enable:function(){this.disabled=!1},fixPosition:function(){var b=this,c=a(b.suggestionsContainer),d=c.parent().get(0);if(d===document.body||b.options.forceFixPosition){var e=b.options.orientation,f=c.outerHeight(),g=b.el.outerHeight(),h=b.el.offset(),i={top:h.top,left:h.left};if("auto"===e){var j=a(window).height(),k=a(window).scrollTop(),l=-k+h.top-f,m=k+j-(h.top+g+f);e=Math.max(l,m)===l?"top":"bottom"}if(i.top+="top"===e?-f:g,d!==document.body){var n,o=c.css("opacity");b.visible||c.css("opacity",0).show(),n=c.offsetParent().offset(),i.top-=n.top,i.left-=n.left,b.visible||c.css("opacity",o).hide()}"auto"===b.options.width&&(i.width=b.el.outerWidth()-2+"px"),c.css(i)}},enableKillerFn:function(){var b=this;a(document).on("click.autocomplete",b.killerFn)},disableKillerFn:function(){var b=this;a(document).off("click.autocomplete",b.killerFn)},killSuggestions:function(){var a=this;a.stopKillSuggestions(),a.intervalId=window.setInterval(function(){a.hide(),a.stopKillSuggestions()},50)},stopKillSuggestions:function(){window.clearInterval(this.intervalId)},isCursorAtEnd:function(){var a,b=this,c=b.el.val().length,d=b.element.selectionStart;return"number"==typeof d?d===c:document.selection?(a=document.selection.createRange(),a.moveStart("character",-c),c===a.text.length):!0},onKeyPress:function(a){var b=this;if(!b.disabled&&!b.visible&&a.which===d.DOWN&&b.currentValue)return void b.suggest();if(!b.disabled&&b.visible){switch(a.which){case d.ESC:b.el.val(b.currentValue),b.hide();break;case d.RIGHT:if(b.hint&&b.options.onHint&&b.isCursorAtEnd()){b.selectHint();break}return;case d.TAB:if(b.hint&&b.options.onHint)return void b.selectHint();if(-1===b.selectedIndex)return void b.hide();if(b.select(b.selectedIndex),b.options.tabDisabled===!1)return;break;case d.RETURN:if(-1===b.selectedIndex)return void b.hide();b.select(b.selectedIndex);break;case d.UP:b.moveUp();break;case d.DOWN:b.moveDown();break;default:return}a.stopImmediatePropagation(),a.preventDefault()}},onKeyUp:function(a){var b=this;if(!b.disabled){switch(a.which){case d.UP:case d.DOWN:return}clearInterval(b.onChangeInterval),b.currentValue!==b.el.val()&&(b.findBestHint(),b.options.deferRequestBy>0?b.onChangeInterval=setInterval(function(){b.onValueChange()},b.options.deferRequestBy):b.onValueChange())}},onValueChange:function(){var b=this,c=b.options,d=b.el.val(),e=b.getQuery(d);return b.selection&&b.currentValue!==e&&(b.selection=null,(c.onInvalidateSelection||a.noop).call(b.element)),clearInterval(b.onChangeInterval),b.currentValue=d,b.selectedIndex=-1,c.triggerSelectOnValidInput&&b.isExactMatch(e)?void b.select(0):void(e.length<c.minChars?b.hide():b.getSuggestions(e))},isExactMatch:function(a){var b=this.suggestions;return 1===b.length&&b[0].value.toLowerCase()===a.toLowerCase()},getQuery:function(b){var c,d=this.options.delimiter;return d?(c=b.split(d),a.trim(c[c.length-1])):b},getSuggestionsLocal:function(b){var c,d=this,e=d.options,f=b.toLowerCase(),g=e.lookupFilter,h=parseInt(e.lookupLimit,10);return c={suggestions:a.grep(e.lookup,function(a){return g(a,b,f)})},h&&c.suggestions.length>h&&(c.suggestions=c.suggestions.slice(0,h)),c},getSuggestions:function(b){var c,d,e,f,g=this,h=g.options,i=h.serviceUrl;if(h.params[h.paramName]=b,d=h.ignoreParams?null:h.params,h.onSearchStart.call(g.element,h.params)!==!1){if(a.isFunction(h.lookup))return void h.lookup(b,function(a){g.suggestions=a.suggestions,g.suggest(),h.onSearchComplete.call(g.element,b,a.suggestions)});g.isLocal?c=g.getSuggestionsLocal(b):(a.isFunction(i)&&(i=i.call(g.element,b)),e=i+"?"+a.param(d||{}),c=g.cachedResponse[e]),c&&a.isArray(c.suggestions)?(g.suggestions=c.suggestions,g.suggest(),h.onSearchComplete.call(g.element,b,c.suggestions)):g.isBadQuery(b)?h.onSearchComplete.call(g.element,b,[]):(g.abortAjax(),f={url:i,data:d,type:h.type,dataType:h.dataType},a.extend(f,h.ajaxSettings),g.currentRequest=a.ajax(f).done(function(a){var c;g.currentRequest=null,c=h.transformResult(a,b),g.processResponse(c,b,e),h.onSearchComplete.call(g.element,b,c.suggestions)}).fail(function(a,c,d){h.onSearchError.call(g.element,b,a,c,d)}))}},isBadQuery:function(a){if(!this.options.preventBadQueries)return!1;for(var b=this.badQueries,c=b.length;c--;)if(0===a.indexOf(b[c]))return!0;return!1},hide:function(){var b=this,c=a(b.suggestionsContainer);a.isFunction(b.options.onHide)&&b.visible&&b.options.onHide.call(b.element,c),b.visible=!1,b.selectedIndex=-1,clearInterval(b.onChangeInterval),a(b.suggestionsContainer).hide(),b.signalHint(null)},suggest:function(){if(0===this.suggestions.length)return void(this.options.showNoSuggestionNotice?this.noSuggestions():this.hide());var b,c=this,d=c.options,e=d.groupBy,f=d.formatResult,g=c.getQuery(c.currentValue),h=c.classes.suggestion,i=c.classes.selected,j=a(c.suggestionsContainer),k=a(c.noSuggestionsContainer),l=d.beforeRender,m="",n=function(a){var c=a.data[e];return b===c?"":(b=c,'<div class="autocomplete-group"><strong>'+b+"</strong></div>")};return d.triggerSelectOnValidInput&&c.isExactMatch(g)?void c.select(0):(a.each(c.suggestions,function(a,b){e&&(m+=n(b,g,a)),m+='<div class="'+h+'" data-index="'+a+'">'+f(b,g)+"</div>"}),this.adjustContainerWidth(),k.detach(),j.html(m),a.isFunction(l)&&l.call(c.element,j),c.fixPosition(),j.show(),d.autoSelectFirst&&(c.selectedIndex=0,j.scrollTop(0),j.children("."+h).first().addClass(i)),c.visible=!0,void c.findBestHint())},noSuggestions:function(){var b=this,c=a(b.suggestionsContainer),d=a(b.noSuggestionsContainer);this.adjustContainerWidth(),d.detach(),c.empty(),c.append(d),b.fixPosition(),c.show(),b.visible=!0},adjustContainerWidth:function(){var b,c=this,d=c.options,e=a(c.suggestionsContainer);"auto"===d.width&&(b=c.el.outerWidth()-2,e.width(b>0?b:300))},findBestHint:function(){var b=this,c=b.el.val().toLowerCase(),d=null;c&&(a.each(b.suggestions,function(a,b){var e=0===b.value.toLowerCase().indexOf(c);return e&&(d=b),!e}),b.signalHint(d))},signalHint:function(b){var c="",d=this;b&&(c=d.currentValue+b.value.substr(d.currentValue.length)),d.hintValue!==c&&(d.hintValue=c,d.hint=b,(this.options.onHint||a.noop)(c))},verifySuggestionsFormat:function(b){return b.length&&"string"==typeof b[0]?a.map(b,function(a){return{value:a,data:null}}):b},validateOrientation:function(b,c){return b=a.trim(b||"").toLowerCase(),-1===a.inArray(b,["auto","bottom","top"])&&(b=c),b},processResponse:function(a,b,c){var d=this,e=d.options;a.suggestions=d.verifySuggestionsFormat(a.suggestions),e.noCache||(d.cachedResponse[c]=a,e.preventBadQueries&&0===a.suggestions.length&&d.badQueries.push(b)),b===d.getQuery(d.currentValue)&&(d.suggestions=a.suggestions,d.suggest())},activate:function(b){var c,d=this,e=d.classes.selected,f=a(d.suggestionsContainer),g=f.find("."+d.classes.suggestion);return f.find("."+e).removeClass(e),d.selectedIndex=b,-1!==d.selectedIndex&&g.length>d.selectedIndex?(c=g.get(d.selectedIndex),a(c).addClass(e),c):null},selectHint:function(){var b=this,c=a.inArray(b.hint,b.suggestions);b.select(c)},select:function(a){var b=this;b.hide(),b.onSelect(a)},moveUp:function(){var b=this;if(-1!==b.selectedIndex)return 0===b.selectedIndex?(a(b.suggestionsContainer).children().first().removeClass(b.classes.selected),b.selectedIndex=-1,b.el.val(b.currentValue),void b.findBestHint()):void b.adjustScroll(b.selectedIndex-1)},moveDown:function(){var a=this;a.selectedIndex!==a.suggestions.length-1&&a.adjustScroll(a.selectedIndex+1)},adjustScroll:function(b){var c=this,d=c.activate(b);if(d){var e,f,g,h=a(d).outerHeight();e=d.offsetTop,f=a(c.suggestionsContainer).scrollTop(),g=f+c.options.maxHeight-h,f>e?a(c.suggestionsContainer).scrollTop(e):e>g&&a(c.suggestionsContainer).scrollTop(e-c.options.maxHeight+h),c.options.preserveInput||c.el.val(c.getValue(c.suggestions[b].value)),c.signalHint(null)}},onSelect:function(b){var c=this,d=c.options.onSelect,e=c.suggestions[b];c.currentValue=c.getValue(e.value),c.currentValue===c.el.val()||c.options.preserveInput||c.el.val(c.currentValue),c.signalHint(null),c.suggestions=[],c.selection=e,a.isFunction(d)&&d.call(c.element,e)},getValue:function(a){var b,c,d=this,e=d.options.delimiter;return e?(b=d.currentValue,c=b.split(e),1===c.length?a:b.substr(0,b.length-c[c.length-1].length)+a):a},dispose:function(){var b=this;b.el.off(".autocomplete").removeData("autocomplete"),b.disableKillerFn(),a(window).off("resize.autocomplete",b.fixPositionCapture),a(b.suggestionsContainer).remove()}},a.fn.autocomplete=a.fn.devbridgeAutocomplete=function(c,d){var e="autocomplete";return 0===arguments.length?this.first().data(e):this.each(function(){var f=a(this),g=f.data(e);"string"==typeof c?g&&"function"==typeof g[c]&&g[c](d):(g&&g.dispose&&g.dispose(),g=new b(this,c),f.data(e,g))})}});


/*!
 * Copyright 2012, Chris Wanstrath
 * Released under the MIT License
 * https://github.com/defunkt/jquery-pjax
 */
(function(b){function J(a,d,e){var c=this;return this.on("click.pjax",a,function(a){var f=b.extend({},l(d,e));f.container||(f.container=b(this).attr("data-pjax")||c);A(a,f)})}function A(a,d,e){e=l(d,e);d=a.currentTarget;if("A"!==d.tagName.toUpperCase())throw"$.fn.pjax or $.pjax.click requires an anchor element";if(!(1<a.which||a.metaKey||a.ctrlKey||a.shiftKey||a.altKey||location.protocol!==d.protocol||location.hostname!==d.hostname||-1<d.href.indexOf("#")&&d.href.replace(/#.*/,"")==location.href.replace(/#.*/,
"")||a.isDefaultPrevented())){var c={url:d.href,container:b(d).attr("data-pjax"),target:d};e=b.extend({},c,e);c=b.Event("pjax:click");b(d).trigger(c,[e]);c.isDefaultPrevented()||(f(e),a.preventDefault(),b(d).trigger("pjax:clicked",[e]))}}function K(a,d,e){e=l(d,e);d=a.currentTarget;var c=b(d);if("FORM"!==d.tagName.toUpperCase())throw"$.pjax.submit requires a form element";c={type:(c.attr("method")||"GET").toUpperCase(),url:c.attr("action"),container:c.attr("data-pjax"),target:d};if("GET"!==c.type&&
void 0!==window.FormData)c.data=new FormData(d),c.processData=!1,c.contentType=!1;else{if(b(d).find(":file").length)return;c.data=b(d).serializeArray()}f(b.extend({},c,e));a.preventDefault()}function f(a){function d(a,d,c){c||(c={});c.relatedTarget=e;a=b.Event(a,c);h.trigger(a,d);return!a.isDefaultPrevented()}a=b.extend(!0,{},b.ajaxSettings,f.defaults,a);b.isFunction(a.url)&&(a.url=a.url());var e=a.target,c=t(a.url).hash,h=a.context=B(a.container);a.data||(a.data={});b.isArray(a.data)?a.data.push({name:"_pjax",
value:h.selector}):a.data._pjax=h.selector;var k;a.beforeSend=function(b,e){"GET"!==e.type&&(e.timeout=0);b.setRequestHeader("X-PJAX","true");b.setRequestHeader("X-PJAX-Container",h.selector);if(!d("pjax:beforeSend",[b,e]))return!1;0<e.timeout&&(k=setTimeout(function(){d("pjax:timeout",[b,a])&&b.abort("timeout")},e.timeout),e.timeout=0);var f=t(e.url);c&&(f.hash=c);a.requestUrl=C(f)};a.complete=function(b,c){k&&clearTimeout(k);d("pjax:complete",[b,c,a]);d("pjax:end",[b,a])};a.error=function(b,c,e){var f=
D("",b,a);b=d("pjax:error",[b,c,e,a]);"GET"==a.type&&"abort"!==c&&b&&u(f.url)};a.success=function(e,m,k){var n=f.state,p="function"===typeof b.pjax.defaults.version?b.pjax.defaults.version():b.pjax.defaults.version,l=k.getResponseHeader("X-PJAX-Version"),g=D(e,k,a),q=t(g.url);c&&(q.hash=c,g.url=q.href);if(p&&l&&p!==l)u(g.url);else if(g.contents){f.state={id:a.id||(new Date).getTime(),url:g.url,title:g.title,container:h.selector,fragment:a.fragment,timeout:a.timeout};(a.push||a.replace)&&window.history.replaceState(f.state,
g.title,g.url);if(b.contains(a.container,document.activeElement))try{document.activeElement.blur()}catch(r){}g.title&&(document.title=g.title);d("pjax:beforeReplace",[g.contents,a],{state:f.state,previousState:n});h.html(g.contents);(n=h.find("input[autofocus], textarea[autofocus]").last()[0])&&document.activeElement!==n&&n.focus();L(g.scripts);g=a.scrollTo;c&&(n=decodeURIComponent(c.slice(1)),n=document.getElementById(n)||document.getElementsByName(n)[0])&&(g=b(n).offset().top);"number"==typeof g&&
b(window).scrollTop(g);d("pjax:success",[e,m,k,a])}else u(g.url)};f.state||(f.state={id:(new Date).getTime(),url:window.location.href,title:document.title,container:h.selector,fragment:a.fragment,timeout:a.timeout},window.history.replaceState(f.state,document.title));E(f.xhr);f.options=a;var m=f.xhr=b.ajax(a);0<m.readyState&&(a.push&&!a.replace&&(M(f.state.id,F(h)),window.history.pushState(null,"",a.requestUrl)),d("pjax:start",[m,a]),d("pjax:send",[m,a]));return f.xhr}function N(a,d){return f(b.extend({url:window.location.href,
push:!1,replace:!0,scrollTo:!1},l(a,d)))}function u(a){window.history.replaceState(null,"",f.state.url);window.location.replace(a)}function G(a){q||E(f.xhr);var d=f.state,e=a.state,c;if(e&&e.container){if(q&&O==e.url)return;if(d){if(d.id===e.id)return;c=d.id<e.id?"forward":"back"}var h=p[e.id]||[];a=b(h[0]||e.container);h=h[1];if(a.length){if(d){var k=c,m=d.id,l=F(a);p[m]=l;"forward"===k?(k=r,l=v):(k=v,l=r);k.push(m);(m=l.pop())&&delete p[m];w(k,f.defaults.maxCacheLength)}c=b.Event("pjax:popstate",
{state:e,direction:c});a.trigger(c);c={id:e.id,url:e.url,container:a,push:!1,fragment:e.fragment,timeout:e.timeout,scrollTo:!1};h?(a.trigger("pjax:start",[null,c]),f.state=e,e.title&&(document.title=e.title),d=b.Event("pjax:beforeReplace",{state:e,previousState:d}),a.trigger(d,[h,c]),a.html(h),a.trigger("pjax:end",[null,c])):f(c);a[0].offsetHeight}else u(location.href)}q=!1}function P(a){var d=b.isFunction(a.url)?a.url():a.url,e=a.type?a.type.toUpperCase():"GET",c=b("<form>",{method:"GET"===e?"GET":
"POST",action:d,style:"display:none"});"GET"!==e&&"POST"!==e&&c.append(b("<input>",{type:"hidden",name:"_method",value:e.toLowerCase()}));a=a.data;if("string"===typeof a)b.each(a.split("&"),function(a,d){var e=d.split("=");c.append(b("<input>",{type:"hidden",name:e[0],value:e[1]}))});else if(b.isArray(a))b.each(a,function(a,d){c.append(b("<input>",{type:"hidden",name:d.name,value:d.value}))});else if("object"===typeof a)for(var f in a)c.append(b("<input>",{type:"hidden",name:f,value:a[f]}));b(document.body).append(c);
c.submit()}function E(a){a&&4>a.readyState&&(a.onreadystatechange=b.noop,a.abort())}function F(a){var b=a.clone();b.find("script").each(function(){this.src||jQuery._data(this,"globalEval",!1)});return[a.selector,b.contents()]}function C(a){a.search=a.search.replace(/([?&])(_pjax|_)=[^&]*/g,"");return a.href.replace(/\?($|#)/,"$1")}function t(a){var b=document.createElement("a");b.href=a;return b}function l(a,d){a&&d?d.container=a:d=b.isPlainObject(a)?a:{container:a};d.container&&(d.container=B(d.container));
return d}function B(a){a=b(a);if(a.length){if(""!==a.selector&&a.context===document)return a;if(a.attr("id"))return b("#"+a.attr("id"));throw"cant get selector for pjax container!";}throw"no pjax container for "+a.selector;}function x(a,b){return a.filter(b).add(a.find(b))}function y(a){return b.parseHTML(a,document,!0)}function D(a,d,e){var c={},f=/<html/i.test(a);d=d.getResponseHeader("X-PJAX-URL");c.url=d?C(t(d)):e.requestUrl;f?(d=b(y(a.match(/<head[^>]*>([\s\S.]*)<\/head>/i)[0])),a=b(y(a.match(/<body[^>]*>([\s\S.]*)<\/body>/i)[0]))):
d=a=b(y(a));if(0===a.length)return c;c.title=x(d,"title").last().text();e.fragment?(f="body"===e.fragment?a:x(a,e.fragment).first(),f.length&&(c.contents="body"===e.fragment?f:f.contents(),c.title||(c.title=f.attr("title")||f.data("title")))):f||(c.contents=a);c.contents&&(c.contents=c.contents.not(function(){return b(this).is("title")}),c.contents.find("title").remove(),c.scripts=x(c.contents,"script[src]").remove(),c.contents=c.contents.not(c.scripts));c.title&&(c.title=b.trim(c.title));return c}
function L(a){if(a){var d=b("script[src]");a.each(function(){var a=this.src;if(!d.filter(function(){return this.src===a}).length){var c=document.createElement("script"),f=b(this).attr("type");f&&(c.type=f);c.src=b(this).attr("src");document.head.appendChild(c)}})}}function M(a,b){p[a]=b;r.push(a);w(v,0);w(r,f.defaults.maxCacheLength)}function w(a,b){for(;a.length>b;)delete p[a.shift()]}function Q(){return b("meta").filter(function(){var a=b(this).attr("http-equiv");return a&&"X-PJAX-VERSION"===a.toUpperCase()}).attr("content")}
function H(){b.fn.pjax=J;b.pjax=f;b.pjax.enable=b.noop;b.pjax.disable=I;b.pjax.click=A;b.pjax.submit=K;b.pjax.reload=N;b.pjax.defaults={timeout:650,push:!0,replace:!1,type:"GET",dataType:"html",scrollTo:0,maxCacheLength:20,version:Q};b(window).on("popstate.pjax",G)}function I(){b.fn.pjax=function(){return this};b.pjax=P;b.pjax.enable=H;b.pjax.disable=b.noop;b.pjax.click=b.noop;b.pjax.submit=b.noop;b.pjax.reload=function(){window.location.reload()};b(window).off("popstate.pjax",G)}var q=!0,O=window.location.href,
z=window.history.state;z&&z.container&&(f.state=z);"state"in window.history&&(q=!1);var p={},v=[],r=[];0>b.inArray("state",b.event.props)&&b.event.props.push("state");b.support.pjax=window.history&&window.history.pushState&&window.history.replaceState&&!navigator.userAgent.match(/((iPod|iPhone|iPad).+\bOS\s+[1-4]\D|WebApps\/.+CFNetwork)/);b.support.pjax?H():I()})(jQuery);
