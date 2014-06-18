<?php

function text( $name, $args = array() ) {
	global $post;

	$label = isset( $args['label'] ) ? $args['label'] : $name;
	$style = isset( $args['style'] ) ? $args['style'] : 'width: 100%;';
	
	$meta_value = $args['value'] ? $args['value'] : get_post_meta( $post->ID, $name, true );

	$html = "<label for=\"{$name}\">{$label}</label>";
	$html .= "<input
        style=\"{$style}\"
        id=\"{$name}\"
        type=\"text\"
        name=\"{$name}\"
        value=\"{$meta_value}\" />
        <br />";

	return $html;
}

function textarea( $name, $args ) {
	global $post;

	$label = isset( $args['label'] ) ? $args['label'] : $name;
	$row   = isset( $args['row'] ) ? $args['row'] : 3;
	$cols  = isset( $args['cols'] ) ? $args['cols'] : 2;
	$style = isset( $args['style'] ) ? $args['style'] : 'width: 100%;';

	$meta_value = $args['value'] ? $args['value'] : get_post_meta( $post->ID, $name, true );

	$html = "<label for=\"{$name}\">{$label}</label>";
	$html .= "<textarea
        rows=\"{$row}\"
        cols=\"{$cols}\"
        style=\"{$style}\"
        id=\"{$name}\"
        name=\"{$name}\">{$meta_value}</textarea>
        <br />";

	return $html;
}

function radio( $name, $args ) {

}

function checkbox( $name, $args ) {
	global $post;

	$label = isset( $args['label'] ) ? $args['label'] : $name;
	$style = isset( $args['style'] ) ? $args['style'] : 'width: 100%;';

	$meta_value = $args['value'] ? $args['value'] : get_post_meta( $post->ID, $name, true );

	$html = "<div><label for=\"{$name}\">{$label}</label></div>";
	$html .= "<label>
		<input type=\"checkbox\" id=\"{$name}\" value=\"{$meta_value}\"> Enable
	</label>";

	return $html;
}

function datepicker( $name, $args ) {
	global $post;

	$plugin_url = plugins_url( '', dirname( __FILE__ ) );

	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_style( 'ev-jquery-style', $plugin_url . '/assets/lib/Aristo/Aristo.css' );
	wp_enqueue_script( 'ev-main', $plugin_url . '/assets/js/ev-main.js' );

	$label = isset( $args['label'] ) ? $args['label'] : $name;
	$style = isset( $args['style'] ) ? $args['style'] : 'width: 100%;';

	$meta_value = $args['value'] ? $args['value'] : get_post_meta( $post->ID, $name, true );

	$html = "<div><label for=\"{$name}\">{$label}</label></div>";
	$html .= "<input
		class=\"ev-datepicker\"
        style=\"{$style}\"
        id=\"{$name}\"
        type=\"text\"
        name=\"{$name}\"
        value=\"{$meta_value}\" />
        <br />";

	return $html;
}

function datetimepicker( $name, $args ) {
	global $post;

	$plugin_url = plugins_url( '', dirname( __FILE__ ) );

	wp_enqueue_script( 'jquery-ui-datepicker' );
	wp_enqueue_script( 'jquery-ui-slider' );
	wp_enqueue_script( 'jquery-ui-timepicker-addon', $plugin_url . '/assets/js/jquery-ui-timepicker-addon.js' );
	wp_enqueue_style( 'ev-jquery-style', $plugin_url . '/assets/lib/Aristo/Aristo.css' );
	wp_enqueue_script( 'ev-main', $plugin_url . '/assets/js/ev-main.js' );

	$label = isset( $args['label'] ) ? $args['label'] : $name;
	$style = isset( $args['style'] ) ? $args['style'] : 'width: 100%;';

	$meta_value = get_post_meta( $post->ID, $name, true ) !== '' ? date( 'm/d/Y H:i', strtotime( get_post_meta( $post->ID, $name, true ) ) ) : '';
	if ( $args['value'] ) {
		$meta_value = $args['value'];
	}

	$html = "<div><label for=\"{$name}\">{$label}</label></div>";
	$html .= "<input
		class=\"ev-datetimepicker\"
        style=\"{$style}\"
        id=\"{$name}\"
        type=\"text\"
        name=\"{$name}\"
        value=\"{$meta_value}\" />
        <br />";

	return $html;
}

function colorpicker( $name, $args ) {
	global $post;

	$plugin_url = plugins_url( '', dirname( __FILE__ ) );

	wp_enqueue_script( 'ev-colorpicker', $plugin_url . '/assets/lib/spectrum/spectrum.js' );
	wp_enqueue_style( 'ev-colorpicker-style', $plugin_url . '/assets/lib/spectrum/spectrum.css' );
	wp_enqueue_script( 'ev-main', $plugin_url . '/assets/js/ev-main.js' );

	$label = isset( $args['label'] ) ? $args['label'] : $name;
	$style = isset( $args['style'] ) ? $args['style'] : 'width: 100%;';

	$meta_value = $args['value'] ? $args['value'] : get_post_meta( $post->ID, $name, true );

	$html = "<div><label for=\"{$name}\">{$label}</label></div>";
	$html .= "<input
		class=\"ev-colorpicker\"
        style=\"{$style}\"
        id=\"{$name}\"
        type=\"color\"
        name=\"{$name}\"
        value=\"{$meta_value}\" />
        <br />";

	return $html;
}

function gmap( $name, $args ) {
	global $post;

	$plugin_url = plugins_url( '', dirname( __FILE__ ) );

	wp_enqueue_script( 'ev-gmap', 'https://maps.googleapis.com/maps/api/js?key=' . $args[ 'apikey' ] );
	wp_enqueue_script( 'ev-gmap-main', $plugin_url . '/assets/js/ev-map	.js' );

	$lat = $args['value'] ? $args['value'] : get_post_meta( $post->ID, "{$name}-lat", true );
	$lng = $args['value'] ? $args['value'] : get_post_meta( $post->ID, "{$name}-lng", true );

	$width = isset( $args['width'] ) ? $args['width'] : '100%';
	$height = isset( $args['height'] ) ? $args['height'] : '300px';

	$html = "<div id='map-canvas' style='width: {$width}; height: {$height};' data-name='{$name}' data-marker='{$lat},{$lng}'></div>";
	$html .= "<input type='hidden' name='{$name}-lat' value='{$lat}' />";
	$html .= "<input type='hidden' name='{$name}-lng' value='{$lng}' />";

	return $html;
}