<?php

global $post;
$post_id = $post->ID;

?>

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
        border: 1px #d9d9d9 solid;
        width: 175px;
        height: 100%;
        margin: 0 15px 0 0;
        padding: 5px;
    }

    .timepicker > select:last-child {
        margin-left: 8px;
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
    #ev_venue_location {
        width: 400px;
    }

    #ev_venue_address {
        height: 80px;
    }

    #ev_venue_location {
        height: 200px;
    }
</style>

<?php $form = new Form( $post_id ); ?>

<div class="input-group first">

    <?= HTML::label('All day event?', 'ev_allday', array('class' => 'label-ev_allday left')); ?>

    <?= $form->radio('Yes', 'ev_allday', array('class' => 'input-ev_allday')); ?>
    <?= $form->radio('No', 'ev_allday', array('class' => 'input-ev_allday', 'checked' => true)); ?>

    <?php
    $hidden = '';
    $allday = get_post_meta( $post_id, 'ev_allday', true );

    if ( $allday != '' && $allday == 'yes' ) {
        $hidden = ' hidden';
    }
    ?>

</div>

<div class="input-group">

    <?= HTML::label('Start date &amp; time', 'ev_start_date', array('class' => 'label-ev_start_date left')); ?>

    <?= $form->datepicker('ev_start_date', array('class' => 'datepicker', 'id' => 'ev_start_date')); ?>
    <?= $form->timepicker('ev_start_time', array('class' => 'timepicker input-ev_start_time' . $hidden)); ?>

</div>

<div class="input-group">

    <?= HTML::label('End date &amp; time', 'ev_end_date', array('class' => 'label-ev_end_date left')); ?>

    <?= $form->datepicker('ev_end_date', array('class' => 'datepicker', 'id' => 'ev_end_date')); ?>
    <?= $form->timepicker('ev_end_time', array('class' => 'timepicker input-ev_end_time' . $hidden)); ?>

</div>

<hr class="input-divider" />

<div class="input-group">

    <?= HTML::label('Venue Name', 'ev_venue_name', array('class' => 'label-ev_venue_name left')); ?>
    <?= $form->text('ev_venue_name', array('id' => 'ev_venue_name')); ?>

</div>

<div class="input-group">

    <?= HTML::label('Venue Address', 'ev_venue_address', array('class' => 'label-ev_venue_address left')); ?>
    <?= $form->textarea('ev_venue_address', array('id' => 'ev_venue_address')); ?>

</div>

<div class="input-group last">

    <?= HTML::label('Venue Location', 'ev_venue_location', array('class' => 'label-ev_venue_location left')); ?>
    <?= $form->geoinput('ev_venue_location', array('class' => 'google-map')); ?>

</div>

<script type="text/javascript">

    var map_selector = '#ev_venue_location';

    var map;
    var markers = [];
    var geocoder;

    jQuery(document).ready(function(){

        // Event date script
        jQuery('.input-ev_allday').bind('change', function(){
            jQuery('.input-ev_start_time').toggleClass('hidden');
            jQuery('.input-ev_end_time').toggleClass('hidden');
        });

        jQuery('#ev_start_date').datepicker({
            dateFormat: 'MM d, yy',
            onClose: function( selectedDate ) {
                jQuery('#ev_end_date').datepicker( "option", "minDate", selectedDate );
            }
        });

        jQuery('#ev_end_date').datepicker({
            dateFormat: 'MM d, yy',
            onClose: function( selectedDate ) {
                jQuery('#ev_start_date').datepicker( "option", "maxDate", selectedDate );
            }
        });

        jQuery('#ev_venue_address').change(function(){
            var address = jQuery(this).val();
            geocoder.geocode( { 'address': address}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    clear_markers();
                    add_marker(results[0].geometry.location);

                    map.setCenter(results[0].geometry.location);
                } else {
                    alert("Geocode was not successful for the following reason: " + status);
                }
            });
        });

        jQuery(window).load( include_google_map_script() );
    });

    function include_google_map_script()
    {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?v=3&sensor=true&callback=init_map';
        document.body.appendChild(script);
    }

    function add_marker(location)
    {
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });

        jQuery( '#ev_venue_location-lat' ).val( location.lat() );
        jQuery( '#ev_venue_location-lng' ).val( location.lng() );

        markers.push( marker );
    }

    function clear_markers()
    {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap( null );
        }

        markers = [];
    }

    function init_map()
    {
        var mapOptions = {
            center: new google.maps.LatLng( -34.397, 150.644 ),
            zoom: 10,
            scrollwheel: false
        };
        map = new google.maps.Map(document.getElementById( "ev_venue_location" ), mapOptions);
        geocoder = new google.maps.Geocoder();

        if ( jQuery( '#ev_venue_location-lat' ).val() != '' && jQuery( '#ev_venue_location-lng' ).val() != '' ) {
            var markerLat = jQuery( '#ev_venue_location-lat' ).val();
            var markerLng = jQuery( '#ev_venue_location-lng' ).val();
            var markerLocation = new google.maps.LatLng( markerLat, markerLng );

            add_marker( markerLocation );
            map.setCenter( markerLocation );
            map.setZoom(16);
        } else {
            if(!!navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var geolocate = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                    add_marker( geolocate );
                    map.setCenter(geolocate);
                    map.setZoom(16);
                });
            }
        }

        google.maps.event.addListener(map, 'click', function(event) {
            clear_markers();
            add_marker( event.latLng );
        });
    }
</script>