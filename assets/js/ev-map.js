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
		console.log( name );
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