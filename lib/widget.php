<?php

function load_widgets() {
	$widgets = scan_widgets();
	foreach ( $widgets as $widget ) {
		include_once( $widget );
	}
}

function scan_widgets() {
	$widgets_path = opendir( __EVENT_WIDGET_PATH__ );
	$widgets = array();
	while ( false !== ( $widget = readdir( $widgets_path ) ) ) {
		$widget_path = __EVENT_WIDGET_PATH__ . $widget;
		if( is_dir( $widget_path ) && $widget != '.' && $widget != '..' ) {
			array_push( $widgets , $widget_path . '/index.php' );
		}
	}

	return $widgets;
}

return add_action( 'widgets_init', 'load_widgets' );