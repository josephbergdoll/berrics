<?php 

$this->Unified->mapJsIncludes();

$this->Html->css("store_profile","stylesheet",array("inline"=>false));

//set the page title

$pt = "THE BERRICS UNIFIED - {$store['UnifiedStore']['shop_name']}";

$this->set("title_for_layout",$pt);

?>
<script type="text/javascript">

var geocoder = false;
var map = false;
var startLng = '<?php echo $store['GeoLocation']['lng']; ?>';
var startLat = '<?php echo $store['GeoLocation']['lat']; ?>';
var markers = [];
jQuery(document).ready(function($) {
	var latLng = new google.maps.LatLng(startLat, startLng);
 	var mapOptions = {
          center: latLng,
          zoom: 15,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);
	
	//drop an initial pin
	addMarker(latLng);
	
	geocoder = new google.maps.Geocoder();
	//bind some of the buttons

	$("#show-on-map-btn").bind('click',function(e) { 

		showAddressOnMap();

	});


	///////
	//state and country drop downs
	/////

	//get all the opt groups in an object
	$("#UnifiedStoreState optgroup").each(function() { 

		ship_to_states[$(this).attr("label")]=$(this).html();
		
	});


	$("#UnifiedStoreCountryCode").change(function() { 

		shipChangeState();

	});
	//$("#ShippingAddressCountry").change();
	shipChangeState();
	

	//tabs
	$("#profile-tab-nav li.tab-b").click(function() { 

		$('.tab-container').hide();

		var token = $(this).attr('data-tab');

		//show the tab

		$('.tab-container#'+token).show();

		$('#profile-tab-nav li.active').removeClass('active');

		$(this).addClass('active');

	});

	$("#profile-tab-nav li.tab-b:first").trigger('click');



	

});

function clearMarkers () {
	
	if(markers.length>0) {

		for(var a in markers) {

			markers[a].setMap(null);

		}
	
		//clear the shit!
		markers = [];

	}

}

function addMarker ($latLng) {
	
	var $mark = new google.maps.Marker({
		
			position:$latLng,
			animation:google.maps.Animation.DROP,
			map:map,
			//icon:"/img/v3/unified/marker.png",
			icon:"/theme/unified/img/marker-new.png",
			
			
	});

	markers.push($mark);

}

function setZoom($latLng) {

	var miles = arguments[1] || .1;
	
	var radius = 1609.3 * miles;

	var cOptions = {
			center: $latLng,
		    fillOpacity: 0,
		    strokeOpacity:1,
		    map: map,
		    radius: radius /* 20 miles */
	};

	var cir = new google.maps.Circle(cOptions);

	map.fitBounds(cir.getBounds());

}

function showAddressOnMap () {
	
	var a = $("#UnifiedStoreAddress1").val();

	a += " "+$("#UnifiedStoreAddress2").val();

	a += " "+$("#UnifiedStoreCity").val();

	a += " "+$("#UnifiedStoreState").val();
	
	a += " "+$("#UnifiedStoreZip").val();
	
	var r = {};

	r.address = a;

	geocoder.geocode(r,function($geo,$res) { 
	
		map.setCenter($geo[0].geometry.location);
		setZoom($geo[0].geometry.location);
		//map.setZoom(19);
		clearMarkers();
		addMarker($geo[0].geometry.location);

		//set the lat and long fields
		$("#GeoLocationLat").val($geo[0].geometry.location.lat());
		$("#GeoLocationLng").val($geo[0].geometry.location.lng());
		$("#GeoLocationStreetAddressFormatted").val($geo[0].formatted_address);

		console.log($geo);

	});

}	

var ship_to_states = {};
$(document).ready(function() { 

	
	
	
});


function shipChangeState() {

	var country = $("#UnifiedStoreCountryCode").val();

	//check to see if we have an already selected state or value
	var sel = $("#UnifiedStoreState").val();
	
	if(ship_to_states[country]) {

		
		$("#UnifiedStoreState").html(ship_to_states[country]);
		$("#form-shipping-state-text-div").hide().find('input').attr({"disabled":true});
		$("#form-shipping-state-select-div").show().find('select').attr({"disabled":false});

		//try and set the selected val
		if(sel.length>0) {

			$("#UnifiedStoreState[value="+sel+"]").attr({"selected":"selected"});
			
		}
		
	} else {

		$("#form-shipping-state-select-div").hide().find('select').attr({"disabled":true});
		$("#form-shipping-state-text-div").show().find('input').attr({"disabled":false});
	}
	
}

//tabs stuff


</script>
<?php 

$addr_string = urlencode("{$store['UnifiedStore']['address1']} {$store['UnifiedStore']['address2']} {$store['UnifiedStore']['city']} {$store['UnifiedStore']['state']}");


 ?>
