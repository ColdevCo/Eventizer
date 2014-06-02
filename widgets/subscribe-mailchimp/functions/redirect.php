<?php

add_action( 'init', function(){
	add_rewrite_endpoint( 'ma' , EP_PERMALINK );	
});

add_action( 'template_redirect', function(){

	if ( ! get_query_var( 'ma' ) and get_query_var( 'ma' ) !== "subscribe" ) {
		return;
	}

	$MailChimp = new MailChimp('54f8f05804c46b290e4f0a4ba5f9adb9-us8');
	$result = $MailChimp->call('lists/subscribe', array(
                'id'                => '01505c6f29',
                'email'             => array('email'=>'bimph2011@gmail.com'),
            ));
	
	wp_redirect( $_SERVER[ 'HTTP_REFERER' ] );
	exit;
});