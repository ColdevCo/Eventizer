<style>
div.details,
div.location {
	display: inline-block;
}
div.details {
	margin-right: 8px;
}
div.input-group {
	margin-bottom: 5px;
}
div.input-group > label {
	display: inline-block;
	width: 180px;
	vertical-align: top;
}
div.input-group > input,
div.input-group > textarea,
div.input-group > .google-map {
	display: inline-block;
	margin: 0;
}
div.input-group > textarea {
	width: 400px;
	height: 80px;
}
div.input-group > .google-map {
	width: 400px;
	height: 200px;
}
div.input-group > input[type="text"] {
	width: 150px;
}
</style>

<div class="input-group">
	<label for="ev_allday">All day event?</label>
	<input type="checkbox" name="ev_allday" />
</div>

<div class="input-group">
	<label>Start date &amp; time</label>
	<input type="text" class="datetimepicker" />
</div>

<div class="input-group">
	<label>End date &amp; time</label>
	<input type="text" class="datetimepicker" />
</div>

<div class="input-group">
	<label>Location</label>
	<textarea></textarea>
</div>

<div class="input-group">
	<label>&nbsp;</label>
	<div id="map-canvas" class="google-map" data-name='ev_map' data-marker=','></div>
	<input type='hidden' name='ev_map-lat' value='' />
	<input type='hidden' name='ev_map-lng' value='' />
</div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key="></script>
<script type="text/javascript">
	var map;
	var markers = [];

	function initialize() {
		var mapOptions = {
			center: new google.maps.LatLng( -34.397, 150.644 ),
			zoom: 16,
			scrollwheel: false
		};
		map = new google.maps.Map(document.getElementById( "map-canvas" ), mapOptions);

		if ( jQuery( '#map-canvas' ).attr( 'data-marker' ) !== ',' ) {
			var markerLat = jQuery( '#map-canvas' ).attr( 'data-marker' ).split(',')[0] || -34.397;
			var markerLng = jQuery( '#map-canvas' ).attr( 'data-marker' ).split(',')[1] || 150.644;
			var markerLocation = new google.maps.LatLng( markerLat, markerLng );

			add_marker( markerLocation );
			map.setCenter( markerLocation );
		}

		google.maps.event.addListener(map, 'click', function(event) {
			clear_markers();
			add_marker( event.latLng );

			var name = jQuery( '#map-canvas' ).attr( 'data-name' );
			jQuery( 'input[name=' + name + '-lat]' ).val( event.latLng.lat() );
			jQuery( 'input[name=' + name + '-lng]' ).val( event.latLng.lng() );
		});
	}

	function add_marker( location ) {
		var marker = new google.maps.Marker({
				position: location,
				map: map
		});
		markers.push( marker );
	}

	function clear_markers() {
		for (var i = 0; i < markers.length; i++) {
			markers[i].setMap( null );
		}

		markers = [];
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>