<div id="unified-store-profile">
	<?php echo $this->element("misc/ribbon-heading",array("heading"=>"SHOP PROFILE")); ?>
	<div class="profile-body-container clearfix">
		<div class="top-info clearfix">
			<div class="left clearfix">
				<div class="shop-name">
					<?php echo $store['UnifiedStore']['shop_name']; ?>
				</div>
				<div class="logo">
					<?php if (!empty($store['UnifiedStore']['image_logo'])): ?>
						  	<img src="//img.theberrics.com/i.php?src=/unified-logos/<?php echo $store['UnifiedStore']['image_logo']; ?>&w=100" alt="">
					<?php endif; ?>
					<div class="open-closed">
						<?php if (!$store['StoreOpen']): ?>
							<span class="closed">CLOSED</span>
						<?php else: ?>							
							<span class="open">OPEN</span>
						<?php endif ?>
					</div>
				</div>
				<div class="store-details">
					<div class="street-address">
						<div><?php echo strtoupper($store['UnifiedStore']['address1']);  ?> <?php echo strtoupper($store['UnifiedStore']['address2']);  ?></div>
						<div><?php echo strtoupper($store['UnifiedStore']['city']); ?>, <?php echo strtoupper($store['UnifiedStore']['state']); ?> <?php echo strtoupper($store['UnifiedStore']['zip']); ?></div>
						<div><?php echo strtoupper($store['UnifiedStore']['phone']); ?></div>
					</div>
					<?php if (!empty($store['UnifiedStore']['parking_situation'])): ?>
					<div class="parking-situation">
						PARKING - <?php echo strtoupper($store['UnifiedStore']['parking_situation']); ?>
					</div>
					<?php endif; ?>
					<?php if (!empty($store['UnifiedStore']['store_hours_text'])): ?>
						 <div class="store-hours-text">
							<?php echo nl2br($store['UnifiedStore']['store_hours_text']); ?>
						</div>	 	 
					<?php endif; ?>
					<div class="website-url">
						<a href="<?php echo $store['UnifiedStore']['website_url']; ?>" target='_blank'><?php echo $store['UnifiedStore']['website_url']; ?></a>
					</div>
					<div class="social-networks">
						<?php if (!empty($store['UnifiedStore']['facebook_url'])): ?>
							<a href="<?php echo $store['UnifiedStore']['facebook_url']; ?>" target='_blank'>
								<img src="/theme/unified/img/profile-fb.png" border='0' alt="">
							</a>
						<?php endif; ?>
						<?php if (!empty($store['UnifiedStore']['instagram_handle'])): ?>
							<a href="//instagram.com/<?php echo $store['UnifiedStore']['instagram_handle']; ?>" target='_blank'>
								<img src="/theme/unified/img/profile-instagram.png" border='0' alt="">
							</a>
						<?php endif; ?>
						<?php if (!empty($store['UnifiedStore']['twitter_handle'])): ?>
							<a href="//twitter.com/<?php echo $store['UnifiedStore']['twitter_handle']; ?>" target='_blank'>
								<img src="/theme/unified/img/profile-twitter.png" border='0' alt="">
							</a>
						<?php endif; ?>
					</div>
				</div>
				<div class="map-container clearfix">
					<div id="map_canvas" class='clearfix' style=''></div>
				</div>
				<div class="directions-link clearfix">
					<a href="https://maps.google.com/maps?saddr=current+location&daddr=<?php echo $addr_string; ?>&hl=en" target='_blank'>GET DIRECTIONS &gt;</a>
				</div>
			</div>
			<div class="right">
				<div class="inner">
					<div class="bio">
						<!-- IMAGE -->
						<div class="main-img">
							<?php echo $this->Media->mediaThumb(array(
								"MediaFile"=>$mediaItems['main'][0]['MediaFile'],
								"w"=>700
							)); ?>
						</div>
						<p>
							<?php echo nl2br($store['UnifiedStore']['shop_bio']); ?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id='profile-tab-nav' class='clearfix'>
		<ul>
			<li class='left'></li>
			<li class='tab-b' data-tab='brands'>BRANDS</li>
			<li class='tab-s'></li>
			<li class='tab-b' data-tab='team'>TEAM</li>
			<li class='tab-s'></li>
			<li class='tab-b' data-tab='employees'>EMPLOYEES</li>
			<li class='tab-s'></li>
			<li class='tab-b' data-tab='news'>NEWS</li>
			<li class='right'></li>
		</ul>
	</div>
	<div class="tab-body-container clearfix">
		<!-- BRANDS -->
		<div id="brands" class="tab-container clearfix">
			<?php foreach ($store['UnifiedStoreBrand'] as $k => $v): ?>
				<div class="brand">
					<img src="//img.theberrics.com/i.php?src=/brand-logos/<?php echo $v['Brand']['image_logo'] ?>&h=85" alt="<?php echo $v['Brand']['name']; ?>">
				</div>
			<?php endforeach ?>
		</div>
		<!-- END BRANDS -->

		<!-- EMPLOYEES -->
		<div id="employees" class='tab-container clearfix'>
			<?php foreach ($employees as $k => $v): ?>
			<div class="employee">
				<div class="profile-img">
					<img src="//img.theberrics.com/i.php?src=/unified-employees/<?php echo $v['image_file']; ?>&w=235&h=150&zc=1" alt="">
				</div>
				<div class="name">
					<?php echo $v['name']; ?>
				</div>
				<div class="title">
					<?php echo ucfirst($v['title']); ?>
				</div>
			</div>
			<?php endforeach ?>
		</div>
		<!-- END EMPLOYEES -->

		<!-- TEAM -->
		<div id="team" class="tab-container">
			<?php if (count($team)>0): ?>
				  	 <?php foreach ($team as $k => $v): ?>
					<div class="team">
						<div class="profile-img">
							<img src="//img.theberrics.com/i.php?src=/unified-employees/<?php echo $v['image_file']; ?>&w=235&h=150&zc=1" alt="">
						</div>
						<div class="name">
							<?php echo $v['name']; ?>
						</div>
					</div>
					<?php endforeach ?>
			<?php else: ?>
				No Team
			<?php endif; ?>
		</div>
		<!-- END TEAM -->


		<!-- NEWS -->
		<div id="news" class="tab-container">
			<?php foreach ($news as $k => $v): ?>
			<?php echo $this->element("dailyops/post-bit",array("dop"=>$v,"lazy"=>false)) ?>
			<?php endforeach ?>
		</div>
		<!-- END NEWS -->

	</div>
</div>
