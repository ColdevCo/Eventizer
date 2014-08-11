<?php

add_action( 'init', function(){
	add_rewrite_endpoint( 'process' , EP_PERMALINK );
});

add_action( 'template_redirect', function(){

	global $wpdb;

	if ( ! get_query_var( 'process' ) and get_query_var( 'process' ) !== "ticket_widget" ) {
		return;
	}

	$wpdb->insert(  $wpdb->prefix . 'event_attendees' ,
		array(
			'event_id' 	=> $_POST[ 'ev_ticket-id' ],
			'email' 	=> $_POST[ 'ev_ticket-email' ],
			'first_name' => $_POST[ 'ev_ticket-first-name' ],
			'last_name' => $_POST[ 'ev_ticket-last-name' ],
			'phone' 	=> $_POST[ 'ev_ticket-phone' ],
			'guest_no' 	=> $_POST[ 'ev_ticket-qty' ]
		)
	);
	wp_redirect( $_SERVER[ 'HTTP_REFERER' ] );
	exit;
});