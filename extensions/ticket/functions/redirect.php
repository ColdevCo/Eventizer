<?php

add_action( 'init', function(){
	add_rewrite_endpoint( 'process' , EP_PERMALINK );	
});

add_action( 'template_redirect', function(){

	global $wpdb;

	if ( ! get_query_var( 'process' ) and get_query_var( 'process' ) !== "ticket_widget" ) {
		return;
	}

	$wpdb->insert(  $wpdb->prefix . 'event_attendances' , 
		array(
			'event_id' => 23,
			'email' => 'naufal.faruqi2010@gmail.com',
			'first_name' => 'Naufal',
			'last_name' => 'Faruqi',
			'phone' => '085720230222',
			'guest_no' => 1
		) 
	);
	exit;
});