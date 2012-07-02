(function($) { 
	
	
	var methods = {
			
			VAST_TEST_URL:"http://pubads.g.doubleclick.net/gampad/ads?sz=700x394&iu=%2F5885%2Fvast_pre&ciu_szs=&impl=s&gdfp_req=1&env=vp&output=xml_vast2&unviewed_position_start=1&url=%5Breferrer_url%5D&correlator=%5Btimestamp%5D",
			LIMELIGHT_URL: "http://berrics.vo.llnwd.net/o45/",
			init:function(options) {
				
				console.log(Modernizr);
		
				var $this = this;
				
				//populate default data
				var data = $.data($this,{
					
					"target":$this,
					"options":$.extend({
						
						"PosterDomain":"http://img.theberrics.com/video/stills/",
						"PosterImage":"5898cf7f99028ddf37d93c4aba4fc88f.jpg",
						"MediaFileId":"4e98c904-f300-4269-b732-0d39323849cf",
						"LazyLoad":null,
						"VideoWidth":700,
						"VideoHeight":396,
						"PlayCount":0,
						"PlayAction":"PreRoll",
						"PreRoll":null,
						"PostRoll":null,
						"DailyopId":3786,
						"BufferInterval":false
						
						
					},options)
					
				});

				methods.requestVideoData($this);
				
				return this;
				
			},
			returnHtml:function(context) {
				
				var data = $.data(context);
				
				var div = $("<div class='berrics-html-video-div' />").append("<video />");
				
				var controls = $("<div class='controls' />").
								append("<div class='inner' />").find('.inner').
								append("<div class='slider' />").
								append("<div />");
				
				$(div).append(controls);
				
				$(div).css({
					
					"position":"realtive"
					
				}).find("video").css({
					
					"background-color":"#000"
					
				}).attr({
					
					"width":data.options.VideoWidth+"px",
					"height":data.options.VideoHeight+"px"
					
				});
				
				return div;
				
			},
			startBufferTimer:function() { },
			handleTimer:function(context) {
				
				
				
			},
			requestVideoData:function(context) {
				
				var data = $.data(context);
				
				var opt = {
					"type":"post",
					"dataType":"JSON",
					"url":"/media/json_video_service",
					"success":function(d) {
						
						data.options['ServerData'] = d;
						methods.bootstrapVideo(context);
						
					},
					"data":{
						
						"data":{
						
							"json":JSON.stringify({
							
								"media_file_id":data.options.MediaFileId,
								"dailyop_id":data.options.DailyopId
							
							})
						
						}
					}
						
				};
				
				$.ajax(opt);
				
			},
			bootstrapVideo:function(context) {
				
				var data = $.data(context);
				
				if(Modernizr.video.h264.length<=0) {
					
					console.log("H264 Not Supported: Flash Video Fallback");
					
					methods.flashFallBack(context);
					
				} else {
					
					methods.html5VideoPlayer(context);
					
					
				}
			},
			handleBuffer:function(context) {
				
				var data = $.data(context);
				
				var video = data.target.find("video");
				
				var duration = video.get(0).duration;
				
				var buffer_end = video.get(0).buffered.end(0);
				
				var percentBuffered = Math.ceil((buffer_end * 100) / duration);
				
				console.log("Percent Buffered: "+percentBuffered);
				
			},
			handleVideoPlay:function(context) {
			
				var data = $.data(context);
				
				data.target.html(methods.returnHtml(context));
				
				clearInterval(data.options.BufferInterval);
				
				data.options.BufferInterval = setInterval(function() { 
					
					methods.handleBuffer(context);
					
				},500)
				
				
				switch(data.options.PlayAction) {
				
					case "PreRoll":
						if(data.options.ServerData.MediaFile.preroll === null || data.options.ServerData.MediaFile.preroll.strlen<=0) {
							
							console.log("Pre Roll Not Found. Change Action and re-run Method");
							data.options.PlayAction = "Video";
							return methods.handleVideoPlay(context);
							
						} 
						//play the preroll!
						console.log("Play PreRoll");
						methods.loadGoogleAd(context);
						break;
					case "PostRoll":
						break;
					case "Exit":
						
							methods.realtedVideoScreen(context);
							
						break;
					case "Video":
					default:
						
						console.log("HTML5 Video Action: Clear All Event");
				
						console.log(data);
						
						var video = data.target.find("video");
						
						video.unbind();
						video.html('').attr({"src":null});
						
						var video_element = $(video).get(0);
						
						video.attr({
							
							"src":methods.LIMELIGHT_URL + data.options.ServerData.MediaFile.limelight_file,
							"controls":true
						});
						
						video_element.play();
						
						video.bind(
							'ended',function() {
							
								console.log('Video Ended');
								methods.handleVideoEnd(context);
								
							}
						);
					
						
						break;
				}
				
				
				
			},
			handleVideoEnd:function(context) {
				
				var data = $.data(context);
				
				switch(data.options.PlayAction) {
				
					case "PreRoll":
						
						data.options.PlayAction = "Video";
						
						break;
						
					case "Video":
						
						data.options.PlayAction = "Exit";
						
						
						break;
				}
				

				methods.handleVideoPlay(context);
				
			},
			videoRequestComplete:function(context) { 

				methods.handleVideoPlay(context);
				
			},
			html5VideoPlayer:function(context) {

				console.log("Html5 Video Player");
				
				var data = $.data(context);
				
		    	methods.handleVideoPlay(context);
				
			},
			flashFallBack:function(context) {
					
					var data = $.data(context);
				
					var e = data.target;
					$(e).unbind("click");
					
					var swf_width = data.options.VideoWidth || 700;
					var swf_height = data.options.VideoHeight || 400;
					
					var fparams = {
							
						media_file_id: data.options.ServerData["MediaFile"]["id"],
						xid:e.attr("xid"),
						legacy_preroll:data.options.ServerData["MediaFile"]["preroll"],
						legacy_postroll:data.options.ServerData["MediaFile"]["postroll"],
						server:window.location.hostname,
						preroll_server:'LEGACY',
						postroll_server:'LEGACY',
						loader:'/swf/player/BerricsPlayerFallback.swf?rev=John1.7'
							
					};
					
					if(data.options.ServerData["dailyop_id"]) {
						
						fparams.dailyop_id = data.options.ServerData["dailyop_id"];	
					
					}
					
					fparams.override_data = escape(JSON.stringify(data.options.ServerData));
					
					console.log(escape(JSON.stringify(data.options.ServerData)));
					
					var xiSwfUrlStr = "/swf/expressInstall.swf";
					var swfVersionStr = "0.0.0";
					var params = {};
					params.quality = "high";
					params.bgcolor = "#000000";
					params.play = "true";
					params.loop = "true";
					params.wmode = "gpu";
					params.scale = "noscale";
					params.menu = "true";
					params.devicefont = "false";
					params.salign = "";
					params.allowscriptaccess = "always";
					params.allowFullScreen = "true";
					var attributes = {};
					attributes.id = "BerricsPlayerLoader";
					attributes.name = "BerricsPlayerLoader";
					attributes.align = "left";
					
					swfobject.embedSWF(
							"/swf/player/BerricsPlayerLoader.swf?testing", $(e).attr("id"),
							swf_width, swf_height,
							swfVersionStr, xiSwfUrlStr,
							fparams, params, attributes);
					
				
			},
			loadGoogleAd:function(context) {
				
				var data = $.data(context);
				var adsLoader = new google.ima.AdsLoader();
				adsLoader.addEventListener(
				    google.ima.AdsLoadedEvent.Type.ADS_LOADED,
				    function(e) {
				    	
				    	var adsManager = e.getAdsManager();
				    	
				    	console.log("Google Adsmanager Start");
				    	
				    	//handle the end of the ad
				    	adsManager.addEventListener(
				    			 google.ima.AdEvent.Type.COMPLETE,
				    			function(ee) {
				    				
				    				console.log("Ad Completed Playing");
				    				methods.handleVideoEnd(context);
				    				
				    			},
				    			false
				    	);
				    	
				    	//bootstrap that bitch
				    	adsManager.play(data.target.find("video").get(0));
				    	
				    	
				    	
				    },
				    false);
				adsLoader.addEventListener(
				    google.ima.AdErrorEvent.Type.AD_ERROR,
				    function(e) { 
				    	
				    	console.log("Google Video Ad Error: ");
				    	console.log(e.getError());
				    	
				    },
				    false);
				 adsLoader.requestAds({
					  adTagUrl: methods.VAST_TEST_URL,
					  adType: "video"
					});
				 
				 console.log(adsLoader);
				
			},
			realtedVideoScreen:function(context) {
				
				var data = $.data(context);
				
				$.ajax({
					
					"url":"/dailyops/related/"+data.options.DailyopId,
					"success":function(d) {
						
						var ele = data.target;
					
						$(ele).html(d);
						
						$(ele).find(".icon").css({"opacity":.5});
						
						methods.destroy(context);
						
						//make the replay button clickable
						
						format_berricsRelatedVideoScreen();
					}
					
				});
				
			},
			destroy:function(context) { 
				
				var data = $.data(context);
				
				clearInterval(data.options.BufferInterval);
				
				$.removeData(context);
				
			}
			
			
	};
	
	
	$.fn.BerricsHtmlVideo = function(method) {
	
		if ( methods[method] ) {
			
	      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
	      
	    } else if ( typeof method === 'object' || ! method ) {
	    	
	      return methods.init.apply( this, arguments );
	      
	    } else {
	    	
	      $.error( 'Method [' +  method + '] does not exist' );
	      
	    }    
		
	};
	

})(jQuery);