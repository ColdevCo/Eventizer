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
		return;
	}
}