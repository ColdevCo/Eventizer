<style type="text/css">
    .widget_event_list {
        background-color: #FFFFFF;
        /* border: 1px solid #ddd; */
    }

    .widget_event_list > .cem-event-item {
        margin-bottom: 8px;
    }

    .widget_event_list > .cem-event-item > div {
        display: inline-block;
        vertical-align: top;
    }

    .widget_event_list > .cem-event-item > .cem-event-item-thumbnail {
        width: 30%;
        margin-right: 7px;
        /* padding: 4px; */
    }

    .widget_event_list > .cem-event-item > .cem-event-item-thumbnail > img {
        width: 100%;
        height: auto;
        margin-top: 7px;
    }

    .widget_event_list > .cem-event-item > .cem-event-item-details {
        width: 65%;
    }

    .widget_event_list > .cem-event-item > .cem-event-item-details > h4 {
        margin: 0 0 10px;
    }

    .widget_event_list > .cem-event-item > .cem-event-item-details > p {
        margin: 0;
    }
</style>

<aside id="cem-event-list-<?= $this->number; ?>" class="widget widget_event_list">
    <?php foreach( $events as $event ) : ?>
        <div class="cem-event-item">

            <div class="cem-event-item-thumbnail">
                <?= get_the_post_thumbnail( $event->ID, array( 450, 450 ) ); ?>
            </div>

            <div class="cem-event-item-details">
                <h4><a href="<?= get_permalink( $event->ID ); ?>"><?= $event->post_title; ?></a></h4>
                <p>Start from: <?= date( 'F d, Y', strtotime( get_post_meta( $event->ID , 'ev_start_date' , true ) ) ); ?></p>
                <p>At: <?php echo get_post_meta( $event->ID , 'ev_venue_name' , true ); ?></p>
            </div>

        </div>

        <hr />

    <?php endforeach; ?>
</aside>