<?php
add_action('widgets_init', function(){
	register_widget('TicketWidget');
});

class TicketWidget extends WP_Widget {
	
	/*
	 * Register Widget
	 *
	 */
	function __construct()
	{
		$this->WP_Widget( 'event_ticket' , 'Ticket' );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args , $instance )
	{
		include( __EVENT_EXTENSION_PATH__ . '/ticket/view/widget.php' );
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance )
	{

        $featured_option = $instance[ 'widget_ticket_featured_event' ];

        if ( $featured_option == '' ) {
            $featured_option = get_event_options( 'widget_ticket_featured_event' );
        }

        $upcoming_options = $random_options = $select_options = array();

        switch ( $featured_option ) {
            case 'upcoming':
                $upcoming_options['checked'] = true;
                break;
            case 'random':
                $random_options['checked'] = true;
                break;
            default:
                $select_options['checked'] = true;
        }

		include( __EVENT_EXTENSION_PATH__ . '/ticket/view/backend-form.php' );
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance , $old_instance )
	{
        $instance[ 'widget_ticket_featured_event' ] = $new_instance[ 'widget_ticket_featured_event' ];

        if ( $instance[ 'widget_ticket_featured_event' ] == "select-event" ) {
            $instance[ 'widget_ticket_featured_event' ] = $new_instance[ 'widget_ticket_featured_event-event_id' ];
        }

        update_event_options( 'widget_ticket_featured_event', $instance[ 'widget_ticket_featured_event' ] );

        return $instance;
	}
}