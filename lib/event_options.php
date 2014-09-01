<?php

function add_event_options( $option , $value = '' ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "eventizer_options";
	$wpdb->insert( $table_name , array( 'name' => $option , 'value' => $value ) );
}

function get_event_options( $option ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "eventizer_options";
	return $wpdb->get_col( "SELECT `value` FROM `{$table_name}` WHERE `name` = '$option'", 0 )[0];
}

function update_event_options( $option , $value = '' ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "eventizer_options";
	$wpdb->update( $table_name , array( 'value' => $value ), array( 'name' => $option ) );
}

function delete_event_options( $option ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "eventizer_options";
	$wpdb->delete( $table_name , array( 'name' => $option ) );
}