<style type="text/css">
    div.details {
        margin-right: 8px;
    }

    .hidden {
        display: none !important;
    }

    div.input-group {
        margin: 0 5px 10px;
    }

    div.input-group.first {
        margin: 40px 5px 10px;
    }

    div.input-group.last {
        margin: 0px 5px 40px;
    }

    hr.input-divider {
        margin: 40px 5px;
    }

    .datepicker {
        width: 175px;
        margin-right: 15px;
    }

    label.left {
        display: inline-block;
        width: 180px;
        vertical-align: top;
    }

    label.input-ev_allday {
        margin-right: 30px;
    }

    .input-ev_start_time,
    .input-ev_end_time,
    .google-map {
        display: inline-block;
    }

    #ev_venue_name,
    #ev_venue_address,
    .google-map {
        width: 400px;
    }

    #ev_venue_address {
        height: 80px;
    }

    .google-map {
        height: 200px;
    }
</style>

<div class="input-group first">

    <?= HTML::label('All day event?', 'ev_allday', array('class' => 'label-ev_allday left')); ?>

    <?= HTML::radio('Yes', 'ev_allday', array('class' => 'input-ev_allday')); ?>
    <?= HTML::radio('No', 'ev_allday', array('class' => 'input-ev_allday', 'checked' => true)); ?>

</div>

<div class="input-group">

    <?= HTML::label('Start date &amp; time', 'ev_start_date', array('class' => 'label-ev_start_date left')); ?>

    <?= HTML::datepicker('ev_start_date', array('class' => 'datepicker', 'id' => 'ev_start_date')); ?>
    <?= HTML::timepicker('ev_start_time', array('class' => 'timepicker input-ev_start_time')); ?>

</div>

<div class="input-group">

    <?= HTML::label('End date &amp; time', 'ev_end_date', array('class' => 'label-ev_end_date left')); ?>

    <?= HTML::datepicker('ev_end_date', array('class' => 'datepicker', 'id' => 'ev_end_date')); ?>
    <?= HTML::timepicker('ev_end_time', array('class' => 'timepicker input-ev_end_time')); ?>
</div>

<hr class="input-divider" />

<div class="input-group">

    <?= HTML::label('Venue Name', 'ev_venue_name', array('class' => 'label-ev_venue_name left')); ?>
    <?= HTML::text('ev_venue_name', array('id' => 'ev_venue_name')); ?>

</div>

<div class="input-group">

    <?= HTML::label('Venue Address', 'ev_venue_address', array('class' => 'label-ev_venue_address left')); ?>
    <?= HTML::textarea('ev_venue_name', array('id' => 'ev_venue_address')); ?>

</div>

<div class="input-group last">

    <?= HTML::label('Venue Location', 'ev_venue_location', array('class' => 'label-ev_venue_location left')); ?>
    <?= HTML::geoinput('ev_map', array('class' => 'google-map', 'data-name' => 'ev_map', 'data-marker' => ',')) ?>

</div>

<script type="text/javascript">

    jQuery(document).ready(function(){

        // Event date script
        jQuery('.input-ev_allday').bind('change', function(){
            jQuery('.input-ev_start_time').toggleClass('hidden');
            jQuery('.input-ev_end_time').toggleClass('hidden');
        });

        jQuery('#ev_start_date').datepicker({
            dateFormat: 'd MM yy',
            onClose: function( selectedDate ) {
                jQuery('#ev_end_date').datepicker( "option", "minDate", selectedDate );
            }
        });

        jQuery('#ev_end_date').datepicker({
            dateFormat: 'd MM yy',
            onClose: function( selectedDate ) {
                jQuery('#ev_start_date').datepicker( "option", "maxDate", selectedDate );
            }
        });

        jQuery('#ev_venue_address').change(function(){

        });

        google.maps.event.addDomListener(window, 'load', include_google_map_script());
    });

    function include_google_map_script()
    {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3&sensor=true&callback=init_map';
        document.body.appendChild(script);
    }

    function init_map()
    {
        var map;
        var marker = [];

        var add_marker = function(location) {
            var marker = new google.maps.Marker({
                position: location,
                map: map
            });
            markers.push( marker );
        }

        var clear_markers = function() {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap( null );
            }

            markers = [];
        }

        var mapOptions = {
            center: new google.maps.LatLng( -34.397, 150.644 ),
            zoom: 10,
            scrollwheel: false
        };
        map = new google.maps.Map(document.getElementById( "map-ev_map" ), mapOptions);

        if(!!navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var geolocate = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                add_marker( geolocate );
                map.setCenter(geolocate);
                map.setZoom(16);
            });
        }

        if ( jQuery( '#map-ev_map' ).attr( 'data-marker' ) !== ',' ) {
            var markerLat = jQuery( '#map-ev_map' ).attr( 'data-marker' ).split(',')[0] || -34.397;
            var markerLng = jQuery( '#map-ev_map' ).attr( 'data-marker' ).split(',')[1] || 150.644;
            var markerLocation = new google.maps.LatLng( markerLat, markerLng );

            add_marker( markerLocation );
            map.setCenter( markerLocation );
            map.setZoom(16);
        }

        google.maps.event.addListener(map, 'click', function(event) {
            clear_markers();
            add_marker( event.latLng );

            var name = jQuery( '#map-canvas' ).attr( 'data-name' );
            jQuery( 'input[name=' + name + '-lat]' ).val( event.latLng.lat() );
            jQuery( 'input[name=' + name + '-lng]' ).val( event.latLng.lng() );
        });
    }
</script>