$(document).ready(function() { 

	yn3.init();

	
});
var yn3 = {


	map:null,
	geocoder:null,
	marker_img:null,
	crewMarkers:[],
	countryMarkers:[],
	crewMarkerCluster:null,
	jsonData:null,
	infoWindow:null,
	scrollTop:405,
	init:function() { 
		
		yn3.configMap();
		yn3.getPins();
		
		$(".country-list li").click(function() { 
			
			
			yn3.handleCountryClick(this);
			
			
		}).hover(
				function() { 
					
					$(this).addClass('over');
					
				},
				function() {
					
					$(this).removeClass('over');
					
				}
		).find('a').click(function() { 
			
			return false;
			
		});
		
		///crew list
		
		$('.crew-list li').click(function() { 
			
			yn3.handleCrewClick(this);
			
		}).hover(
			function() { 
				
				$(this).addClass("over");
				
			},
			function() { 
				
				$(this).removeClass("over");
				
			}
		);
		
		$('.view-all-button').hover(
			function() {
				
				$(this).addClass('view-all-button-over');
				
			},
			function() {
				
				$(this).removeClass('view-all-button-over');
				
			}
		).click(function() {
			
			yn3.showAllCrews();
			
		}).hide();
		
		
		//boot strap it biatch
		$(window).hashchange(function() { 
			
			yn3.handleHash();
			
		});
		
		setTimeout(function() {
			
			yn3.handleHash(true);
			
		},250);
		
	},
	handleHash:function() { 
		
		var h = location.hash;
		
		var docReady = arguments[0] || false;
		
		switch(true) {
		
			case /^#country/.test(h):
				
				var sp = h.split(":",2);
				yn3.loadCountry(sp[1]);
			
			break;
			case /^#crew/.test(h):
				
				if(docReady) {
				var sp = h.split(":",2);
					yn3.viewCrew(Base64.decode(sp[1]));
				}
				
			break;
		}
		
	},
	getPins:function() {

		$.ajax({
			
			dataType:'json',
			url:'/younited-nations-3/ajax_get_crews',
			success:function(d) {
			
				//var t = prettyPrint(d,{maxArray: 5,maxDepth:5});
				
				//$('body').append(t);
				
				yn3.jsonData = d;
				yn3.configMap();
				yn3.configCrewPins();
				yn3.placeCrewPins();
				yn3.handleHash();
			}
			
		});
		
	},
	configMap:function() {
		

		yn3.geocoder = new google.maps.Geocoder();
	    var latlng = new google.maps.LatLng(-34.397, 150.644);
	    var lat = new google.maps.LatLng(34.0522342,-102.2436849);
		var myOptions = {
			      zoom:2,
			      mapTypeId: google.maps.MapTypeId.HYBRID,
			      center:lat
		};
		
		
		yn3.map = new google.maps.Map(document.getElementById("map"),myOptions);
		yn3.marker_img = new google.maps.MarkerImage("/theme/younited-nations-3/img/vans_pin.png");
		
		var infoOps = {
				boxClass:'yn3-info-window',
               disableAutoPan: false
               ,maxWidth: 0
               ,pixelOffset: new google.maps.Size(-182.5, 0)
               ,zIndex: null
               ,boxStyle: { 
                 
                }
               ,closeBoxMargin: "10px 2px 2px 2px"
               ,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
               ,infoBoxClearance: new google.maps.Size(1, 1)
               ,isHidden: false
               ,pane: "floatPane"
               ,enableEventPropagation: true
       };
		
		yn3.infoWindow = new InfoBox(infoOps);
		
	},
	configCrewPins:function() {
		
		var d = arguments[0] || yn3.jsonData;
		
		for(var a in d.entries) {

			var p = d.entries[a].YounitedNationsPosse;
			
			var m = new google.maps.Marker({
				
				map:null,
				icon:yn3.marker_img,
				position:new google.maps.LatLng(p.geo_latitude,p.geo_longitude),
				title:p.name
				
			});
	
			//alert(marker.getId());
			
			m.set("posse_id",p.id);
			
			google.maps.event.addListener(m,'click',(function(m,p) { 

				return function() {
					
					yn3.infoWindow.setContent("<div class='inner'>"+p.name.toUpperCase()+"</div>");
					yn3.infoWindow.open(yn3.map,m);
					
					if(yn3.map.getZoom()<12) {
						
						yn3.map.setZoom(12);
						yn3.map.setCenter(m.getPosition());
						
					}
					
					yn3.viewCrew(p.id);
					
				};
				
			})(m,p));
			
			yn3.crewMarkers.push(m);
			
		}
		
		
	},
	placeCrewPins:function() {

		yn3.crewMarkerCluster = new MarkerClusterer(yn3.map,yn3.crewMarkers,{
			
			gridSize:20,
			maxZoom:null,
			minimumClusterSize:8
			
		});
		//yn3.crewMarkerCluster.fitMapToMarkers();
		yn3.crewMarkerCluster.repaint();
	},
	removeCrewPins:function() {
		
		for(var i =0;i<yn3.crewMarkers.length;i++) {
		
			yn3.crewMarkers[i].setMap(null);
			
		}
		
	},
	getGeoData:function(str,callback) {
	
		var ops = {
				
			data:{
			
				"data":{
			
					"key":str
			
				}
			
			},
			url:"/younited-nations-3/get_geo_cache",
			success:function(d) {
				
				if(d.lat == undefined || d.lng == undefined) {
					
					yn3.googleGeoCoder(str,callback);
					
				} else {
					
					callback.apply(this,[d,str]);
					
				}
				
			},
			type:"post",
			dataType:"json"
				
		};
		
		$.ajax(ops);
		
		
	},
	handleCountryClickGeoData:function(data,str) {
		
	
		
		$(window).scrollTo(yn3.scrollTop,"normal");
	
		//yn3.map.panToBounds(new google.maps.LatLngBounds(new google.maps.LatLng(data.viewport.southwest.lat,data.viewport.southwest.lng),new google.maps.LatLng(data.viewport.northeast.lat,data.viewport.northeast.lng)));
			
		yn3.map.setCenter(new google.maps.LatLng(data.lat,data.lng));
		
		var GLOBE_WIDTH = 420; 
		var west = data.bounds.southwest.lng;
		var east = data.bounds.northeast.lng;
		var angle = east - west;
		if (angle < 0) {
		  angle += 360;
		}
		var zoom = Math.round(Math.log(700 * 360 / angle / GLOBE_WIDTH) / Math.LN2);
		
		yn3.map.setZoom(zoom);
		
		yn3.infoWindow.close();
		
	},
	handleCountryClick:function(scope) {
		
		var hash = $(scope).find('a').attr('href');
		
		var c = $(scope).attr("country");
		
		location.hash = "country:"+$(scope).find('span:eq(0)').text();
		
		if(c) {
		
			yn3.filterCrewsByCountry(c);
			
		}
		
	},
	loadCountry:function(str) {
		
		yn3.getGeoData(str, yn3.handleCountryClickGeoData);
		
		$('.crew-content').html(' ');

	},
	googleGeoCoder:function(str,callback) {
		
		
		yn3.geocoder.geocode( { 'address': str}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				
					//results[0].geometry.location
				
					var data = {
							'lat':results[0].geometry.viewport.getCenter().lat(),
							'lng':results[0].geometry.viewport.getCenter().lng(),
							'viewport':{
								
								'southwest':{
									'lat':results[0].geometry.viewport.getSouthWest().lat(),
									'lng':results[0].geometry.viewport.getSouthWest().lng()
								},
								'northeast':{
									'lat':results[0].geometry.viewport.getNorthEast().lat(),
									'lng':results[0].geometry.viewport.getNorthEast().lng()
								}
								
							},
							'bounds':{
								
								'southwest':{
									'lat':results[0].geometry.bounds.getSouthWest().lat(),
									'lng':results[0].geometry.bounds.getSouthWest().lng()
								},
								'northeast':{
									'lat':results[0].geometry.bounds.getNorthEast().lat(),
									'lng':results[0].geometry.bounds.getNorthEast().lng()
								}
								
							}
					};

				
					//fire the callback method
				
					callback.apply(this,[data,str]);
				
					//send the results to the server to be cached
					
					var ops = {
							
							"url":"/younited-nations-3/set_geo_cache",
							type:"post",
							"data":{
							
								"data":{
						
									"key":str,
									"val":data
							
								}
							
							}
							
					};
					
					$.ajax(ops);
				
		      } else {
			      
		        	alert("Geocode was not successful for the following reason: " + status);
		        	//$("#YounitedNationsPosseCityStatePostal").parent().find('label').removeClass('green-label-check').addClass("red-label-x");
		        
		      }
		});
	},
	viewCrew:function(posse_id) { 

		//load the crew info
		$('.crew-content').html("<div class='loading-msg'>Loading....</div>");

		$.ajax({
			
			url:'/younited-nations-3/ajax_get_crew/'+posse_id,
			dataType:'html',
			success:function(d) {
				
				$('.crew-content').html(d);
				$(window).scrollTo(yn3.scrollTop,'normal');
				
			}
			
		});
		
		
	},
	returnPinByPosseId:function(id) {
		for(var a in yn3.crewMarkers) {
			
			var p = yn3.crewMarkers[a];
			
			if(p.posse_id == id) {
				
				return p;
			}
			
		}
		return false;
	},
	moveToPin:function(id) {
	
		
		
		
	},
	filterCrewsByCountry:function(country) {
		
		$('.crew-list li').hide();
		
		$(".crew-list li[country="+country+"]").show();
		
		$('.view-all-button').show();
		
	},
	showAllCrews:function() {
		
		$('.crew-list li').show();
		
		$('.view-all-button').hide();
		
	},
	handleCrewClick:function(scope) {
	
		location.hash = "crew:"+Base64.encode($(scope).attr('posse_id'));
		
		var id = $(scope).attr("posse_id");
		
		yn3.viewCrew(id);
		
		var pin = yn3.returnPinByPosseId(id);
		
		google.maps.event.trigger(pin,'click');
		
	}
	,handleCrewHash:function(posse_id) {
		
		
		
	}
	
	
	
	
	
};