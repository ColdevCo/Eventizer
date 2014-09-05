<?php

add_action( 'init', function(){
	add_rewrite_endpoint( 'process' , EP_PERMALINK );
});

add_action( 'template_redirect', function(){

	global $wpdb;

	if ( ! get_query_var( 'process' ) and get_query_var( 'process' ) !== "ticket_widget" ) {
		return;
	}

    $name   = $_POST[ 'cem_widget_ticket-name' ];
    $email  = $_POST[ 'cem_widget_ticket-email' ];
    $phone  = $_POST[ 'cem_widget_ticket-phone' ];

    $event_id   = $_POST[ 'cem_widget_ticket-event_id' ];
    $ticket_id  = $_POST[ 'cem_widget_ticket-ticket_id' ];
    $quantity   = $_POST[ 'cem_widget_ticket-quantity' ];

	$wpdb->insert(  $wpdb->prefix . 'eventizer_attendees' ,
		array(
            'name'      => $name,
			'email' 	=> $email,
			'phone' 	=> $phone,
            'event_id' 	=> $event_id,
            'ticket_id' => $ticket_id,
			'quantity' 	=> $quantity
		)
	);

    $mail = get_mail_by_context( 'Order' );


    if ( wp_mail( $email, $mail->subject, $mail->content ) ) {

        $_COOKIE['notice'] = "Thank you, we receive your order";

    }

	wp_redirect( $_SERVER[ 'HTTP_REFERER' ] );
	exit;
});