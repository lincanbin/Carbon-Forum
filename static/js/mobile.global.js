/*
 * Carbon-Forum
 * https://github.com/lincanbin/Carbon-Forum
 *
 * Copyright 2006-2016 Canbin Lin (lincanbin@hotmail.com)
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A high performance open-source forum software written in PHP. 
 */


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


//https://github.com/ftlabs/fastclick
(function(){function FastClick(layer,options){var oldOnClick;options=options||{};this.trackingClick=false;this.trackingClickStart=0;this.targetElement=null;this.touchStartX=0;this.touchStartY=0;this.lastTouchIdentifier=0;this.touchBoundary=options.touchBoundary||10;this.layer=layer;this.tapDelay=options.tapDelay||200;this.tapTimeout=options.tapTimeout||700;if(FastClick.notNeeded(layer)){return}function bind(method,context){return function(){return method.apply(context,arguments)}}var methods=["onMouse","onClick","onTouchStart","onTouchMove","onTouchEnd","onTouchCancel"];var context=this;for(var i=0,l=methods.length;i<l;i++){context[methods[i]]=bind(context[methods[i]],context)}if(deviceIsAndroid){layer.addEventListener("mouseover",this.onMouse,true);layer.addEventListener("mousedown",this.onMouse,true);layer.addEventListener("mouseup",this.onMouse,true)}layer.addEventListener("click",this.onClick,true);layer.addEventListener("touchstart",this.onTouchStart,false);layer.addEventListener("touchmove",this.onTouchMove,false);layer.addEventListener("touchend",this.onTouchEnd,false);layer.addEventListener("touchcancel",this.onTouchCancel,false);if(!Event.prototype.stopImmediatePropagation){layer.removeEventListener=function(type,callback,capture){var rmv=Node.prototype.removeEventListener;if(type==="click"){rmv.call(layer,type,callback.hijacked||callback,capture)}else{rmv.call(layer,type,callback,capture)}};layer.addEventListener=function(type,callback,capture){var adv=Node.prototype.addEventListener;if(type==="click"){adv.call(layer,type,callback.hijacked||(callback.hijacked=function(event){if(!event.propagationStopped){callback(event)}}),capture)}else{adv.call(layer,type,callback,capture)}}}if(typeof layer.onclick==="function"){oldOnClick=layer.onclick;layer.addEventListener("click",function(event){oldOnClick(event)},false);layer.onclick=null}}var deviceIsWindowsPhone=navigator.userAgent.indexOf("Windows Phone")>=0;var deviceIsAndroid=navigator.userAgent.indexOf("Android")>0&&!deviceIsWindowsPhone;var deviceIsIOS=/iP(ad|hone|od)/.test(navigator.userAgent)&&!deviceIsWindowsPhone;var deviceIsIOS4=deviceIsIOS&&(/OS 4_\d(_\d)?/).test(navigator.userAgent);var deviceIsIOSWithBadTarget=deviceIsIOS&&(/OS [6-7]_\d/).test(navigator.userAgent);var deviceIsBlackBerry10=navigator.userAgent.indexOf("BB10")>0;FastClick.prototype.needsClick=function(target){switch(target.nodeName.toLowerCase()){case"button":case"select":case"textarea":if(target.disabled){return true}break;case"input":if((deviceIsIOS&&target.type==="file")||target.disabled){return true}break;case"label":case"iframe":case"video":return true}return(/\bneedsclick\b/).test(target.className)};FastClick.prototype.needsFocus=function(target){switch(target.nodeName.toLowerCase()){case"textarea":return true;case"select":return !deviceIsAndroid;case"input":switch(target.type){case"button":case"checkbox":case"file":case"image":case"radio":case"submit":return false}return !target.disabled&&!target.readOnly;default:return(/\bneedsfocus\b/).test(target.className)}};FastClick.prototype.sendClick=function(targetElement,event){var clickEvent,touch;if(document.activeElement&&document.activeElement!==targetElement){document.activeElement.blur()}touch=event.changedTouches[0];clickEvent=document.createEvent("MouseEvents");clickEvent.initMouseEvent(this.determineEventType(targetElement),true,true,window,1,touch.screenX,touch.screenY,touch.clientX,touch.clientY,false,false,false,false,0,null);clickEvent.forwardedTouchEvent=true;targetElement.dispatchEvent(clickEvent)};FastClick.prototype.determineEventType=function(targetElement){if(deviceIsAndroid&&targetElement.tagName.toLowerCase()==="select"){return"mousedown"}return"click"};FastClick.prototype.focus=function(targetElement){var length;if(deviceIsIOS&&targetElement.setSelectionRange&&targetElement.type.indexOf("date")!==0&&targetElement.type!=="time"&&targetElement.type!=="month"){length=targetElement.value.length;targetElement.setSelectionRange(length,length)}else{targetElement.focus()}};FastClick.prototype.updateScrollParent=function(targetElement){var scrollParent,parentElement;scrollParent=targetElement.fastClickScrollParent;if(!scrollParent||!scrollParent.contains(targetElement)){parentElement=targetElement;do{if(parentElement.scrollHeight>parentElement.offsetHeight){scrollParent=parentElement;targetElement.fastClickScrollParent=parentElement;break}parentElement=parentElement.parentElement}while(parentElement)}if(scrollParent){scrollParent.fastClickLastScrollTop=scrollParent.scrollTop}};FastClick.prototype.getTargetElementFromEventTarget=function(eventTarget){if(eventTarget.nodeType===Node.TEXT_NODE){return eventTarget.parentNode}return eventTarget};FastClick.prototype.onTouchStart=function(event){var targetElement,touch,selection;if(event.targetTouches.length>1){return true}targetElement=this.getTargetElementFromEventTarget(event.target);touch=event.targetTouches[0];if(deviceIsIOS){selection=window.getSelection();if(selection.rangeCount&&!selection.isCollapsed){return true
}if(!deviceIsIOS4){if(touch.identifier&&touch.identifier===this.lastTouchIdentifier){event.preventDefault();return false}this.lastTouchIdentifier=touch.identifier;this.updateScrollParent(targetElement)}}this.trackingClick=true;this.trackingClickStart=event.timeStamp;this.targetElement=targetElement;this.touchStartX=touch.pageX;this.touchStartY=touch.pageY;if((event.timeStamp-this.lastClickTime)<this.tapDelay){event.preventDefault()}return true};FastClick.prototype.touchHasMoved=function(event){var touch=event.changedTouches[0],boundary=this.touchBoundary;if(Math.abs(touch.pageX-this.touchStartX)>boundary||Math.abs(touch.pageY-this.touchStartY)>boundary){return true}return false};FastClick.prototype.onTouchMove=function(event){if(!this.trackingClick){return true}if(this.targetElement!==this.getTargetElementFromEventTarget(event.target)||this.touchHasMoved(event)){this.trackingClick=false;this.targetElement=null}return true};FastClick.prototype.findControl=function(labelElement){if(labelElement.control!==undefined){return labelElement.control}if(labelElement.htmlFor){return document.getElementById(labelElement.htmlFor)}return labelElement.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea")};FastClick.prototype.onTouchEnd=function(event){var forElement,trackingClickStart,targetTagName,scrollParent,touch,targetElement=this.targetElement;if(!this.trackingClick){return true}if((event.timeStamp-this.lastClickTime)<this.tapDelay){this.cancelNextClick=true;return true}if((event.timeStamp-this.trackingClickStart)>this.tapTimeout){return true}this.cancelNextClick=false;this.lastClickTime=event.timeStamp;trackingClickStart=this.trackingClickStart;this.trackingClick=false;this.trackingClickStart=0;if(deviceIsIOSWithBadTarget){touch=event.changedTouches[0];targetElement=document.elementFromPoint(touch.pageX-window.pageXOffset,touch.pageY-window.pageYOffset)||targetElement;targetElement.fastClickScrollParent=this.targetElement.fastClickScrollParent}targetTagName=targetElement.tagName.toLowerCase();if(targetTagName==="label"){forElement=this.findControl(targetElement);if(forElement){this.focus(targetElement);if(deviceIsAndroid){return false}targetElement=forElement}}else{if(this.needsFocus(targetElement)){if((event.timeStamp-trackingClickStart)>100||(deviceIsIOS&&window.top!==window&&targetTagName==="input")){this.targetElement=null;return false}this.focus(targetElement);this.sendClick(targetElement,event);if(!deviceIsIOS||targetTagName!=="select"){this.targetElement=null;event.preventDefault()}return false}}if(deviceIsIOS&&!deviceIsIOS4){scrollParent=targetElement.fastClickScrollParent;if(scrollParent&&scrollParent.fastClickLastScrollTop!==scrollParent.scrollTop){return true}}if(!this.needsClick(targetElement)){event.preventDefault();this.sendClick(targetElement,event)}return false};FastClick.prototype.onTouchCancel=function(){this.trackingClick=false;this.targetElement=null};FastClick.prototype.onMouse=function(event){if(!this.targetElement){return true}if(event.forwardedTouchEvent){return true}if(!event.cancelable){return true}if(!this.needsClick(this.targetElement)||this.cancelNextClick){if(event.stopImmediatePropagation){event.stopImmediatePropagation()}else{event.propagationStopped=true}event.stopPropagation();event.preventDefault();return false}return true};FastClick.prototype.onClick=function(event){var permitted;if(this.trackingClick){this.targetElement=null;this.trackingClick=false;return true}if(event.target.type==="submit"&&event.detail===0){return true}permitted=this.onMouse(event);if(!permitted){this.targetElement=null}return permitted};FastClick.prototype.destroy=function(){var layer=this.layer;if(deviceIsAndroid){layer.removeEventListener("mouseover",this.onMouse,true);layer.removeEventListener("mousedown",this.onMouse,true);layer.removeEventListener("mouseup",this.onMouse,true)}layer.removeEventListener("click",this.onClick,true);layer.removeEventListener("touchstart",this.onTouchStart,false);layer.removeEventListener("touchmove",this.onTouchMove,false);layer.removeEventListener("touchend",this.onTouchEnd,false);layer.removeEventListener("touchcancel",this.onTouchCancel,false)};FastClick.notNeeded=function(layer){var metaViewport;var chromeVersion;var blackberryVersion;var firefoxVersion;if(typeof window.ontouchstart==="undefined"){return true}chromeVersion=+(/Chrome\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1];if(chromeVersion){if(deviceIsAndroid){metaViewport=document.querySelector("meta[name=viewport]");if(metaViewport){if(metaViewport.content.indexOf("user-scalable=no")!==-1){return true}if(chromeVersion>31&&document.documentElement.scrollWidth<=window.outerWidth){return true}}}else{return true}}if(deviceIsBlackBerry10){blackberryVersion=navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/);if(blackberryVersion[1]>=10&&blackberryVersion[2]>=3){metaViewport=document.querySelector("meta[name=viewport]");if(metaViewport){if(metaViewport.content.indexOf("user-scalable=no")!==-1){return true
}if(document.documentElement.scrollWidth<=window.outerWidth){return true}}}}if(layer.style.msTouchAction==="none"||layer.style.touchAction==="manipulation"){return true}firefoxVersion=+(/Firefox\/([0-9]+)/.exec(navigator.userAgent)||[,0])[1];if(firefoxVersion>=27){metaViewport=document.querySelector("meta[name=viewport]");if(metaViewport&&(metaViewport.content.indexOf("user-scalable=no")!==-1||document.documentElement.scrollWidth<=window.outerWidth)){return true}}if(layer.style.touchAction==="none"||layer.style.touchAction==="manipulation"){return true}return false};FastClick.attach=function(layer,options){return new FastClick(layer,options)};if(typeof define==="function"&&typeof define.amd==="object"&&define.amd){define(function(){return FastClick})}else{if(typeof module!=="undefined"&&module.exports){module.exports=FastClick.attach;module.exports.FastClick=FastClick}else{window.FastClick=FastClick}}}());


