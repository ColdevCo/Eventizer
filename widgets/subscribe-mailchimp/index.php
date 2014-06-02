<?php

include_once( 'functions/MailChimp.php' );
include_once( 'functions/redirect.php' );

register_widget( 'SubscribeMailChimp' );

class SubscribeMailChimp extends WP_Widget {

	/*
	 * Register Widget
	 *
	 */
	function __construct()
	{
		$this->WP_Widget( 'subscribe_mailchimp' , 'Subscribe to MailChimp' );
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
		$mailchimp_apikey  = '';
		$mailchimp_list_id = '';
		
		if ( $instance ) {
			$mailchimp_apikey  = $instance[ 'mailchimp-apikey' ];
			$mailchimp_list_id = $instance[ 'mailchimp-list-id' ];
		}

		include( 'view/backend-form.php' );
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
		$instance[ 'mailchimp-apikey' ]  = $new_instance[ 'mailchimp-apikey' ];
		$instance[ 'mailchimp-list-id' ] = $new_instance[ 'mailchimp-list-id' ];
		return $instance;
	}
}