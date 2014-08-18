<style type="text/css">
    #widget-ticket-backend-form {
        padding: 10px 0;
    }

    #widget-ticket-backend-form label {
        display: block;
        padding: 3px 0;
    }

    #widget_ticket_featured_event-event_id {
        margin-left: 20px;
    }
</style>

<div id="widget-ticket-backend-form" class="input-group">

    <label>Featured Options:</label>

    <?= HTML::radio( 'Upcoming', $this->get_field_name( 'widget_ticket_featured_event' ), $upcoming_options ); ?>
    <?= HTML::radio( 'Random', $this->get_field_name( 'widget_ticket_featured_event' ), $random_options ); ?>

    <?php
    $args = array(
        'post_type'      => 'event',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'meta_value',
        'meta_key'       => 'ev_start_date',
        'order'          => 'asc',
        'meta_query'     => array(
            array(
                'key' => 'ev_start_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE'
            )
        )
    );
    $events = get_posts( $args );

    if ( $events ) {
        echo HTML::radio( 'Select event', $this->get_field_name( 'widget_ticket_featured_event' ), $select_options );

        $events = array_reduce($events, function($result, $data){ $result["{$data->ID} "] = $data->post_title; return $result; }, array());
        echo HTML::dropdown( $this->get_field_name( 'widget_ticket_featured_event-event_id' ), $events, array( 'id' => 'widget_ticket_featured_event-event_id', 'value' => $featured_option ) );
    }
    ?>

</div>