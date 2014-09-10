<?php get_header(); ?>

<style type="text/css">
    .event-date {
        background-color: #E8E8E8;
        border: 1px solid #DDD;
        display: inline-block;
        padding: 5px 20px;
        margin: 15px 0 15px 15px;
        float: right;
    }

    .event-details {
        text-align: justify;
    }

    .event-location {
        background-color: #E8E8E8;
        border: 1px solid #DDD;
        display: inline-block;
        margin: 15px 0;
    }

    .event-tickets {
        display: inline-block;
        margin: 15px 0 15px 15px;
        vertical-align: top;
    }

    .event-tickets h4:first-child {
        margin-top: 0;
    }
</style>

    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">

            <?php while ( have_posts() ) : the_post(); ?>

                <h2><?php the_title(); ?></h2>

                <?php the_post_thumbnail() ?>

                <div class="event-date">
                    <h4>
                        <?php
                        $start_date = date( 'j F Y', strtotime( get_post_meta( get_the_ID(), 'ev_start_date', true ) ) );
                        $end_date   = date( 'j F Y', strtotime( get_post_meta( get_the_ID(), 'ev_end_date', true ) ) );

                        if ( $start_date == $end_date )
                            echo $start_date;
                        else
                            echo "{$start_date} - {$end_date}";
                        ?>
                    </h4>

                    <p>
                        <?= get_post_meta( get_the_ID(), 'ev_start_time', true ); ?>
                        -
                        <?= get_post_meta( get_the_ID(), 'ev_end_time', true ); ?>
                    </p>
                </div>

                <div class="event-details"><?php the_content(); ?></div>

                <div class="event-location">

                    <img src="http://maps.googleapis.com/maps/api/staticmap?zoom=15&size=300x150&maptype=roadmap&markers=<?= get_post_meta( get_the_ID(), 'ev_venue_location', true ); ?>" />
                    <h4><?= get_post_meta( get_the_ID(), 'ev_venue_name', true ); ?></h4>
                    <p><?= get_post_meta( get_the_ID(), 'ev_venue_address', true ); ?></p>

                </div>

                <?php
                if ( is_enabled( 'Ticket' ) ) :
                    $tickets = EventTicket::get_event_tickets( get_the_ID() );
                ?>

                <div class="event-tickets">
                    <?php foreach( $tickets as $ticket ) : ?>

                        <div>
                            <h4>
                                <?= $ticket->name ?>
                                &nbsp;
                                <strong><?= get_event_options( 'default_currency' ) . ' ' . number_format($ticket->price); ?></strong>
                            </h4>
                            <p><?= date( 'j F Y H:i', strtotime($ticket->start_sell_date) ) . ' - ' . date( 'j F Y H:i', strtotime($ticket->stop_sell_date) ) ?></p>
                        </div>

                        <hr />

                    <?php endforeach; ?>
                </div>

                <?php
                $tickets = EventTicket::get_event_tickets( get_the_ID(), true );
                $tickets = array_reduce($tickets, function($result, $data){ $result["{$data->id} "] = $data->name; return $result; }, array());
                if ( $tickets ) : ?>

                    <div class="form-ticket widget_cem_ticket">

                        <form action="?process=ticket_widget" method="post">

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

                            <div class="input-group">

                                <?= HTML::label( 'Type', 'cem_widget_ticket-ticket_id' ); ?>
                                <?= HTML::dropdown( 'cem_widget_ticket-ticket_id', $tickets, array( 'id' => 'cem_widget_ticket-ticket_id' ) ); ?>

                            </div>

                            <div class="input-group">

                                <?= HTML::label( 'Quantity', 'cem_widget_ticket-quantity' ); ?>
                                <?= HTML::text( 'cem_widget_ticket-quantity', array( 'id' => 'cem_widget_ticket-quantity' ) ); ?>

                            </div>

                            <div class="input-group">

                                <?= HTML::button( 'Buy' ); ?>

                            </div>

                        </form>

                    </div>

            <?php endif; endif; endwhile; ?>

        </div>
    </div>

<?php
get_sidebar();
get_footer();
