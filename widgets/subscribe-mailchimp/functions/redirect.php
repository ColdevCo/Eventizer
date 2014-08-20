<?php

add_action( 'init', function(){
	add_rewrite_endpoint( 'ma' , EP_PERMALINK );	
});

add_action( 'template_redirect', function(){

	if ( ! get_query_var( 'ma' ) and get_query_var( 'ma' ) !== "subscribe" ) {
		return;
	}

    /*
     * apikey: 54f8f05804c46b290e4f0a4ba5f9adb9-us8
     * list id: 01505c6f29
     */

    $mailchimp_apikey  = get_event_options( 'mailchimp-apikey' );
    $mailchimp_list_id = get_event_options( 'mailchimp-list-id' );

    if ( ! is_null( $mailchimp_apikey ) && ! is_null( $mailchimp_list_id ) ) {
        $MailChimp = new MailChimp( $mailchimp_apikey );
        $MailChimp->call('lists/subscribe', array(
                    'id'        => $mailchimp_list_id,
                    'email'     => array( 'email'=> $_POST[ 'cem_subscribe_mailchimp-email' ] ),
        ));
    }
	
	wp_redirect( $_SERVER[ 'HTTP_REFERER' ] );
	exit;
});