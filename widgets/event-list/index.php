<?php

register_widget( 'EventList' );

class EventList extends WP_Widget {

	/*
	 * Register Widget
	 *
	 */
	function __construct()
	{
		$this->WP_Widget( 'event_list' , 'Event List' );
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
		if ( $instance ) {
			$event_list_counter = $instance[ 'event-list-counter' ];
		} else {
			$event_list_counter = 10;
		}
		
		$args   = array(
			'posts_per_page'	=> $event_list_counter,
			'paged'				=> 1,
			'pagination'		=> false,
			'post_type' 		=> 'event',
			'post_status' 		=> 'publish',
			'order'				=> 'ASC',
			'orderby'			=> 'meta_value',
			'meta_query'		=> array(
				array(
					'key'		=> 'ev_start_date',
					'value'		=> date("Y-m-d"),
					'compare'	=> '>=',
					'type'		=> 'DATE',
					),
				),
			'meta_key'			=> 'ev_start_date',
		);
		$events = get_posts( $args );

		include( 'view/widget.php' );	
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
		if ( $instance ) {
			$event_list_counter = $instance[ 'event-list-counter' ];
		} else {
			$event_list_counter = 10;
		}

		include( 'view/backend-form.php');
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
		$instance[ 'event-list-counter' ] = $new_instance[ 'event-list-counter' ];
		return $instance;
	}
}