// https://github.com/Mango/slideout
// v0.1.11
!function(e){if("object"==typeof exports&&"undefined"!=typeof module)module.exports=e();else if("function"==typeof define&&define.amd)define([],e);else{var t;"undefined"!=typeof window?t=window:"undefined"!=typeof global?t=global:"undefined"!=typeof self&&(t=self),t.Slideout=e()}}(function(){var e,t,n;return function i(e,t,n){function o(r,a){if(!t[r]){if(!e[r]){var u=typeof require=="function"&&require;if(!a&&u)return u(r,!0);if(s)return s(r,!0);var l=new Error("Cannot find module '"+r+"'");throw l.code="MODULE_NOT_FOUND",l}var f=t[r]={exports:{}};e[r][0].call(f.exports,function(t){var n=e[r][1][t];return o(n?n:t)},f,f.exports,i,e,t,n)}return t[r].exports}var s=typeof require=="function"&&require;for(var r=0;r<n.length;r++)o(n[r]);return o}({1:[function(e,t,n){"use strict";var i=e("decouple");var o=e("emitter");var s;var r=false;var a=window.document;var u=a.documentElement;var l=window.navigator.msPointerEnabled;var f={start:l?"MSPointerDown":"touchstart",move:l?"MSPointerMove":"touchmove",end:l?"MSPointerUp":"touchend"};var c=function v(){var e=/^(Webkit|Khtml|Moz|ms|O)(?=[A-Z])/;var t=a.getElementsByTagName("script")[0].style;for(var n in t){if(e.test(n)){return"-"+n.match(e)[0].toLowerCase()+"-"}}if("WebkitOpacity"in t){return"-webkit-"}if("KhtmlOpacity"in t){return"-khtml-"}return""}();function h(e,t){for(var n in t){if(t[n]){e[n]=t[n]}}return e}function p(e,t){e.prototype=h(e.prototype||{},t.prototype)}function d(e){e=e||{};this._startOffsetX=0;this._currentOffsetX=0;this._opening=false;this._moved=false;this._opened=false;this._preventOpen=false;this._touch=e.touch===undefined?true:e.touch&&true;this.panel=e.panel;this.menu=e.menu;if(this.panel.className.search("slideout-panel")===-1){this.panel.className+=" slideout-panel"}if(this.menu.className.search("slideout-menu")===-1){this.menu.className+=" slideout-menu"}this._fx=e.fx||"ease";this._duration=parseInt(e.duration,10)||300;this._tolerance=parseInt(e.tolerance,10)||70;this._padding=this._translateTo=parseInt(e.padding,10)||256;this._orientation=e.side==="right"?-1:1;this._translateTo*=this._orientation;if(this._touch){this._initTouchEvents()}}p(d,o);d.prototype.open=function(){var e=this;this.emit("beforeopen");if(u.className.search("slideout-open")===-1){u.className+=" slideout-open"}this._setTransition();this._translateXTo(this._translateTo);this._opened=true;setTimeout(function(){e.panel.style.transition=e.panel.style["-webkit-transition"]="";e.emit("open")},this._duration+50);return this};d.prototype.close=function(){var e=this;if(!this.isOpen()&&!this._opening){return this}this.emit("beforeclose");this._setTransition();this._translateXTo(0);this._opened=false;setTimeout(function(){u.className=u.className.replace(/ slideout-open/,"");e.panel.style.transition=e.panel.style["-webkit-transition"]=e.panel.style[c+"transform"]=e.panel.style.transform="";e.emit("close")},this._duration+50);return this};d.prototype.toggle=function(){return this.isOpen()?this.close():this.open()};d.prototype.isOpen=function(){return this._opened};d.prototype._translateXTo=function(e){this._currentOffsetX=e;this.panel.style[c+"transform"]=this.panel.style.transform="translate3d("+e+"px, 0, 0)"};d.prototype._setTransition=function(){this.panel.style[c+"transition"]=this.panel.style.transition=c+"transform "+this._duration+"ms "+this._fx};d.prototype._initTouchEvents=function(){var e=this;this._onScrollFn=i(a,"scroll",function(){if(!e._moved){clearTimeout(s);r=true;s=setTimeout(function(){r=false},250)}});this._preventMove=function(t){if(e._moved){t.preventDefault()}};a.addEventListener(f.move,this._preventMove);this._resetTouchFn=function(t){if(typeof t.touches==="undefined"){return}e._moved=false;e._opening=false;e._startOffsetX=t.touches[0].pageX;e._preventOpen=!e._touch||!e.isOpen()&&e.menu.clientWidth!==0};this.panel.addEventListener(f.start,this._resetTouchFn);this._onTouchCancelFn=function(){e._moved=false;e._opening=false};this.panel.addEventListener("touchcancel",this._onTouchCancelFn);this._onTouchEndFn=function(){if(e._moved){e._opening&&Math.abs(e._currentOffsetX)>e._tolerance?e.open():e.close()}e._moved=false};this.panel.addEventListener(f.end,this._onTouchEndFn);this._onTouchMoveFn=function(t){if(r||e._preventOpen||typeof t.touches==="undefined"){return}var n=t.touches[0].clientX-e._startOffsetX;var i=e._currentOffsetX=n;if(Math.abs(i)>e._padding){return}if(Math.abs(n)>20){e._opening=true;var o=n*e._orientation;if(e._opened&&o>0||!e._opened&&o<0){return}if(o<=0){i=n+e._padding*e._orientation;e._opening=false}if(!e._moved&&u.className.search("slideout-open")===-1){u.className+=" slideout-open"}e.panel.style[c+"transform"]=e.panel.style.transform="translate3d("+i+"px, 0, 0)";e.emit("translate",i);e._moved=true}};this.panel.addEventListener(f.move,this._onTouchMoveFn)};d.prototype.enableTouch=function(){this._touch=true;return this};d.prototype.disableTouch=function(){this._touch=false;return this};d.prototype.destroy=function(){this.close();a.removeEventListener(f.move,this._preventMove);this.panel.removeEventListener(f.start,this._resetTouchFn);this.panel.removeEventListener("touchcancel",this._onTouchCancelFn);this.panel.removeEventListener(f.end,this._onTouchEndFn);this.panel.removeEventListener(f.move,this._onTouchMoveFn);a.removeEventListener("scroll",this._onScrollFn);this.open=this.close=function(){};return this};t.exports=d},{decouple:2,emitter:3}],2:[function(e,t,n){"use strict";var i=function(){return window.requestAnimationFrame||window.webkitRequestAnimationFrame||function(e){window.setTimeout(e,1e3/60)}}();function o(e,t,n){var o,s=false;function r(e){o=e;a()}function a(){if(!s){i(u);s=true}}function u(){n.call(e,o);s=false}e.addEventListener(t,r,false)}t.exports=o},{}],3:[function(e,t,n){"use strict";var i=function(e,t){if(!(e instanceof t)){throw new TypeError("Cannot call a class as a function")}};n.__esModule=true;var o=function(){function e(){i(this,e)}e.prototype.on=function t(e,n){this._eventCollection=this._eventCollection||{};this._eventCollection[e]=this._eventCollection[e]||[];this._eventCollection[e].push(n);return this};e.prototype.once=function n(e,t){var n=this;function i(){n.off(e,i);t.apply(this,arguments)}i.listener=t;this.on(e,i);return this};e.prototype.off=function o(e,t){var n=undefined;if(!this._eventCollection||!(n=this._eventCollection[e])){return this}n.forEach(function(e,i){if(e===t||e.listener===t){n.splice(i,1)}});if(n.length===0){delete this._eventCollection[e]}return this};e.prototype.emit=function s(e){var t=this;for(var n=arguments.length,i=Array(n>1?n-1:0),o=1;o<n;o++){i[o-1]=arguments[o]}var s=undefined;if(!this._eventCollection||!(s=this._eventCollection[e])){return this}s=s.slice(0);s.forEach(function(e){return e.apply(t,i)});return this};return e}();n["default"]=o;t.exports=n["default"]},{}]},{},[1])(1)});


