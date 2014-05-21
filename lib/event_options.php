<?php

function add_event_options( $option , $value = '' ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "event_settings";
	$wpdb->insert( $table_name , array( 'name' => $option , 'value' => $value ) );
}

function get_event_options( $option ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "event_settings";
	return $wpdb->get_col( "SELECT `value` FROM `$table_name` WHERE `name` = '$option'", 0 )[0];
}

function update_event_options( $option , $value = '' ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "event_settings";
	$wpdb->update( $table_name , array( 'value' => $value ), array( 'name' => $option ) );
}

function delete_event_options( $option ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "event_settings";
	$wpdb->delete( $table_name , array( 'name' => $option ) );
}