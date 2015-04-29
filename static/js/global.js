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


//Button go to top
$(function(){
	//Initialize position of button
	$("#go-to-top").css('left',(Math.max(document.body.clientWidth, 960) - 960)/2 + 690);
	$("#go-to-top").click(function(){
		$("html, body").animate({"scrollTop": 0}, 400);
		return false;
	});
	$(window).scroll(function() {
		var top = $(document).scrollTop();
		var g = $("#go-to-top");
		if (top > 500 && g.is(":hidden")) {
			g.fadeIn();
		} else if(top < 500 && g.is(":visible")) {
			g.fadeOut();
		}
	});
	$(window).resize(function() {
		$("#go-to-top").css('left',(Math.max(document.body.clientWidth, 960) - 960)/2 + 690);
	});
})



//异步非阻塞加载JavaScript脚本文件
function loadScript(url, callback) {
	var script = document.createElement("script");
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
	document.getElementsByTagName("head")[0].appendChild(script);
}

//管理函数的完成回调
function ManageCallback(TargetTag) {
	this.Success = function(Json) {
		if (Json.Status == 1) {
			//alert(Json.Message);
			TargetTag.innerText = Json.Message;
			//window.location.reload();
		} else {
			TargetTag.innerText = Json.ErrorMessage;
			//alert(Json.ErrorMessage);
		}
	}
}

//管理
function Manage(ID, Type, Action, NeedToConfirm, TargetTag) {
	if (!Lang) {
　　　　var Lang = {Confirm_Operation : 'Confirm_Operation'};
　　}
	if (NeedToConfirm ? confirm(Lang['Confirm_Operation']) : true) {
		TargetTag.innerText = "Loading";
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