// showdown 27-08-2015
// https://github.com/showdownjs/showdown
(function(){function a(a){"use strict";var b={omitExtraWLInCodeBlocks:{"default":!1,describe:"Omit the default extra whiteline added to code blocks",type:"boolean"},noHeaderId:{"default":!1,describe:"Turn on/off generated header id",type:"boolean"},prefixHeaderId:{"default":!1,describe:"Specify a prefix to generated header ids",type:"string"},headerLevelStart:{"default":!1,describe:"The header blocks level start",type:"integer"},parseImgDimensions:{"default":!1,describe:"Turn on/off image dimension parsing",type:"boolean"},simplifiedAutoLink:{"default":!1,describe:"Turn on/off GFM autolink style",type:"boolean"},literalMidWordUnderscores:{"default":!1,describe:"Parse midword underscores as literal underscores",type:"boolean"},strikethrough:{"default":!1,describe:"Turn on/off strikethrough support",type:"boolean"},tables:{"default":!1,describe:"Turn on/off tables support",type:"boolean"},tablesHeaderId:{"default":!1,describe:"Add an id to table headers",type:"boolean"},ghCodeBlocks:{"default":!0,describe:"Turn on/off GFM fenced code blocks support",type:"boolean"},tasklists:{"default":!1,describe:"Turn on/off GFM tasklist support",type:"boolean"},smoothLivePreview:{"default":!1,describe:"Prevents weird effects in live previews due to incomplete input",type:"boolean"}};if(a===!1)return JSON.parse(JSON.stringify(b));var c={};for(var d in b)b.hasOwnProperty(d)&&(c[d]=b[d]["default"]);return c}function b(a,b){"use strict";var c=b?"Error in "+b+" extension->":"Error in unnamed extension",e={valid:!0,error:""};d.helper.isArray(a)||(a=[a]);for(var f=0;f<a.length;++f){var g=c+" sub-extension "+f+": ",h=a[f];if("object"!=typeof h)return e.valid=!1,e.error=g+"must be an object, but "+typeof h+" given",e;if(!d.helper.isString(h.type))return e.valid=!1,e.error=g+'property "type" must be a string, but '+typeof h.type+" given",e;var i=h.type=h.type.toLowerCase();if("language"===i&&(i=h.type="lang"),"html"===i&&(i=h.type="output"),"lang"!==i&&"output"!==i)return e.valid=!1,e.error=g+"type "+i+' is not recognized. Valid values: "lang" or "output"',e;if(h.filter){if("function"!=typeof h.filter)return e.valid=!1,e.error=g+'"filter" must be a function, but '+typeof h.filter+" given",e}else{if(!h.regex)return e.valid=!1,e.error=g+'extensions must define either a "regex" property or a "filter" method',e;if(d.helper.isString(h.regex)&&(h.regex=new RegExp(h.regex,"g")),!h.regex instanceof RegExp)return e.valid=!1,e.error=g+'"regex" property must either be a string or a RegExp object, but '+typeof h.regex+" given",e;if(d.helper.isUndefined(h.replace))return e.valid=!1,e.error=g+'"regex" extensions must implement a replace string or function',e}if(d.helper.isUndefined(h.filter)&&d.helper.isUndefined(h.regex))return e.valid=!1,e.error=g+"output extensions must define a filter property",e}return e}function c(a,b){"use strict";var c=b.charCodeAt(0);return"~E"+c+"E"}var d={},e={},f={},g=a(!0),h={github:{omitExtraWLInCodeBlocks:!0,prefixHeaderId:"user-content-",simplifiedAutoLink:!0,literalMidWordUnderscores:!0,strikethrough:!0,tables:!0,tablesHeaderId:!0,ghCodeBlocks:!0,tasklists:!0},vanilla:a(!0)};d.helper={},d.extensions={},d.setOption=function(a,b){"use strict";return g[a]=b,this},d.getOption=function(a){"use strict";return g[a]},d.getOptions=function(){"use strict";return g},d.resetOptions=function(){"use strict";g=a(!0)},d.setFlavor=function(a){"use strict";if(h.hasOwnProperty(a)){var b=h[a];for(var c in b)b.hasOwnProperty(c)&&(g[c]=b[c])}},d.getDefaultOptions=function(b){"use strict";return a(b)},d.subParser=function(a,b){"use strict";if(d.helper.isString(a)){if("undefined"==typeof b){if(e.hasOwnProperty(a))return e[a];throw Error("SubParser named "+a+" not registered!")}e[a]=b}},d.extension=function(a,c){"use strict";if(!d.helper.isString(a))throw Error("Extension 'name' must be a string");if(a=d.helper.stdExtName(a),d.helper.isUndefined(c)){if(!f.hasOwnProperty(a))throw Error("Extension named "+a+" is not registered!");return f[a]}"function"==typeof c&&(c=c()),d.helper.isArray(c)||(c=[c]);var e=b(c,a);if(!e.valid)throw Error(e.error);f[a]=c},d.getAllExtensions=function(){"use strict";return f},d.removeExtension=function(a){"use strict";delete f[a]},d.resetExtensions=function(){"use strict";f={}},d.validateExtension=function(a){"use strict";var c=b(a,null);return c.valid?!0:(console.warn(c.error),!1)},d.hasOwnProperty("helper")||(d.helper={}),d.helper.isString=function(a){"use strict";return"string"==typeof a||a instanceof String},d.helper.forEach=function(a,b){"use strict";if("function"==typeof a.forEach)a.forEach(b);else for(var c=0;c<a.length;c++)b(a[c],c,a)},d.helper.isArray=function(a){"use strict";return a.constructor===Array},d.helper.isUndefined=function(a){"use strict";return"undefined"==typeof a},d.helper.stdExtName=function(a){"use strict";return a.replace(/[_-]||\s/g,"").toLowerCase()},d.helper.escapeCharactersCallback=c,d.helper.escapeCharacters=function(a,b,d){"use strict";var e="(["+b.replace(/([\[\]\\])/g,"\\$1")+"])";d&&(e="\\\\"+e);var f=new RegExp(e,"g");return a=a.replace(f,c)},d.helper.isUndefined(console)&&(console={warn:function(a){"use strict";alert(a)},log:function(a){"use strict";alert(a)}}),d.Converter=function(a){"use strict";function c(){a=a||{};for(var b in g)g.hasOwnProperty(b)&&(k[b]=g[b]);if("object"!=typeof a)throw Error("Converter expects the passed parameter to be an object, but "+typeof a+" was passed instead.");for(var c in a)a.hasOwnProperty(c)&&(k[c]=a[c]);k.extensions&&d.helper.forEach(k.extensions,i)}function i(a,c){if(c=c||null,d.helper.isString(a)){if(a=d.helper.stdExtName(a),c=a,d.extensions[a])return console.warn("DEPRECATION WARNING: "+a+" is an old extension that uses a deprecated loading method.Please inform the developer that the extension should be updated!"),void j(d.extensions[a],a);if(d.helper.isUndefined(f[a]))throw Error('Extension "'+a+'" could not be loaded. It was either not found or is not a valid extension.');a=f[a]}"function"==typeof a&&(a=a()),d.helper.isArray(a)||(a=[a]);var e=b(a,c);if(!e.valid)throw Error(e.error);for(var g=0;g<a.length;++g)switch(a[g].type){case"lang":l.push(a[g]);break;case"output":m.push(a[g]);break;default:throw Error("Extension loader error: Type unrecognized!!!")}}function j(a,c){"function"==typeof a&&(a=a(new d.Converter)),d.helper.isArray(a)||(a=[a]);var e=b(a,c);if(!e.valid)throw Error(e.error);for(var f=0;f<a.length;++f)switch(a[f].type){case"lang":l.push(a[f]);break;case"output":m.push(a[f]);break;default:throw Error("Extension loader error: Type unrecognized!!!")}}var k={},l=[],m=[],n=["githubCodeBlocks","hashHTMLBlocks","stripLinkDefinitions","blockGamut","unescapeSpecialChars"];c(),this.makeHtml=function(a){if(!a)return a;var b={gHtmlBlocks:[],gUrls:{},gTitles:{},gDimensions:{},gListLevel:0,hashLinkCounts:{},langExtensions:l,outputModifiers:m,converter:this};a=a.replace(/~/g,"~T"),a=a.replace(/\$/g,"~D"),a=a.replace(/\r\n/g,"\n"),a=a.replace(/\r/g,"\n"),a="\n\n"+a+"\n\n",a=d.subParser("detab")(a,k,b),a=d.subParser("stripBlankLines")(a,k,b),d.helper.forEach(l,function(c){a=d.subParser("runExtension")(c,a,k,b)});for(var c=0;c<n.length;++c){var f=n[c];a=e[f](a,k,b)}return a=a.replace(/~D/g,"$$"),a=a.replace(/~T/g,"~"),d.helper.forEach(m,function(c){a=d.subParser("runExtension")(c,a,k,b)}),a},this.setOption=function(a,b){k[a]=b},this.getOption=function(a){return k[a]},this.getOptions=function(){return k},this.addExtension=function(a,b){b=b||null,i(a,b)},this.useExtension=function(a){i(a)},this.setFlavor=function(a){if(h.hasOwnProperty(a)){var b=h[a];for(var c in b)b.hasOwnProperty(c)&&(k[c]=b[c])}},this.removeExtension=function(a){d.helper.isArray(a)||(a=[a]);for(var b=0;b<a.length;++b){for(var c=a[b],e=0;e<l.length;++e)l[e]===c&&l[e].splice(e,1);for(var f=0;f<m.length;++e)m[f]===c&&m[f].splice(e,1)}},this.getAllExtensions=function(){return{language:l,output:m}}},d.subParser("anchors",function(a,b,c){"use strict";var e=function(a,b,e,f,g,h,i,j){d.helper.isUndefined(j)&&(j=""),a=b;var k=e,l=f.toLowerCase(),m=g,n=j;if(!m)if(l||(l=k.toLowerCase().replace(/ ?\n/g," ")),m="#"+l,d.helper.isUndefined(c.gUrls[l])){if(!(a.search(/\(\s*\)$/m)>-1))return a;m=""}else m=c.gUrls[l],d.helper.isUndefined(c.gTitles[l])||(n=c.gTitles[l]);m=d.helper.escapeCharacters(m,"*_",!1);var o='<a href="'+m+'"';return""!==n&&null!==n&&(n=n.replace(/"/g,"&quot;"),n=d.helper.escapeCharacters(n,"*_",!1),o+=' title="'+n+'"'),o+=">"+k+"</a>"};return a=a.replace(/(\[((?:\[[^\]]*\]|[^\[\]])*)\][ ]?(?:\n[ ]*)?\[(.*?)\])()()()()/g,e),a=a.replace(/(\[((?:\[[^\]]*\]|[^\[\]])*)\]\([ \t]*()<?(.*?(?:\(.*?\).*?)?)>?[ \t]*((['"])(.*?)\6[ \t]*)?\))/g,e),a=a.replace(/(\[([^\[\]]+)\])()()()()()/g,e)}),d.subParser("autoLinks",function(a,b){"use strict";function c(a,b){var c=d.subParser("unescapeSpecialChars")(b);return d.subParser("encodeEmailAddress")(c)}var e=/\b(((https?|ftp|dict):\/\/|www\.)[^'">\s]+\.[^'">\s]+)(?=\s|$)(?!["<>])/gi,f=/<(((https?|ftp|dict):\/\/|www\.)[^'">\s]+)>/gi,g=/\b(?:mailto:)?([-.\w]+@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]+)\b/gi,h=/<(?:mailto:)?([-.\w]+@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]+)>/gi;return a=a.replace(f,'<a href="$1">$1</a>'),a=a.replace(h,c),b.simplifiedAutoLink&&(a=a.replace(e,'<a href="$1">$1</a>'),a=a.replace(g,c)),a}),d.subParser("blockGamut",function(a,b,c){"use strict";a=d.subParser("blockQuotes")(a,b,c),a=d.subParser("headers")(a,b,c);var e=d.subParser("hashBlock")("<hr />",b,c);return a=a.replace(/^[ ]{0,2}([ ]?\*[ ]?){3,}[ \t]*$/gm,e),a=a.replace(/^[ ]{0,2}([ ]?\-[ ]?){3,}[ \t]*$/gm,e),a=a.replace(/^[ ]{0,2}([ ]?_[ ]?){3,}[ \t]*$/gm,e),a=d.subParser("lists")(a,b,c),a=d.subParser("codeBlocks")(a,b,c),a=d.subParser("tables")(a,b,c),a=d.subParser("hashHTMLBlocks")(a,b,c),a=d.subParser("paragraphs")(a,b,c)}),d.subParser("blockQuotes",function(a,b,c){"use strict";return a=a.replace(/((^[ \t]{0,3}>[ \t]?.+\n(.+\n)*\n*)+)/gm,function(a,e){var f=e;return f=f.replace(/^[ \t]*>[ \t]?/gm,"~0"),f=f.replace(/~0/g,""),f=f.replace(/^[ \t]+$/gm,""),f=d.subParser("githubCodeBlocks")(f,b,c),f=d.subParser("blockGamut")(f,b,c),f=f.replace(/(^|\n)/g,"$1  "),f=f.replace(/(\s*<pre>[^\r]+?<\/pre>)/gm,function(a,b){var c=b;return c=c.replace(/^  /gm,"~0"),c=c.replace(/~0/g,"")}),d.subParser("hashBlock")("<blockquote>\n"+f+"\n</blockquote>",b,c)})}),d.subParser("codeBlocks",function(a,b,c){"use strict";a+="~0";var e=/(?:\n\n|^)((?:(?:[ ]{4}|\t).*\n+)+)(\n*[ ]{0,3}[^ \t\n]|(?=~0))/g;return a=a.replace(e,function(a,e,f){var g=e,h=f,i="\n";return g=d.subParser("outdent")(g),g=d.subParser("encodeCode")(g),g=d.subParser("detab")(g),g=g.replace(/^\n+/g,""),g=g.replace(/\n+$/g,""),b.omitExtraWLInCodeBlocks&&(i=""),g="<pre class=\"brush:plain;toolbar:false;\"><code>"+g+i+"</code></pre>",d.subParser("hashBlock")(g,b,c)+h}),a=a.replace(/~0/,"")}),d.subParser("codeSpans",function(a){"use strict";return a=a.replace(/(<code[^><]*?>)([^]*?)<\/code>/g,function(a,b,c){return c=c.replace(/^([ \t]*)/g,""),c=c.replace(/[ \t]*$/g,""),c=d.subParser("encodeCode")(c),b+c+"</code>"}),a=a.replace(/(^|[^\\])(`+)([^\r]*?[^`])\2(?!`)/gm,function(a,b,c,e){var f=e;return f=f.replace(/^([ \t]*)/g,""),f=f.replace(/[ \t]*$/g,""),f=d.subParser("encodeCode")(f),b+"<code>"+f+"</code>"})}),d.subParser("detab",function(a){"use strict";return a=a.replace(/\t(?=\t)/g,"    "),a=a.replace(/\t/g,"~A~B"),a=a.replace(/~B(.+?)~A/g,function(a,b){for(var c=b,d=4-c.length%4,e=0;d>e;e++)c+=" ";return c}),a=a.replace(/~A/g,"    "),a=a.replace(/~B/g,"")}),d.subParser("encodeAmpsAndAngles",function(a){"use strict";return a=a.replace(/&(?!#?[xX]?(?:[0-9a-fA-F]+|\w+);)/g,"&amp;"),a=a.replace(/<(?![a-z\/?\$!])/gi,"&lt;")}),d.subParser("encodeBackslashEscapes",function(a){"use strict";return a=a.replace(/\\(\\)/g,d.helper.escapeCharactersCallback),a=a.replace(/\\([`*_{}\[\]()>#+-.!])/g,d.helper.escapeCharactersCallback)}),d.subParser("encodeCode",function(a){"use strict";return a=a.replace(/&/g,"&amp;"),a=a.replace(/</g,"&lt;"),a=a.replace(/>/g,"&gt;"),a=d.helper.escapeCharacters(a,"*_{}[]\\",!1)}),d.subParser("encodeEmailAddress",function(a){"use strict";var b=[function(a){return"&#"+a.charCodeAt(0)+";"},function(a){return"&#x"+a.charCodeAt(0).toString(16)+";"},function(a){return a}];return a="mailto:"+a,a=a.replace(/./g,function(a){if("@"===a)a=b[Math.floor(2*Math.random())](a);else if(":"!==a){var c=Math.random();a=c>.9?b[2](a):c>.45?b[1](a):b[0](a)}return a}),a='<a href="'+a+'">'+a+"</a>",a=a.replace(/">.+:/g,'">')}),d.subParser("escapeSpecialCharsWithinTagAttributes",function(a){"use strict";var b=/(<[a-z\/!$]("[^"]*"|'[^']*'|[^'">])*>|<!(--.*?--\s*)+>)/gi;return a=a.replace(b,function(a){var b=a.replace(/(.)<\/?code>(?=.)/g,"$1`");return b=d.helper.escapeCharacters(b,"\\`*_",!1)})}),d.subParser("githubCodeBlocks",function(a,b,c){"use strict";return b.ghCodeBlocks?(a+="~0",a=a.replace(/(?:^|\n)```(.*)\n([\s\S]*?)\n```/g,function(a,e,f){var g=b.omitExtraWLInCodeBlocks?"":"\n";return f=d.subParser("encodeCode")(f),f=d.subParser("detab")(f),f=f.replace(/^\n+/g,""),f=f.replace(/\n+$/g,""),f="<pre class=\"brush:plain;toolbar:false;\"><code"+(e?' class="'+e+" language-"+e+'"':"")+">"+f+g+"</code></pre>",d.subParser("hashBlock")(f,b,c)}),a=a.replace(/~0/,"")):a}),d.subParser("hashBlock",function(a,b,c){"use strict";return a=a.replace(/(^\n+|\n+$)/g,""),"\n\n~K"+(c.gHtmlBlocks.push(a)-1)+"K\n\n"}),d.subParser("hashElement",function(a,b,c){"use strict";return function(a,b){var d=b;return d=d.replace(/\n\n/g,"\n"),d=d.replace(/^\n/,""),d=d.replace(/\n+$/g,""),d="\n\n~K"+(c.gHtmlBlocks.push(d)-1)+"K\n\n"}}),d.subParser("hashHTMLBlocks",function(a,b,c){"use strict";return a=a.replace(/\n/g,"\n\n"),a=a.replace(/^(<(p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|script|noscript|form|fieldset|iframe|math|ins|del)\b[^\r]*?\n<\/\2>[ \t]*(?=\n+))/gm,d.subParser("hashElement")(a,b,c)),a=a.replace(/^(<(p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|script|noscript|form|fieldset|iframe|math|style|section|header|footer|nav|article|aside|address|audio|canvas|figure|hgroup|output|video)\b[^\r]*?<\/\2>[ \t]*(?=\n+)\n)/gm,d.subParser("hashElement")(a,b,c)),a=a.replace(/(\n[ ]{0,3}(<(hr)\b([^<>])*?\/?>)[ \t]*(?=\n{2,}))/g,d.subParser("hashElement")(a,b,c)),a=a.replace(/(\n\n[ ]{0,3}<!(--[^\r]*?--\s*)+>[ \t]*(?=\n{2,}))/g,d.subParser("hashElement")(a,b,c)),a=a.replace(/(?:\n\n)([ ]{0,3}(?:<([?%])[^\r]*?\2>)[ \t]*(?=\n{2,}))/g,d.subParser("hashElement")(a,b,c)),a=a.replace(/\n\n/g,"\n")}),d.subParser("headers",function(a,b,c){"use strict";function e(a){var b,e=a.replace(/[^\w]/g,"").toLowerCase();return c.hashLinkCounts[e]?b=e+"-"+c.hashLinkCounts[e]++:(b=e,c.hashLinkCounts[e]=1),f===!0&&(f="section"),d.helper.isString(f)?f+b:b}var f=b.prefixHeaderId,g=isNaN(parseInt(b.headerLevelStart))?1:parseInt(b.headerLevelStart),h=b.smoothLivePreview?/^(.+)[ \t]*\n={2,}[ \t]*\n+/gm:/^(.+)[ \t]*\n=+[ \t]*\n+/gm,i=b.smoothLivePreview?/^(.+)[ \t]*\n-{2,}[ \t]*\n+/gm:/^(.+)[ \t]*\n-+[ \t]*\n+/gm;return a=a.replace(h,function(a,f){var h=d.subParser("spanGamut")(f,b,c),i=b.noHeaderId?"":' id="'+e(f)+'"',j=g,k="<h"+j+i+">"+h+"</h"+j+">";return d.subParser("hashBlock")(k,b,c)}),a=a.replace(i,function(a,f){var h=d.subParser("spanGamut")(f,b,c),i=b.noHeaderId?"":' id="'+e(f)+'"',j=g+1,k="<h"+j+i+">"+h+"</h"+j+">";return d.subParser("hashBlock")(k,b,c)}),a=a.replace(/^(#{1,6})[ \t]*(.+?)[ \t]*#*\n+/gm,function(a,f,h){var i=d.subParser("spanGamut")(h,b,c),j=b.noHeaderId?"":' id="'+e(h)+'"',k=g-1+f.length,l="<h"+k+j+">"+i+"</h"+k+">";return d.subParser("hashBlock")(l,b,c)})}),d.subParser("images",function(a,b,c){"use strict";function e(a,b,e,f,g,h,i,j){var k=c.gUrls,l=c.gTitles,m=c.gDimensions;if(e=e.toLowerCase(),j||(j=""),""===f||null===f){if((""===e||null===e)&&(e=b.toLowerCase().replace(/ ?\n/g," ")),f="#"+e,d.helper.isUndefined(k[e]))return a;f=k[e],d.helper.isUndefined(l[e])||(j=l[e]),d.helper.isUndefined(m[e])||(g=m[e].width,h=m[e].height)}b=b.replace(/"/g,"&quot;"),b=d.helper.escapeCharacters(b,"*_",!1),f=d.helper.escapeCharacters(f,"*_",!1);var n='<img src="'+f+'" alt="'+b+'"';return j&&(j=j.replace(/"/g,"&quot;"),j=d.helper.escapeCharacters(j,"*_",!1),n+=' title="'+j+'"'),g&&h&&(g="*"===g?"auto":g,h="*"===h?"auto":h,n+=' width="'+g+'"',n+=' height="'+h+'"'),n+=" />"}var f=/!\[(.*?)]\s?\([ \t]*()<?(\S+?)>?(?: =([*\d]+[A-Za-z%]{0,4})x([*\d]+[A-Za-z%]{0,4}))?[ \t]*(?:(['"])(.*?)\6[ \t]*)?\)/g,g=/!\[(.*?)][ ]?(?:\n[ ]*)?\[(.*?)]()()()()()/g;return a=a.replace(g,e),a=a.replace(f,e)}),d.subParser("italicsAndBold",function(a,b){"use strict";return b.literalMidWordUnderscores?(a=a.replace(/(^|\s|>|\b)__(?=\S)([^]+?)__(?=\b|<|\s|$)/gm,"$1<strong>$2</strong>"),a=a.replace(/(^|\s|>|\b)_(?=\S)([^]+?)_(?=\b|<|\s|$)/gm,"$1<em>$2</em>"),a=a.replace(/\*\*(?=\S)([^]+?)\*\*/g,"<strong>$1</strong>"),a=a.replace(/\*(?=\S)([^]+?)\*/g,"<em>$1</em>")):(a=a.replace(/(\*\*|__)(?=\S)([^\r]*?\S[*_]*)\1/g,"<strong>$2</strong>"),a=a.replace(/(\*|_)(?=\S)([^\r]*?\S)\1/g,"<em>$2</em>")),a}),d.subParser("lists",function(a,b,c){"use strict";function e(a,e){c.gListLevel++,a=a.replace(/\n{2,}$/,"\n"),a+="~0";var f=/(\n)?(^[ \t]*)([*+-]|\d+[.])[ \t]+((\[(x| )?])?[ \t]*[^\r]+?(\n{1,2}))(?=\n*(~0|\2([*+-]|\d+[.])[ \t]+))/gm,g=/\n[ \t]*\n(?!~0)/.test(a);return a=a.replace(f,function(a,e,f,h,i,j,k){k=k&&""!==k.trim();var l=d.subParser("outdent")(i,b,c),m="";return j&&b.tasklists&&(m=' class="task-list-item" style="list-style-type: none;"',l=l.replace(/^[ \t]*\[(x| )?]/m,function(){var a='<input type="checkbox" disabled style="margin: 0px 0.35em 0.25em -1.6em; vertical-align: middle;"';return k&&(a+=" checked"),a+=">"})),e||l.search(/\n{2,}/)>-1?(l=d.subParser("githubCodeBlocks")(l,b,c),l=d.subParser("blockGamut")(l,b,c)):(l=d.subParser("lists")(l,b,c),l=l.replace(/\n$/,""),l=g?d.subParser("paragraphs")(l,b,c):d.subParser("spanGamut")(l,b,c)),l="\n<li"+m+">"+l+"</li>\n"}),a=a.replace(/~0/g,""),c.gListLevel--,e&&(a=a.replace(/\s+$/,"")),a}function f(a,b,c){var d="ul"===b?/^ {0,2}\d+\.[ \t]/gm:/^ {0,2}[*+-][ \t]/gm,f=[],g="";if(-1!==a.search(d)){!function i(a){var f=a.search(d);-1!==f?(g+="\n\n<"+b+">"+e(a.slice(0,f),!!c)+"</"+b+">\n\n",b="ul"===b?"ol":"ul",d="ul"===b?/^ {0,2}\d+\.[ \t]/gm:/^ {0,2}[*+-][ \t]/gm,i(a.slice(f))):g+="\n\n<"+b+">"+e(a,!!c)+"</"+b+">\n\n"}(a);for(var h=0;h<f.length;++h);}else g="\n\n<"+b+">"+e(a,!!c)+"</"+b+">\n\n";return g}a+="~0";var g=/^(([ ]{0,3}([*+-]|\d+[.])[ \t]+)[^\r]+?(~0|\n{2,}(?=\S)(?![ \t]*(?:[*+-]|\d+[.])[ \t]+)))/gm;return c.gListLevel?a=a.replace(g,function(a,b,c){var d=c.search(/[*+-]/g)>-1?"ul":"ol";return f(b,d,!0)}):(g=/(\n\n|^\n?)(([ ]{0,3}([*+-]|\d+[.])[ \t]+)[^\r]+?(~0|\n{2,}(?=\S)(?![ \t]*(?:[*+-]|\d+[.])[ \t]+)))/gm,a=a.replace(g,function(a,b,c,d){var e=d.search(/[*+-]/g)>-1?"ul":"ol";return f(c,e)})),a=a.replace(/~0/,"")}),d.subParser("outdent",function(a){"use strict";return a=a.replace(/^(\t|[ ]{1,4})/gm,"~0"),a=a.replace(/~0/g,"")}),d.subParser("paragraphs",function(a,b,c){"use strict";a=a.replace(/^\n+/g,""),a=a.replace(/\n+$/g,"");for(var e=a.split(/\n{2,}/g),f=[],g=e.length,h=0;g>h;h++){var i=e[h];i.search(/~K(\d+)K/g)>=0?f.push(i):i.search(/\S/)>=0&&(i=d.subParser("spanGamut")(i,b,c),i=i.replace(/^([ \t]*)/g,"<p>"),i+="</p>",f.push(i))}for(g=f.length,h=0;g>h;h++)for(;f[h].search(/~K(\d+)K/)>=0;){var j=c.gHtmlBlocks[RegExp.$1];j=j.replace(/\$/g,"$$$$"),f[h]=f[h].replace(/~K\d+K/,j)}return f.join("\n\n")}),d.subParser("runExtension",function(a,b,c,d){"use strict";if(a.filter)b=a.filter(b,d.converter,c);else if(a.regex){var e=a.regex;!e instanceof RegExp&&(e=new RegExp(e,"g")),b=b.replace(e,a.replace)}return b}),d.subParser("spanGamut",function(a,b,c){"use strict";return a=d.subParser("codeSpans")(a,b,c),a=d.subParser("escapeSpecialCharsWithinTagAttributes")(a,b,c),a=d.subParser("encodeBackslashEscapes")(a,b,c),a=d.subParser("images")(a,b,c),a=d.subParser("anchors")(a,b,c),a=d.subParser("autoLinks")(a,b,c),a=d.subParser("encodeAmpsAndAngles")(a,b,c),a=d.subParser("italicsAndBold")(a,b,c),a=d.subParser("strikethrough")(a,b,c),a=a.replace(/  +\n/g," <br />\n")}),d.subParser("strikethrough",function(a,b){"use strict";return b.strikethrough&&(a=a.replace(/(?:~T){2}([^~]+)(?:~T){2}/g,"<del>$1</del>")),a}),d.subParser("stripBlankLines",function(a){"use strict";return a.replace(/^[ \t]+$/gm,"")}),d.subParser("stripLinkDefinitions",function(a,b,c){"use strict";var e=/^ {0,3}\[(.+)]:[ \t]*\n?[ \t]*<?(\S+?)>?(?: =([*\d]+[A-Za-z%]{0,4})x([*\d]+[A-Za-z%]{0,4}))?[ \t]*\n?[ \t]*(?:(\n*)["|'(](.+?)["|')][ \t]*)?(?:\n+|(?=~0))/gm;return a+="~0",a=a.replace(e,function(a,e,f,g,h,i,j){return e=e.toLowerCase(),c.gUrls[e]=d.subParser("encodeAmpsAndAngles")(f),i?i+j:(j&&(c.gTitles[e]=j.replace(/"|'/g,"&quot;")),b.parseImgDimensions&&g&&h&&(c.gDimensions[e]={width:g,height:h}),"")}),a=a.replace(/~0/,"")}),d.subParser("tables",function(a,b,c){"use strict";var e=function(){var a,e={};return e.th=function(a,e){var f="";return a=a.trim(),""===a?"":(b.tableHeaderId&&(f=' id="'+a.replace(/ /g,"_").toLowerCase()+'"'),a=d.subParser("spanGamut")(a,b,c),e=e&&""!==e.trim()?' style="'+e+'"':"","<th"+f+e+">"+a+"</th>")},e.td=function(a,e){var f=d.subParser("spanGamut")(a.trim(),b,c);return e=e&&""!==e.trim()?' style="'+e+'"':"","<td"+e+">"+f+"</td>"},e.ths=function(){var a="",b=0,c=[].slice.apply(arguments[0]),d=[].slice.apply(arguments[1]);for(b;b<c.length;b+=1)a+=e.th(c[b],d[b])+"\n";return a},e.tds=function(){var a="",b=0,c=[].slice.apply(arguments[0]),d=[].slice.apply(arguments[1]);for(b;b<c.length;b+=1)a+=e.td(c[b],d[b])+"\n";return a},e.thead=function(){var a,b=[].slice.apply(arguments[0]),c=[].slice.apply(arguments[1]);return a="<thead>\n",a+="<tr>\n",a+=e.ths.apply(this,[b,c]),a+="</tr>\n",a+="</thead>\n"},e.tr=function(){var a,b=[].slice.apply(arguments[0]),c=[].slice.apply(arguments[1]);return a="<tr>\n",a+=e.tds.apply(this,[b,c]),a+="</tr>\n"},a=function(a){var b,c,d=0,f=a.split("\n"),g=[];for(d;d<f.length;d+=1){if(b=f[d],b.trim().match(/^[|].*[|]$/)){b=b.trim();var h=[],i=f[d+1].trim(),j=[],k=0;if(i.match(/^[|][-=|: ]+[|]$/))for(j=i.substring(1,i.length-1).split("|"),k=0;k<j.length;++k)j[k]=j[k].trim(),j[k].match(/^[:][-=| ]+[:]$/)?j[k]="text-align:center;":j[k].match(/^[-=| ]+[:]$/)?j[k]="text-align:right;":j[k].match(/^[:][-=| ]+$/)?j[k]="text-align:left;":j[k]="";if(h.push("<table>"),c=b.substring(1,b.length-1).split("|"),0===j.length)for(k=0;k<c.length;++k)j.push("text-align:left");if(h.push(e.thead.apply(this,[c,j])),b=f[++d],b.trim().match(/^[|][-=|: ]+[|]$/)){for(b=f[++d],h.push("<tbody>");b.trim().match(/^[|].*[|]$/);)b=b.trim(),h.push(e.tr.apply(this,[b.substring(1,b.length-1).split("|"),j])),b=f[++d];h.push("</tbody>"),h.push("</table>"),g.push(h.join("\n"));continue}b=f[--d]}g.push(b)}return g.join("\n")},{parse:a}};if(b.tables){var f=e();return f.parse(a)}return a}),d.subParser("unescapeSpecialChars",function(a){"use strict";return a=a.replace(/~E(\d+)E/g,function(a,b){var c=parseInt(b);return String.fromCharCode(c)})});var i=this;"undefined"!=typeof module&&module.exports?module.exports=d:"function"==typeof define&&define.amd?define("showdown",function(){"use strict";return d}):i.showdown=d}).call(this);


// App framework Plugin: Toast
(function ($) {
	"use strict";
	$.fn.toast = function (opts) {
		return new Toast(this[0], opts);
	};
	var Toast = (function () {
		var Toast = function (containerEl, opts) {

			if (typeof containerEl === "string" || containerEl instanceof String) {
				this.container = document.getElementById(containerEl);
			} else {
				this.container = containerEl;
			}
			if (!this.container) {
				window.alert("Error finding container for toast " + containerEl);
				return;
			}
			if (typeof (opts) === "string" || typeof (opts) === "number") {
				opts = {
					message: opts
				};
			}
			this.addCssClass = opts.addCssClass ? opts.addCssClass : "";
			this.message = opts.message || "";
			this.delay=opts.delay||this.delay;
			this.position=opts.position||"tc";
			this.addCssClass+=" "+this.position;
			this.type=opts.type||"";
			//Check if the container exists
			this.container=$(this.container);
			if(this.container.find(".afToastContainer").length===0)
			{
				this.container.append("<div class='afToastContainer'></div>");
			}
			this.container=this.container.find(".afToastContainer");
			this.container.removeClass("tr br tl bl tc bc").addClass(this.addCssClass);
			if(opts.autoClose===false)
				this.autoClose=false;
			this.show();
		};

		Toast.prototype = {
			addCssClass: null,
			message: null,
			delay:5000,
			el:null,
			container:null,
			timer:null,
			autoClose:true,
			show: function () {
				var self = this;
				var markup = "<div  class='afToast "+this.type+"'>"+
							"<div>" + this.message + "</div>"+
							"</div>";
				this.el=$(markup).get(0);
				this.container.append(this.el);
				var $el=$(this.el);
				var height=this.el.clientHeight;
				$el.addClass("hidden");
				setTimeout(function(){
					$el.css("height",height);
					$el.removeClass("hidden");
				},20);
				if(this.autoClose){
					this.timer=setTimeout(function(){
						self.hide();
					},this.delay);
				}
				$el.bind("click",function(){
					self.hide();
				});
			},

			hide: function () {
				var self = this;
				clearTimeout(this.timer);
				$(this.el).unbind("click").addClass("hidden");
				$(this.el).css("height","0px");
				if(!$.os.ie&&!$.os.android){
					setTimeout(function () {
						self.remove();
					}, 300);
				}
				else
					self.remove();
			},

			remove: function () {
				var $el = $(this.el);
				$el.remove();
			}
		};
		return Toast;
	})();


	$.afui.toast=function(opts){
		$(document.body).toast(opts);
	};

	$.afui.registerDataDirective("[data-toast]",function(item){
		var $item=$(item);
		var message=$item.attr("data-message")||"";
		if(message.length===0) return;
		var position=$item.attr("data-position")||"tr";
		var type=$item.attr("data-type");
		var autoClose=$item.attr("data-auto-close")==="false"?false:true;
		var delay=$item.attr("data-delay")||0;
		var opts={
			message:message,
			position:position,
			delay:delay,
			autoClose:autoClose,
			type:type
		};
		$(document.body).toast(opts);
	});

})(jQuery);

// 异步加载的回调函数
function PageAjaxLoad (Title, URL) {
	setTimeout(function () {
		history.replaceState(null, Title, URL);
		document.title = Title;
		$.afui.setTitle(Title);
		slideout.close();
		if (document.getElementById("ReturnUrl") != null) {
			document.getElementById("ReturnUrl").value = URL;
		};
	}, 1);
}

//非阻塞的带样式的Alert
function CarbonAlert(Message) {
	$.afui.popup(Message);
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
		$.afui.toast({
			message:Lang["New_Message"].replace("{{NewMessage}}", NewMessageNumber),
			position:"tc",
			autoClose:true, //have to click the message to close
			type:"success"
		});
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
			TargetTag.innerText = Json.Message;
		} else {
			TargetTag.innerText = Json.ErrorMessage;
		}
	}
}

//管理
function Manage(ID, Type, Action, NeedToConfirm, TargetTag) {
	if(NeedToConfirm){
		$.afui.popup({
			title: Lang['Confirm'],
			message: Lang['Confirm_Operation'],
			cancelText: Lang['Cancel'],
			cancelCallback: function() {
				//console.log("cancelled");
			},
			doneText: Lang['Confirm'],
			doneCallback: function() {
				TargetTag.innerText = "Loading";
				var CallbackObj = new ManageCallback(TargetTag);
				$.ajax({
					url: WebsitePath + "/manage",
					data: {
						ID: ID,
						Type: Type,
						Action: Action
					},
					dataType: "json",
					type: "POST",
					success: CallbackObj.Success
				});
			},
			cancelOnly: false
		});
	}else{
		TargetTag.innerText = "Loading";
		var CallbackObj = new ManageCallback(TargetTag);
		$.ajax({
			url: WebsitePath + "/manage",
			data: {
				ID: ID,
				Type: Type,
				Action: Action
			},
			dataType: "json",
			type: "POST",
			success: CallbackObj.Success
		});
	}
}

//回复某人
function Reply(UserName, PostFloor, PostID, FormHash, TopicID) {
	$.afui.loadContent(
		"#Reply", 
		false, 
		false, 
		"up-reveal",
		document.getElementById('mainview')
	);
	$("#ReplyViewTitle").text(Lang['Reply_To'] + "#" + PostFloor + " @" + UserName + " :");
	var TempHTML = "<label class=\"button block\" style=\"cursor: pointer;\"><i class=\"icon picture\"></i>";
	TempHTML += "<input type=\"file\" id=\"upfile\" onchange=\"javascript:UploadPicture('Content" + TopicID +"');\" accept=\"image/*\" style=\"display:none;\" />";
	TempHTML += "</label>";
	TempHTML += "<div class=\"input-group\" style=\"width:100%;\"><textarea id=\"Content" + TopicID +"\" rows=\"10\"></textarea></div>";
	$("#ReplyViewHTML").html(TempHTML);
	$("#ReplyViewCancelButton").text(Lang['Cancel']);
	$("#ReplyViewSubmitButton").text(Lang['Reply']);
	$("#ReplyViewSubmitButton").unbind('click');
	$("#ReplyViewSubmitButton").click(function() {
		if ($("Content" + TopicID).val()) {
			CarbonAlert(Lang['Content_Empty']);
		} else {
			$.afui.toast(Lang['Replying']);
			var MarkdownConverter = new showdown.Converter(),
			PreContent =  (PostFloor==0) ? "" : Lang['Reply_To'] + "[#" + PostFloor + "](/t/"+TopicID+"#Post"+PostID+") @" + UserName + " :\n\n",
			Content = MarkdownConverter.makeHtml(PreContent + $("#Content" + TopicID).val());
			$.ajax({
				url: WebsitePath + "/reply",
				data: {
					FormHash: FormHash,
					TopicID: TopicID,
					Content: Content
				},
				type: "post",
				dataType: "json",
				success: function(Result) {
					//TODO：删除Toast
					if (Result.Status == 1) {
						console.log(Result);
						$.afui.goBack();
						$.afui.loadContent(
							WebsitePath + "/t/" + Result.TopicID + (Result.Page > 1 ? "-" + Result.Page: ""), 
							false, 
							false, 
							"slide",
							document.getElementById('mainview')
						);
						//$("#ReplyViewSubmitButton").attr("href", WebsitePath + "/t/" + Result.TopicID + (Result.Page > 1 ? "-" + Result.Page: ""));
					} else {
						CarbonAlert(Result.ErrorMessage);
					}
				},
				error: function() {
					CarbonAlert(Lang['Submit_Failure']);
				}
			});
		}
	});
}

//回复某人
function Search() {
	$.afui.popup({
		title: "Search",
		message: '<input type="text" id="SearchInput" />',
		cancelText: Lang['Cancel'],
		cancelCallback: function() {
		},
		doneText: Lang['Confirm'],
		doneCallback: function() {
			if($("#SearchInput").val()) {
				$.afui.loadContent(
					WebsitePath + '/search/'+$("#SearchInput").val(), 
					false, 
					false, 
					'slide',
					document.getElementById('mainview') //Hack to allow passing in no anchor
				);
				return true;
			}else{
				return false;
			}
		},
		cancelOnly: false
	});
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

//渲染主题
function TopicParse() {
	loadScript(WebsitePath + "/static/editor/ueditor.parse.min.js", function(){
		//强制所有链接在新窗口中打开
		var AllPosts = document.getElementsByClassName("card-content");
		PostContentLists = {};//Global
		//console.log(PostContentLists);
		for (var j=0; j<AllPosts.length; j++) {
			PostContentLists[document.getElementsByClassName("card-content")[j].id] = trim3(document.getElementsByClassName("card-content")[j].childNodes[0].innerHTML);
			//console.log(PostContentLists);
			var AllLinks = AllPosts[j].getElementsByTagName("a");
			for(var i=0; i<AllLinks.length; i++)
			{
				var a = AllLinks[i];
				//console.log(a);
				if(a.host != location.host || a.href.indexOf("upload/") != -1){
					a.setAttribute("target","_blank");
					a.setAttribute("data-ignore","true");
				}
			};
		};
		//样式渲染需最后进行
		uParse('.card-content',{
			'rootPath': WebsitePath + '/static/editor/',
			'liiconpath':WebsitePath + '/static/editor/themes/ueditor-list/'//使用 '/' 开头的绝对路径
		});
	});
}



//上传图片
function UploadPicture(TextareaID) {
	// https://developer.mozilla.org/en-US/docs/Web/API/FormData/Using_FormData_Objects
	//$("#upfile").click();
	//if($('#upfile')[0].files[0]){
		$.afui.toast("Uploading……");
		var UploadData = new FormData();
		UploadData.append('upfile', $('#upfile')[0].files[0]);  
		$.ajax({  
			url: WebsitePath + "/upload_controller?action=uploadimage",  
			type: 'POST',  
			data: UploadData,
			dataType: 'json',
			processData: false,  // 告诉jQuery不要去处理发送的数据  
			contentType: false  // 告诉jQuery不要去设置Content-Type请求头  
			}).done(function(JSON) {
				console.log(TextareaID);
				if (JSON.state == "SUCCESS") {
					$("#"+TextareaID).val($("#"+TextareaID).val() + "\n![" + JSON.original + "](" + JSON.url + ")\n");
				} else {
					CarbonAlert(JSON.state);
				}
		});
		return true;
	//}else{
	//	return false;
	//}
}

/* Init Appframework*/
if (! ((window.DocumentTouch && document instanceof DocumentTouch) || 'ontouchstart' in window)) {
	var script = document.createElement("script");
	script.src = WebsitePath + "/static/js/appframework.desktopBrowsers.js";
	var tag = $("head").append(script);
}
$.afui.overlayStatusbar = false; // for ios7 only to add header padding to overlay the statusbar
$.afui.autoLaunch = true; //By default, it is set to true and you're app will run right away.  We set it to false to show a splashscreen
$.afui.useOSThemes = false; //This must be set before $(document).ready() triggers;
$.afui.isAjaxApp = true;
$.afui.useAjaxCacheBuster = false;
$.afui.loadDefaultHash=false;
//This function runs when the body is loaded.

$(document).ready(function() {
	$.afui.launch();
	$.ajaxSetup({
		cache: false
	});
	slideout = new Slideout({
		'panel': document.getElementsByClassName('pages')[0],
		'menu': document.getElementById('menu'),
		'padding': 200,
		'tolerance': 70
	});
});
