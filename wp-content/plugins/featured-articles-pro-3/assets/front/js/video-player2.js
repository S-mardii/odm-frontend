!function(a){a.fn.fa_video=function(b){if(0==this.length)return!1;if(this.length>1)return this.each(function(c,d){a(d).fa_video(b)}),this;var c,d={onLoad:function(){},onPlay:function(){},onStop:function(){},onPause:function(){}},e=this,b=a.extend({},d,b),f=!1,g=function(){if(!f)switch(a(e).data("source")){case"youtube":f=a(e).youtubeVideo({onLoad:k,onPlay:l,onStop:m,onPause:n});break;case"vimeo":f=a(e).vimeoVideo({onLoad:k,onPlay:l,onStop:m,onPause:n});break;default:var b=a(e).data("source")+"Video";a.fn[b]?f=a.fn[b].call(e,{onLoad:k,onPlay:l,onStop:m,onPause:n}):console&&console.warn('No implementation for video source "'+e.data("source")+'".')}return h(),a(window).resize(h),e},h=function(){var b,c=a(e).width();switch(a(e).data("aspect")){case"16x9":default:b=9*c/16;break;case"4x3":b=3*c/4}a(e).css({height:Math.floor(b)})},i=function(a){c=a},j=function(){return c},k=function(){i(1),b.onLoad.call(e,c)},l=function(){i(2),b.onPlay.call(e,c)},m=function(){i(4),b.onStop.call(e,c)},n=function(){i(3),b.onPause.call(e,c)},o=function(){f.play(),i(2)},p=function(){f.pause(),i(3)},q=function(){f.stop(),i(4)};return this.play=function(){o()},this.pause=function(){p()},this.stop=function(){q()},this.getStatus=function(){return j()},this.resize=function(){h()},g()}}(jQuery),function(a){var b=!1;a.fn.youtubeVideo=function(c){if(0==this.length)return!1;if(this.length>1)return this.each(function(b,d){a(d).youtubeVideo(c)}),this;var d={onLoad:function(){},onPlay:function(){},onStop:function(){},onPause:function(){}},e=this,c=a.extend({},d,c),f=!1,g=function(){return b?h():a(window).on("youtubeapiready",function(){h()}),i(),e},h=function(){e.append("<div/>");var a={enablejsapi:1,rel:0,showinfo:0,showsearch:0,modestbranding:e.data("modestbranding")||0,iv_load_policy:e.data("iv_load_policy")||0,autohide:e.data("autohide")||0,controls:e.data("controls")||0,fs:e.data("fullscreen")||0,loop:e.data("loop")||0};f=new YT.Player(e.children(":first")[0],{height:"100%",width:"100%",videoId:e.data("video_id"),playerVars:a,events:{onReady:function(){c.onLoad.call(e),j()},onStateChange:function(a){switch(window.parseInt(a.data,10)){case 0:c.onStop.call(e);break;case 1:c.onPlay.call(e);break;case 2:c.onPause.call(e)}}}})},i=function(){if(!b){b=!0;var c=document.createElement("script"),d=document.getElementsByTagName("script")[0];c.async=!0,c.src=document.location.protocol+"//www.youtube.com/iframe_api",d.parentNode.insertBefore(c,d),window.onYouTubeIframeAPIReady=function(){a(window).trigger("youtubeapiready")}}},j=function(){f.setVolume(e.data("volume"))};return this.play=function(){f.playVideo()},this.pause=function(){f.pauseVideo()},this.stop=function(){f.stopVideo()},g()}}(jQuery),function(a){a.fn.vimeoVideo=function(b){if(0==this.length)return!1;if(this.length>1)return this.each(function(c,d){a(d).vimeoVideo(b)}),this;var c,d,e={onLoad:function(){},onPlay:function(){},onStop:function(){},onPause:function(){}},f=this,b=a.extend({},e,b),g=function(){var b=(new Date).getTime();d="vimeo"+a(f).data("video_id")+b;var e={title:f.data("title")||0,byline:f.data("byline")||0,portrait:f.data("portrait")||0,color:f.data("color").replace("#",""),fullscreen:f.data("fullscreen")||0,loop:f.data("loop")||0},g='<iframe src="http://player.vimeo.com/video/'+a(f).data("video_id")+"?api=1&player_id="+d+"&"+a.param(e)+'" id="'+d+'" width="100%" height="100%" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';a(f).append(g);try{c=Froogaloop(f.children(":first")[0]).addEvent("ready",h)}catch(i){}return f},h=function(a){b.onLoad.call(f,{player:c,status:1}),i(),$f(a).addEvent("pause",function(){b.onPause.call(f,{player:c,status:3})}),$f(a).addEvent("finish",function(){b.onStop.call(f,{player:c,status:4})}),$f(a).addEvent("play",function(){b.onPlay.call(f,{player:c,status:2})})},i=function(){var a=window.parseInt(f.data("volume"),10)/100;$f(d).api("setVolume",a)};return this.play=function(){$f(d).api("play")},this.pause=function(){$f(d).api("pause")},this.stop=function(){$f(d).api("unload")},g()}}(jQuery);var Froogaloop=function(){function a(b){return new a.fn.init(b)}function b(a,b,c){if(!c.contentWindow.postMessage)return!1;var d=c.getAttribute("src").split("?")[0],e=JSON.stringify({method:a,value:b});"//"===d.substr(0,2)&&(d=window.location.protocol+d),c.contentWindow.postMessage(e,d)}function c(a){var b,c;try{b=JSON.parse(a.data),c=b.event||b.method}catch(d){}if("ready"!=c||j||(j=!0),a.origin!=k)return!1;var f=b.value,g=b.data,h=""===h?null:b.player_id,i=e(c,h),l=[];return i?(void 0!==f&&l.push(f),g&&l.push(g),h&&l.push(h),l.length>0?i.apply(null,l):i.call()):!1}function d(a,b,c){c?(i[c]||(i[c]={}),i[c][a]=b):i[a]=b}function e(a,b){return b?i[b][a]:i[a]}function f(a,b){if(b&&i[b]){if(!i[b][a])return!1;i[b][a]=null}else{if(!i[a])return!1;i[a]=null}return!0}function g(a){"//"===a.substr(0,2)&&(a=window.location.protocol+a);for(var b=a.split("/"),c="",d=0,e=b.length;e>d&&3>d;d++)c+=b[d],2>d&&(c+="/");return c}function h(a){return!!(a&&a.constructor&&a.call&&a.apply)}var i={},j=!1,k=(Array.prototype.slice,"");return a.fn=a.prototype={element:null,init:function(a){return"string"==typeof a&&(a=document.getElementById(a)),this.element=a,k=g(this.element.getAttribute("src")),this},api:function(a,c){if(!this.element||!a)return!1;var e=this,f=e.element,g=""!==f.id?f.id:null,i=h(c)?null:c,j=h(c)?c:null;return j&&d(a,j,g),b(a,i,f),e},addEvent:function(a,c){if(!this.element)return!1;var e=this,f=e.element,g=""!==f.id?f.id:null;return d(a,c,g),"ready"!=a?b("addEventListener",a,f):"ready"==a&&j&&c.call(null,g),e},removeEvent:function(a){if(!this.element)return!1;var c=this,d=c.element,e=""!==d.id?d.id:null,g=f(a,e);"ready"!=a&&g&&b("removeEventListener",a,d)}},a.fn.init.prototype=a.fn,window.addEventListener?window.addEventListener("message",c,!1):window.attachEvent("onmessage",c),window.Froogaloop=window.$f=a}();