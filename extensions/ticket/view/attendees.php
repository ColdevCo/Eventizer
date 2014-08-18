<?php

wp_enqueue_script( 'jquery-chosen', plugins_url( '', dirname( __FILE__ ) ) . '/js/chosen_v1.1.0/chosen.jquery.min.js', array( 'jquery' ), '', true );
wp_enqueue_style( 'jquery-chosen-css', plugins_url( '', dirname( __FILE__ ) ) . '/js/chosen_v1.1.0/chosen.min.css' );

$filter = array(
    'event_id' => $_GET['ev_attendee_event_name'],
    'name'  => $_GET['ev_attendee_name'],
    'email' => $_GET['ev_attendee_email'],
    'phone' => $_GET['ev_attendee_phone']
);

$paged = isset($_GET['paged']) ? (int) $_GET['paged'] : 1;

$attendees = EventTicket::get_attendees( $filter, $paged );

?>
<style type="text/css">
    .wrap {
        margin: 30px 30px 30px 10px;
        padding: 15px 15px 40px 15px;
        background-color: #fff;
    }

    .wrap > h2 {
        margin-bottom: 15px;
    }

    #trigger_filter {
        font-size: 16px;
        color: #2ea2cc;
        cursor: pointer;
        margin: 15px 0;
        display: inline-block;
    }

    .ev_attendee_filter {
        background-color: #F1F1F1;
        /* margin: 0 -15px; */
        padding: 30px 40px;
        margin-bottom: 40px;
    }

    .ev_attendee_filter > div.input-group {
        margin: 0 5px 10px;
    }

    div.input-group > label.left {
        display: inline-block;
        width: 80px;
        vertical-align: top;
    }

    #ev_attendee_event_name_chosen > ul {
        padding: 5px;
        border: 1px solid #ddd;
    }


    #ev_attendee_event_name,
    #ev_attendee_name,
    #ev_attendee_email,
    #ev_attendee_phone {
        width: 275px;
        height: 100%;
        padding: 5px;
    }

    #ev_attendee_submit_filter {
        display: inline-block;
        cursor: pointer;
        background-color: #16499A;
        color: #FFF;
        padding: 6px 30px;
        margin: 25px 0 0;
        text-align: center;
        vertical-align: middle;
        border: 1px solid transparent;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
    }

    #ev_attendee_clear_filter {
        display: inline-block;
        cursor: pointer;
        background-color: #9A1616;
        color: #FFF;
        padding: 6px 30px;
        margin: 25px 0 0;
        text-align: center;
        vertical-align: middle;
        border: 1px solid transparent;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
    }

    table.attendees {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }

    table.attendees th,
    table.attendees td {
        padding: 8px;
        font-size: 14px;
        line-height: 14pt;
        text-align: left;
    }

    table.attendees th {
        border-bottom: 1px solid #111;
        vertical-align: bottom;
    }

    table.attendees td {
        border-bottom: 1px solid #EAEAEA;
        vertical-align: middle;
    }
</style>

<div id="event-attendees" class="wrap">
    <h2>Attendees</h2>
    <hr />

    <!-- <span id="trigger_filter">Filter</span> -->

    <form class="ev_attendee_filter" method="get">

        <input type="hidden" name="post_type" value="event" />
        <input type="hidden" name="page" value="event-attendees" />

        <div class="input-group">

            <label for="ev_attendee_event_name" class="left">Event</label>
            <select id="ev_attendee_event_name" name="ev_attendee_event_name[]" multiple="true" style="width: 275px">
                <?php
                $events = get_posts( array( 'post_type' => 'event', 'post_status' => 'publish', 'posts_per_page' => -1 ) );
                foreach( $events as $event ) :
                ?>
                    <option value="<?= $event->ID; ?>" <?= ( isset($_GET['ev_attendee_event_name']) && in_array( $event->ID, $_GET['ev_attendee_event_name'] ) ) ? 'selected' : '' ?>><?= $event->post_title; ?></option>
                <?php endforeach; wp_reset_postdata(); ?>
            </select>

        </div>

        <div class="input-group">

            <label for="ev_attendee_name" class="left">Name</label>
            <input type="text" id="ev_attendee_name" name="ev_attendee_name" value="<?= $_GET['ev_attendee_name'] ?>" />

        </div>

        <div class="input-group">

            <label for="ev_attendee_email" class="left">Email</label>
            <input type="email" id="ev_attendee_email" name="ev_attendee_email" value="<?= $_GET['ev_attendee_email'] ?>" />

        </div>

        <div class="input-group">

            <label for="ev_attendee_phone" class="left">Phone</label>
            <input type="text" id="ev_attendee_phone" name="ev_attendee_phone" value="<?= $_GET['ev_attendee_phone'] ?>" />

        </div>

        <div class="input-group">

            <button id="ev_attendee_submit_filter">Filter</button>
            <button id="ev_attendee_clear_filter">Clear</button>

        </div>

    </form>

    <table class="attendees">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Event</th>
            <th>Qty</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $attendees->data as $attendee ) : ?>
        <tr>
            <td><?= $attendee->name ?></td>
            <td><?= $attendee->email !== '' ? $attendee->email : '-' ?></td>
            <td><?= $attendee->phone !== '' ? $attendee->phone : '-' ?></td>
            <td><?= get_the_title( $attendee->event_id ) ?></td>
            <td><?= $attendee->quantity . ' ' . EventTicket::get_ticket_name( $attendee->ticket_id ); ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?= $attendees->paginate; ?>

</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#ev_attendee_event_name").chosen({
            disable_search_threshold: 10,
            placeholder_text_multiple: "Select Event(s)"
        });

        jQuery("#ev_attendee_clear_filter").bind('click', function(e){

            e.preventDefault();

            var url = window.location.origin + window.location.pathname + "?post_type=event&page=event-attendees";

            jQuery(location).attr('href', url);

        });
    });
</script>