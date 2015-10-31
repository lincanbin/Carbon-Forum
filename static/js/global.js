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


$(function() {
	//Button go to top
	function SetButtonToTop() {
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
	}
	//Initialize position of button
	SetButtonToTop();
	$(window).scroll(function() {
		var top = $(document).scrollTop();
		var g = $("#go-to-top");
		if (top > 500 && g.is(":hidden")) {
			g.fadeIn();
		} else if (top < 500 && g.is(":visible")) {
			g.fadeOut();
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
		SetButtonToTop();
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
				if (Json.Status == 0) {
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
			if (Data.Status != 0) {
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
		},
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
						body: "",
					});
					CarbonNotification.onclick = function() {
						window.open(document.location.protocol + "//" + location.host + WebsitePath + "/notifications#notifications1");
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
			if (script.readyState == "loaded" || script.readyState == "complete") {
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
	if (document.getElementById(script.id) == undefined) {
		document.getElementsByTagName("head")[0].appendChild(script);
	} else {
		callback();
		//console.log(url);
	}
}

//管理函数的完成回调
function ManageCallback(TargetTag) {
	this.Success = function(Json) {
		if (Json.Status == 1) {
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
(function ($) {
	$.fn.extend({
		easyResponsiveTabs: function (options) {
			//Set the default values, use comma to separate the settings, example:
			var defaults = {
				type: 'default', //default, vertical, accordion;
				width: 'auto',
				fit: true,
				closed: false,
				activate: function(){}
			}
			//Variables
			var options = $.extend(defaults, options);            
			var opt = options, jtype = opt.type, jfit = opt.fit, jwidth = opt.width, vtabs = 'vertical', accord = 'accordion';
			var hash = window.location.hash;
			var historyApi = !!(window.history && history.replaceState);
			
			//Events
			$(this).bind('tabactivate', function(e, currentTab) {
				if(typeof options.activate === 'function') {
					options.activate.call(currentTab, e)
				}
			});

			//Main function
			this.each(function () {
				var $respTabs = $(this);
				var $respTabsList = $respTabs.find('ul.resp-tabs-list');
				var respTabsId = $respTabs.attr('id');
				$respTabs.find('ul.resp-tabs-list li').addClass('resp-tab-item');
				$respTabs.css({
					'display': 'block',
					'width': jwidth
				});

				$respTabs.find('.resp-tabs-container > div').addClass('resp-tab-content');
				jtab_options();
				//Properties Function
				function jtab_options() {
					if (jtype == vtabs) {
						$respTabs.addClass('resp-vtabs');
					}
					if (jfit == true) {
						$respTabs.css({ width: '100%', margin: '0px' });
					}
					if (jtype == accord) {
						$respTabs.addClass('resp-easy-accordion');
						$respTabs.find('.resp-tabs-list').css('display', 'none');
					}
				}

				//Assigning the h2 markup to accordion title
				var $tabItemh2;
				$respTabs.find('.resp-tab-content').before("<h2 class='resp-accordion' role='tab'><span class='resp-arrow'></span></h2>");

				var itemCount = 0;
				$respTabs.find('.resp-accordion').each(function () {
					$tabItemh2 = $(this);
					var $tabItem = $respTabs.find('.resp-tab-item:eq(' + itemCount + ')');
					var $accItem = $respTabs.find('.resp-accordion:eq(' + itemCount + ')');
					$accItem.append($tabItem.html());
					$accItem.data($tabItem.data());
					$tabItemh2.attr('aria-controls', 'tab_item-' + (itemCount));
					itemCount++;
				});

				//Assigning the 'aria-controls' to Tab items
				var count = 0,
					$tabContent;
				$respTabs.find('.resp-tab-item').each(function () {
					$tabItem = $(this);
					$tabItem.attr('aria-controls', 'tab_item-' + (count));
					$tabItem.attr('role', 'tab');

					//Assigning the 'aria-labelledby' attr to tab-content
					var tabcount = 0;
					$respTabs.find('.resp-tab-content').each(function () {
						$tabContent = $(this);
						$tabContent.attr('aria-labelledby', 'tab_item-' + (tabcount));
						tabcount++;
					});
					count++;
				});
				
				// Show correct content area
				var tabNum = 0;
				if(hash!='') {
					var matches = hash.match(new RegExp(respTabsId+"([0-9]+)"));
					if (matches!==null && matches.length===2) {
						tabNum = parseInt(matches[1],10)-1;
						if (tabNum > count) {
							tabNum = 0;
						}
					}
				}

				//Active correct tab
				$($respTabs.find('.resp-tab-item')[tabNum]).addClass('resp-tab-active');

				//keep closed if option = 'closed' or option is 'accordion' and the element is in accordion mode
				if(options.closed !== true && !(options.closed === 'accordion' && !$respTabsList.is(':visible')) && !(options.closed === 'tabs' && $respTabsList.is(':visible'))) {                  
					$($respTabs.find('.resp-accordion')[tabNum]).addClass('resp-tab-active');
					$($respTabs.find('.resp-tab-content')[tabNum]).addClass('resp-tab-content-active').attr('style', 'display:block');
				}
				//assign proper classes for when tabs mode is activated before making a selection in accordion mode
				else {
					$($respTabs.find('.resp-tab-content')[tabNum]).addClass('resp-tab-content-active resp-accordion-closed')
				}

				//Tab Click action function
				$respTabs.find("[role=tab]").each(function () {
				   
					var $currentTab = $(this);
					$currentTab.click(function () {
						
						var $currentTab = $(this);
						var $tabAria = $currentTab.attr('aria-controls');

						if ($currentTab.hasClass('resp-accordion') && $currentTab.hasClass('resp-tab-active')) {
							$respTabs.find('.resp-tab-content-active').slideUp('', function () { $(this).addClass('resp-accordion-closed'); });
							$currentTab.removeClass('resp-tab-active');
							return false;
						}
						if (!$currentTab.hasClass('resp-tab-active') && $currentTab.hasClass('resp-accordion')) {
							$respTabs.find('.resp-tab-active').removeClass('resp-tab-active');
							$respTabs.find('.resp-tab-content-active').slideUp().removeClass('resp-tab-content-active resp-accordion-closed');
							$respTabs.find("[aria-controls=" + $tabAria + "]").addClass('resp-tab-active');

							$respTabs.find('.resp-tab-content[aria-labelledby = ' + $tabAria + ']').slideDown().addClass('resp-tab-content-active');
						} else {
							$respTabs.find('.resp-tab-active').removeClass('resp-tab-active');
							$respTabs.find('.resp-tab-content-active').removeAttr('style').removeClass('resp-tab-content-active').removeClass('resp-accordion-closed');
							$respTabs.find("[aria-controls=" + $tabAria + "]").addClass('resp-tab-active');
							$respTabs.find('.resp-tab-content[aria-labelledby = ' + $tabAria + ']').addClass('resp-tab-content-active').attr('style', 'display:block');
						}
						//Trigger tab activation event
						$currentTab.trigger('tabactivate', $currentTab);
						
						//Update Browser History
						if(historyApi) {
							var currentHash = window.location.hash;
							var newHash = respTabsId+(parseInt($tabAria.substring(9),10)+1).toString();
							if (currentHash!="") {
								var re = new RegExp(respTabsId+"[0-9]+");
								if (currentHash.match(re)!=null) {                                    
									newHash = currentHash.replace(re,newHash);
								}
								else {
									newHash = currentHash+"|"+newHash;
								}
							}
							else {
								newHash = '#'+newHash;
							}
							
							history.replaceState(null,null,newHash);
						}
					});
					
				});
				
				//Window resize function                   
				$(window).resize(function () {
					$respTabs.find('.resp-accordion-closed').removeAttr('style');
				});
			});
		}
	});
})(jQuery);


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

(function($){

// When called on a container with a selector, fetches the href with
// ajax into the container or with the data-pjax attribute on the link
// itself.
//
// Tries to make sure the back button and ctrl+click work the way
// you'd expect.
//
// Exported as $.fn.pjax
//
// Accepts a jQuery ajax options object that may include these
// pjax specific options:
//
//
// container - Where to stick the response body. Usually a String selector.
//             $(container).html(xhr.responseBody)
//             (default: current jquery context)
//      push - Whether to pushState the URL. Defaults to true (of course).
//   replace - Want to use replaceState instead? That's cool.
//
// For convenience the second parameter can be either the container or
// the options object.
//
// Returns the jQuery object
function fnPjax(selector, container, options) {
	var context = this
	return this.on('click.pjax', selector, function(event) {
		var opts = $.extend({}, optionsFor(container, options))
		if (!opts.container)
			opts.container = $(this).attr('data-pjax') || context
		handleClick(event, opts)
	})
}

// Public: pjax on click handler
//
// Exported as $.pjax.click.
//
// event   - "click" jQuery.Event
// options - pjax options
//
// Examples
//
//   $(document).on('click', 'a', $.pjax.click)
//   // is the same as
//   $(document).pjax('a')
//
//  $(document).on('click', 'a', function(event) {
//    var container = $(this).closest('[data-pjax-container]')
//    $.pjax.click(event, container)
//  })
//
// Returns nothing.
function handleClick(event, container, options) {
	options = optionsFor(container, options)

	var link = event.currentTarget

	if (link.tagName.toUpperCase() !== 'A')
		throw "$.fn.pjax or $.pjax.click requires an anchor element"

	// Middle click, cmd click, and ctrl click should open
	// links in a new tab as normal.
	if ( event.which > 1 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey )
		return

	// Ignore cross origin links
	if ( location.protocol !== link.protocol || location.hostname !== link.hostname )
		return

	// Ignore case when a hash is being tacked on the current URL
	if ( link.href.indexOf('#') > -1 && stripHash(link) == stripHash(location) )
		return

	// Ignore event with default prevented
	if (event.isDefaultPrevented())
		return

	var defaults = {
		url: link.href,
		container: $(link).attr('data-pjax'),
		target: link
	}

	var opts = $.extend({}, defaults, options)
	var clickEvent = $.Event('pjax:click')
	$(link).trigger(clickEvent, [opts])

	if (!clickEvent.isDefaultPrevented()) {
		pjax(opts)
		event.preventDefault()
		$(link).trigger('pjax:clicked', [opts])
	}
}

// Public: pjax on form submit handler
//
// Exported as $.pjax.submit
//
// event   - "click" jQuery.Event
// options - pjax options
//
// Examples
//
//  $(document).on('submit', 'form', function(event) {
//    var container = $(this).closest('[data-pjax-container]')
//    $.pjax.submit(event, container)
//  })
//
// Returns nothing.
function handleSubmit(event, container, options) {
	options = optionsFor(container, options)

	var form = event.currentTarget

	if (form.tagName.toUpperCase() !== 'FORM')
		throw "$.pjax.submit requires a form element"

	var defaults = {
		type: form.method.toUpperCase(),
		url: form.action,
		container: $(form).attr('data-pjax'),
		target: form
	}

	if (defaults.type !== 'GET' && window.FormData !== undefined) {
		defaults.data = new FormData(form);
		defaults.processData = false;
		defaults.contentType = false;
	} else {
		// Can't handle file uploads, exit
		if ($(form).find(':file').length) {
			return;
		}

		// Fallback to manually serializing the fields
		defaults.data = $(form).serializeArray();
	}

	pjax($.extend({}, defaults, options))

	event.preventDefault()
}

// Loads a URL with ajax, puts the response body inside a container,
// then pushState()'s the loaded URL.
//
// Works just like $.ajax in that it accepts a jQuery ajax
// settings object (with keys like url, type, data, etc).
//
// Accepts these extra keys:
//
// container - Where to stick the response body.
//             $(container).html(xhr.responseBody)
//      push - Whether to pushState the URL. Defaults to true (of course).
//   replace - Want to use replaceState instead? That's cool.
//
// Use it just like $.ajax:
//
//   var xhr = $.pjax({ url: this.href, container: '#main' })
//   console.log( xhr.readyState )
//
// Returns whatever $.ajax returns.
function pjax(options) {
	options = $.extend(true, {}, $.ajaxSettings, pjax.defaults, options)

	if ($.isFunction(options.url)) {
		options.url = options.url()
	}

	var target = options.target

	var hash = parseURL(options.url).hash

	var context = options.context = findContainerFor(options.container)

	// We want the browser to maintain two separate internal caches: one
	// for pjax'd partial page loads and one for normal page loads.
	// Without adding this secret parameter, some browsers will often
	// confuse the two.
	if (!options.data) options.data = {}
	if ($.isArray(options.data)) {
		options.data.push({name: '_pjax', value: context.selector})
	} else {
		options.data._pjax = context.selector
	}

	function fire(type, args, props) {
		if (!props) props = {}
		props.relatedTarget = target
		var event = $.Event(type, props)
		context.trigger(event, args)
		return !event.isDefaultPrevented()
	}

	var timeoutTimer

	options.beforeSend = function(xhr, settings) {
		// No timeout for non-GET requests
		// Its not safe to request the resource again with a fallback method.
		if (settings.type !== 'GET') {
			settings.timeout = 0
		}

		xhr.setRequestHeader('X-PJAX', 'true')
		xhr.setRequestHeader('X-PJAX-Container', context.selector)

		if (!fire('pjax:beforeSend', [xhr, settings]))
			return false

		if (settings.timeout > 0) {
			timeoutTimer = setTimeout(function() {
				if (fire('pjax:timeout', [xhr, options]))
					xhr.abort('timeout')
			}, settings.timeout)

			// Clear timeout setting so jquerys internal timeout isn't invoked
			settings.timeout = 0
		}

		var url = parseURL(settings.url)
		if (hash) url.hash = hash
		options.requestUrl = stripInternalParams(url)
	}

	options.complete = function(xhr, textStatus) {
		if (timeoutTimer)
			clearTimeout(timeoutTimer)

		fire('pjax:complete', [xhr, textStatus, options])

		fire('pjax:end', [xhr, options])
	}

	options.error = function(xhr, textStatus, errorThrown) {
		var container = extractContainer("", xhr, options)

		var allowed = fire('pjax:error', [xhr, textStatus, errorThrown, options])
		if (options.type == 'GET' && textStatus !== 'abort' && allowed) {
			locationReplace(container.url)
		}
	}

	options.success = function(data, status, xhr) {
		var previousState = pjax.state;

		// If $.pjax.defaults.version is a function, invoke it first.
		// Otherwise it can be a static string.
		var currentVersion = (typeof $.pjax.defaults.version === 'function') ?
			$.pjax.defaults.version() :
			$.pjax.defaults.version

		var latestVersion = xhr.getResponseHeader('X-PJAX-Version')

		var container = extractContainer(data, xhr, options)

		var url = parseURL(container.url)
		if (hash) {
			url.hash = hash
			container.url = url.href
		}

		// If there is a layout version mismatch, hard load the new url
		if (currentVersion && latestVersion && currentVersion !== latestVersion) {
			locationReplace(container.url)
			return
		}

		// If the new response is missing a body, hard load the page
		if (!container.contents) {
			locationReplace(container.url)
			return
		}

		pjax.state = {
			id: options.id || uniqueId(),
			url: container.url,
			title: container.title,
			container: context.selector,
			fragment: options.fragment,
			timeout: options.timeout
		}

		if (options.push || options.replace) {
			window.history.replaceState(pjax.state, container.title, container.url)
		}

		// Clear out any focused controls before inserting new page contents.
		try {
			document.activeElement.blur()
		} catch (e) { }

		if (container.title) document.title = container.title

		fire('pjax:beforeReplace', [container.contents, options], {
			state: pjax.state,
			previousState: previousState
		})
		context.html(container.contents)

		// FF bug: Won't autofocus fields that are inserted via JS.
		// This behavior is incorrect. So if theres no current focus, autofocus
		// the last field.
		//
		// http://www.w3.org/html/wg/drafts/html/master/forms.html
		var autofocusEl = context.find('input[autofocus], textarea[autofocus]').last()[0]
		if (autofocusEl && document.activeElement !== autofocusEl) {
			autofocusEl.focus();
		}

		executeScriptTags(container.scripts)

		var scrollTo = options.scrollTo

		// Ensure browser scrolls to the element referenced by the URL anchor
		if (hash) {
			var name = decodeURIComponent(hash.slice(1))
			var target = document.getElementById(name) || document.getElementsByName(name)[0]
			if (target) scrollTo = $(target).offset().top
		}

		if (typeof scrollTo == 'number') $(window).scrollTop(scrollTo)

		fire('pjax:success', [data, status, xhr, options])
	}


	// Initialize pjax.state for the initial page load. Assume we're
	// using the container and options of the link we're loading for the
	// back button to the initial page. This ensures good back button
	// behavior.
	if (!pjax.state) {
		pjax.state = {
			id: uniqueId(),
			url: window.location.href,
			title: document.title,
			container: context.selector,
			fragment: options.fragment,
			timeout: options.timeout
		}
		window.history.replaceState(pjax.state, document.title)
	}

	// Cancel the current request if we're already pjaxing
	abortXHR(pjax.xhr)

	pjax.options = options
	var xhr = pjax.xhr = $.ajax(options)

	if (xhr.readyState > 0) {
		if (options.push && !options.replace) {
			// Cache current container element before replacing it
			cachePush(pjax.state.id, cloneContents(context))

			window.history.pushState(null, "", options.requestUrl)
		}

		fire('pjax:start', [xhr, options])
		fire('pjax:send', [xhr, options])
	}

	return pjax.xhr
}

// Public: Reload current page with pjax.
//
// Returns whatever $.pjax returns.
function pjaxReload(container, options) {
	var defaults = {
		url: window.location.href,
		push: false,
		replace: true,
		scrollTo: false
	}

	return pjax($.extend(defaults, optionsFor(container, options)))
}

// Internal: Hard replace current state with url.
//
// Work for around WebKit
//   https://bugs.webkit.org/show_bug.cgi?id=93506
//
// Returns nothing.
function locationReplace(url) {
	window.history.replaceState(null, "", pjax.state.url)
	window.location.replace(url)
}


var initialPop = true
var initialURL = window.location.href
var initialState = window.history.state

// Initialize $.pjax.state if possible
// Happens when reloading a page and coming forward from a different
// session history.
if (initialState && initialState.container) {
	pjax.state = initialState
}

// Non-webkit browsers don't fire an initial popstate event
if ('state' in window.history) {
	initialPop = false
}

// popstate handler takes care of the back and forward buttons
//
// You probably shouldn't use pjax on pages with other pushState
// stuff yet.
function onPjaxPopstate(event) {

	// Hitting back or forward should override any pending PJAX request.
	if (!initialPop) {
		abortXHR(pjax.xhr)
	}

	var previousState = pjax.state
	var state = event.state
	var direction

	if (state && state.container) {
		// When coming forward from a separate history session, will get an
		// initial pop with a state we are already at. Skip reloading the current
		// page.
		if (initialPop && initialURL == state.url) return

		if (previousState) {
			// If popping back to the same state, just skip.
			// Could be clicking back from hashchange rather than a pushState.
			if (previousState.id === state.id) return

			// Since state IDs always increase, we can deduce the navigation direction
			direction = previousState.id < state.id ? 'forward' : 'back'
		}

		var cache = cacheMapping[state.id] || []
		var container = $(cache[0] || state.container), contents = cache[1]

		if (container.length) {
			if (previousState) {
				// Cache current container before replacement and inform the
				// cache which direction the history shifted.
				cachePop(direction, previousState.id, cloneContents(container))
			}

			var popstateEvent = $.Event('pjax:popstate', {
				state: state,
				direction: direction
			})
			container.trigger(popstateEvent)

			var options = {
				id: state.id,
				url: state.url,
				container: container,
				push: false,
				fragment: state.fragment,
				timeout: state.timeout,
				scrollTo: false
			}

			if (contents) {
				container.trigger('pjax:start', [null, options])

				pjax.state = state
				if (state.title) document.title = state.title
				var beforeReplaceEvent = $.Event('pjax:beforeReplace', {
					state: state,
					previousState: previousState
				})
				container.trigger(beforeReplaceEvent, [contents, options])
				container.html(contents)

				container.trigger('pjax:end', [null, options])
			} else {
				pjax(options)
			}

			// Force reflow/relayout before the browser tries to restore the
			// scroll position.
			container[0].offsetHeight
		} else {
			locationReplace(location.href)
		}
	}
	initialPop = false
}

// Fallback version of main pjax function for browsers that don't
// support pushState.
//
// Returns nothing since it retriggers a hard form submission.
function fallbackPjax(options) {
	var url = $.isFunction(options.url) ? options.url() : options.url,
			method = options.type ? options.type.toUpperCase() : 'GET'

	var form = $('<form>', {
		method: method === 'GET' ? 'GET' : 'POST',
		action: url,
		style: 'display:none'
	})

	if (method !== 'GET' && method !== 'POST') {
		form.append($('<input>', {
			type: 'hidden',
			name: '_method',
			value: method.toLowerCase()
		}))
	}

	var data = options.data
	if (typeof data === 'string') {
		$.each(data.split('&'), function(index, value) {
			var pair = value.split('=')
			form.append($('<input>', {type: 'hidden', name: pair[0], value: pair[1]}))
		})
	} else if ($.isArray(data)) {
		$.each(data, function(index, value) {
			form.append($('<input>', {type: 'hidden', name: value.name, value: value.value}))
		})
	} else if (typeof data === 'object') {
		var key
		for (key in data)
			form.append($('<input>', {type: 'hidden', name: key, value: data[key]}))
	}

	$(document.body).append(form)
	form.submit()
}

// Internal: Abort an XmlHttpRequest if it hasn't been completed,
// also removing its event handlers.
function abortXHR(xhr) {
	if ( xhr && xhr.readyState < 4) {
		xhr.onreadystatechange = $.noop
		xhr.abort()
	}
}

// Internal: Generate unique id for state object.
//
// Use a timestamp instead of a counter since ids should still be
// unique across page loads.
//
// Returns Number.
function uniqueId() {
	return (new Date).getTime()
}

function cloneContents(container) {
	var cloned = container.clone()
	// Unmark script tags as already being eval'd so they can get executed again
	// when restored from cache. HAXX: Uses jQuery internal method.
	cloned.find('script').each(function(){
		if (!this.src) jQuery._data(this, 'globalEval', false)
	})
	return [container.selector, cloned.contents()]
}

// Internal: Strip internal query params from parsed URL.
//
// Returns sanitized url.href String.
function stripInternalParams(url) {
	url.search = url.search.replace(/([?&])(_pjax|_)=[^&]*/g, '')
	return url.href.replace(/\?($|#)/, '$1')
}

// Internal: Parse URL components and returns a Locationish object.
//
// url - String URL
//
// Returns HTMLAnchorElement that acts like Location.
function parseURL(url) {
	var a = document.createElement('a')
	a.href = url
	return a
}

// Internal: Return the `href` component of given URL object with the hash
// portion removed.
//
// location - Location or HTMLAnchorElement
//
// Returns String
function stripHash(location) {
	return location.href.replace(/#.*/, '')
}

// Internal: Build options Object for arguments.
//
// For convenience the first parameter can be either the container or
// the options object.
//
// Examples
//
//   optionsFor('#container')
//   // => {container: '#container'}
//
//   optionsFor('#container', {push: true})
//   // => {container: '#container', push: true}
//
//   optionsFor({container: '#container', push: true})
//   // => {container: '#container', push: true}
//
// Returns options Object.
function optionsFor(container, options) {
	// Both container and options
	if ( container && options )
		options.container = container

	// First argument is options Object
	else if ( $.isPlainObject(container) )
		options = container

	// Only container
	else
		options = {container: container}

	// Find and validate container
	if (options.container)
		options.container = findContainerFor(options.container)

	return options
}

// Internal: Find container element for a variety of inputs.
//
// Because we can't persist elements using the history API, we must be
// able to find a String selector that will consistently find the Element.
//
// container - A selector String, jQuery object, or DOM Element.
//
// Returns a jQuery object whose context is `document` and has a selector.
function findContainerFor(container) {
	container = $(container)

	if ( !container.length ) {
		throw "no pjax container for " + container.selector
	} else if ( container.selector !== '' && container.context === document ) {
		return container
	} else if ( container.attr('id') ) {
		return $('#' + container.attr('id'))
	} else {
		throw "cant get selector for pjax container!"
	}
}

// Internal: Filter and find all elements matching the selector.
//
// Where $.fn.find only matches descendants, findAll will test all the
// top level elements in the jQuery object as well.
//
// elems    - jQuery object of Elements
// selector - String selector to match
//
// Returns a jQuery object.
function findAll(elems, selector) {
	return elems.filter(selector).add(elems.find(selector));
}

function parseHTML(html) {
	return $.parseHTML(html, document, true)
}

// Internal: Extracts container and metadata from response.
//
// 1. Extracts X-PJAX-URL header if set
// 2. Extracts inline <title> tags
// 3. Builds response Element and extracts fragment if set
//
// data    - String response data
// xhr     - XHR response
// options - pjax options Object
//
// Returns an Object with url, title, and contents keys.
function extractContainer(data, xhr, options) {
	var obj = {}, fullDocument = /<html/i.test(data)

	// Prefer X-PJAX-URL header if it was set, otherwise fallback to
	// using the original requested url.
	var serverUrl = xhr.getResponseHeader('X-PJAX-URL')
	obj.url = serverUrl ? stripInternalParams(parseURL(serverUrl)) : options.requestUrl

	// Attempt to parse response html into elements
	if (fullDocument) {
		var $head = $(parseHTML(data.match(/<head[^>]*>([\s\S.]*)<\/head>/i)[0]))
		var $body = $(parseHTML(data.match(/<body[^>]*>([\s\S.]*)<\/body>/i)[0]))
	} else {
		var $head = $body = $(parseHTML(data))
	}

	// If response data is empty, return fast
	if ($body.length === 0)
		return obj

	// If there's a <title> tag in the header, use it as
	// the page's title.
	obj.title = findAll($head, 'title').last().text()

	if (options.fragment) {
		// If they specified a fragment, look for it in the response
		// and pull it out.
		if (options.fragment === 'body') {
			var $fragment = $body
		} else {
			var $fragment = findAll($body, options.fragment).first()
		}

		if ($fragment.length) {
			obj.contents = options.fragment === 'body' ? $fragment : $fragment.contents()

			// If there's no title, look for data-title and title attributes
			// on the fragment
			if (!obj.title)
				obj.title = $fragment.attr('title') || $fragment.data('title')
		}

	} else if (!fullDocument) {
		obj.contents = $body
	}

	// Clean up any <title> tags
	if (obj.contents) {
		// Remove any parent title elements
		obj.contents = obj.contents.not(function() { return $(this).is('title') })

		// Then scrub any titles from their descendants
		obj.contents.find('title').remove()

		// Gather all script[src] elements
		obj.scripts = findAll(obj.contents, 'script[src]').remove()
		obj.contents = obj.contents.not(obj.scripts)
	}

	// Trim any whitespace off the title
	if (obj.title) obj.title = $.trim(obj.title)

	return obj
}

// Load an execute scripts using standard script request.
//
// Avoids jQuery's traditional $.getScript which does a XHR request and
// globalEval.
//
// scripts - jQuery object of script Elements
//
// Returns nothing.
function executeScriptTags(scripts) {
	if (!scripts) return

	var existingScripts = $('script[src]')

	scripts.each(function() {
		var src = this.src
		var matchedScripts = existingScripts.filter(function() {
			return this.src === src
		})
		if (matchedScripts.length) return

		var script = document.createElement('script')
		var type = $(this).attr('type')
		if (type) script.type = type
		script.src = $(this).attr('src')
		document.head.appendChild(script)
	})
}

// Internal: History DOM caching class.
var cacheMapping      = {}
var cacheForwardStack = []
var cacheBackStack    = []

// Push previous state id and container contents into the history
// cache. Should be called in conjunction with `pushState` to save the
// previous container contents.
//
// id    - State ID Number
// value - DOM Element to cache
//
// Returns nothing.
function cachePush(id, value) {
	cacheMapping[id] = value
	cacheBackStack.push(id)

	// Remove all entries in forward history stack after pushing a new page.
	trimCacheStack(cacheForwardStack, 0)

	// Trim back history stack to max cache length.
	trimCacheStack(cacheBackStack, pjax.defaults.maxCacheLength)
}

// Shifts cache from directional history cache. Should be
// called on `popstate` with the previous state id and container
// contents.
//
// direction - "forward" or "back" String
// id        - State ID Number
// value     - DOM Element to cache
//
// Returns nothing.
function cachePop(direction, id, value) {
	var pushStack, popStack
	cacheMapping[id] = value

	if (direction === 'forward') {
		pushStack = cacheBackStack
		popStack  = cacheForwardStack
	} else {
		pushStack = cacheForwardStack
		popStack  = cacheBackStack
	}

	pushStack.push(id)
	if (id = popStack.pop())
		delete cacheMapping[id]

	// Trim whichever stack we just pushed to to max cache length.
	trimCacheStack(pushStack, pjax.defaults.maxCacheLength)
}

// Trim a cache stack (either cacheBackStack or cacheForwardStack) to be no
// longer than the specified length, deleting cached DOM elements as necessary.
//
// stack  - Array of state IDs
// length - Maximum length to trim to
//
// Returns nothing.
function trimCacheStack(stack, length) {
	while (stack.length > length)
		delete cacheMapping[stack.shift()]
}

// Public: Find version identifier for the initial page load.
//
// Returns String version or undefined.
function findVersion() {
	return $('meta').filter(function() {
		var name = $(this).attr('http-equiv')
		return name && name.toUpperCase() === 'X-PJAX-VERSION'
	}).attr('content')
}

// Install pjax functions on $.pjax to enable pushState behavior.
//
// Does nothing if already enabled.
//
// Examples
//
//     $.pjax.enable()
//
// Returns nothing.
function enable() {
	$.fn.pjax = fnPjax
	$.pjax = pjax
	$.pjax.enable = $.noop
	$.pjax.disable = disable
	$.pjax.click = handleClick
	$.pjax.submit = handleSubmit
	$.pjax.reload = pjaxReload
	$.pjax.defaults = {
		timeout: 650,
		push: true,
		replace: false,
		type: 'GET',
		dataType: 'html',
		scrollTo: 0,
		maxCacheLength: 20,
		version: findVersion
	}
	$(window).on('popstate.pjax', onPjaxPopstate)
}

// Disable pushState behavior.
//
// This is the case when a browser doesn't support pushState. It is
// sometimes useful to disable pushState for debugging on a modern
// browser.
//
// Examples
//
//     $.pjax.disable()
//
// Returns nothing.
function disable() {
	$.fn.pjax = function() { return this }
	$.pjax = fallbackPjax
	$.pjax.enable = enable
	$.pjax.disable = $.noop
	$.pjax.click = $.noop
	$.pjax.submit = $.noop
	$.pjax.reload = function() { window.location.reload() }

	$(window).off('popstate.pjax', onPjaxPopstate)
}


// Add the state property to jQuery's event object so we can use it in
// $(window).bind('popstate')
if ( $.inArray('state', $.event.props) < 0 )
	$.event.props.push('state')

// Is pjax supported by this browser?
$.support.pjax =
	window.history && window.history.pushState && window.history.replaceState &&
	// pushState isn't reliable on iOS until 5.
	!navigator.userAgent.match(/((iPod|iPhone|iPad).+\bOS\s+[1-4]\D|WebApps\/.+CFNetwork)/)

$.support.pjax ? enable() : disable()

})(jQuery);
