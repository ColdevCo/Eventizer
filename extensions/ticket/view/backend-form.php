<style type="text/css">
    #widget-ticket-backend-form {
        padding: 10px 0;
    }

    #widget-ticket-backend-form label {
        display: block;
        padding: 3px 0;
    }
</style>

<div id="widget-ticket-backend-form" class="input-group">

    <label>Featured Options:</label>

    <?= HTML::radio( 'Upcoming', $this->get_field_name( 'widget_ticket_featured_event' ), $upcoming_options ); ?>
    <?= HTML::radio( 'Random', $this->get_field_name( 'widget_ticket_featured_event' ), $random_options ); ?>
    <?//= HTML::radio( 'Select', $this->get_field_name( 'widget_ticket_featured_event' ), $select_options ); ?>

</div>