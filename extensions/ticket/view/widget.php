<?php

$args = array(
	'post_type'      => 'event',
	'post_status'    => 'publish',
	'posts_per_page' => 1,
);

$featured_option = get_event_options( 'widget_ticket_featured_event' );
switch ( $featured_option ) {
    case 'random':
        $args['orderby'] = 'rand';
        break;
    case 'upcoming':
        $args['orderby'] = 'meta_value';
        $args['meta_key'] = 'ev_start_date';
        $args['order'] = 'asc';
        $args['meta_query'] = array(
            array(
                'key' => 'ev_start_date',
                'value' => date('F j, Y'),
                'compare' => '>=',
                'type' => 'DATE'
            )
        );
        break;

}

$events = get_posts( $args );

$event = get_post( 69 );

wp_enqueue_script( 'ev-ticket', plugins_url( '', dirname( __FILE__ ) ) . '/js/ev-ticket.js', array( 'jquery' ) );
wp_enqueue_style( 'ev-ticket-style', plugins_url( '', dirname( __FILE__ ) ) . '/css/ev-ticket.css' );

?>

<div id="cem-ticket-widget">

    <?php if ( isset( $event ) ) : ?>

    <form action="?process=ticket_widget" method="post">

        <?= HTML::hidden( 'cem_widget_ticket-event_id', $event->ID ); ?>

        <div class="cem-event-thumbnail">

            <?= get_the_post_thumbnail( $event->ID, array( 450, 450 ) ); ?>

        </div>

        <h3><a href="<?= get_permalink( $event->ID ); ?>"><?= $event->post_title; ?></a></h3>
        <p><?= $event->post_excerpt; ?></p>

        <div class="input-group">

            <?= HTML::label( 'Name', 'cem_widget_ticket-name' ); ?>
            <?= HTML::text( 'cem_widget_ticket-name', array( 'id' => 'cem_widget_ticket-name' ) ); ?>

        </div>

        <div class="input-group">

            <?= HTML::label( 'Email', 'cem_widget_ticket-email' ); ?>
            <?= HTML::text( 'cem_widget_ticket-email', array( 'id' => 'cem_widget_ticket-email' ) ); ?>

        </div>

        <div class="input-group">

            <?= HTML::label( 'Phone', 'cem_widget_ticket-phone' ); ?>
            <?= HTML::text( 'cem_widget_ticket-phone', array( 'id' => 'cem_widget_ticket-phone' ) ); ?>

        </div>

        <hr />

        <div class="input-group">

            <?= HTML::label( 'Type', 'cem_widget_ticket-ticket_id' ); ?>
            <?= HTML::text( 'cem_widget_ticket-ticket_id', array( 'id' => 'cem_widget_ticket-ticket_id' ) ); ?>

        </div>

        <div class="input-group">

            <?= HTML::label( 'Quantity', 'cem_widget_ticket-quantity' ); ?>
            <?= HTML::text( 'cem_widget_ticket-quantity', array( 'id' => 'cem_widget_ticket-quantity' ) ); ?>

        </div>

        <div class="input-group">

            <?= HTML::button( 'Buy' ); ?>

        </div>

    </form>

    <?php endif; ?>

</div>