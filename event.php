<?php
/**
 * Plugin Name: Test Event
 * Plugin URI: http://www.facebook.com
 * Description: Test Event
 * Version: 1.0
 * Author: Me
 * Author URI: http://www.facebook.com
 */

require "lib/field-type.php";

add_action( 'init', 'register_event' );
function register_event() {
	$args = array(
		'labels'        => array(
			'name'               => 'Events',
			'singular_name'      => 'Event',
			'menu_name'          => 'Event',
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Event',
			'edit_item'          => 'Edit Event',
			'new_item'           => 'New Event',
			'view_item'          => 'View Event',
			'search_items'       => 'Search Events',
			'not_found'          => 'No events found',
			'not_found_in_trash' => 'No events found in Trash'
		),
		'description'   => 'Event Management',
		'supports'      => array( 'title', 'editor', 'thumbnail' ),
		'menu_position' => 5,
		'public'        => true
	);
	register_post_type( 'event', $args );
}

add_action( 'add_meta_boxes', 'add_event_details_meta_box' );
function add_event_details_meta_box() {
	add_meta_box( 'event-details-box', 'Detail', 'details_box_html', 'event', 'side', 'high' );
}

function details_box_html() {
	wp_nonce_field( 'ev_details_box', 'ev_details_box_nonce' );

	$fields   = array();
	$fields[] = text( 'ev_price', array( 'label' => 'Price' ) );
	$fields[] = textarea( 'ev_location', array( 'label' => 'Location' ) );

	$fields = apply_filters( 'add_fields', $fields );

	foreach ( $fields as $field ) {
		echo $field;
	}
}

add_action( 'save_post', 'save_event_meta_value' );
function save_event_meta_value( $post_id ) {

	if ( ! isset( $_POST['ev_details_box_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['ev_details_box_nonce'], 'ev_details_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['post_type'] ) && 'event' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	$price    = sanitize_text_field( $_POST['ev_price'] );
	$location = sanitize_text_field( $_POST['ev_location'] );

	update_post_meta( $post_id, 'ev_price', $price );
	update_post_meta( $post_id, 'ev_location', $location );
}

class Event {
	private $_supports = array( 'title', 'editor', 'thumbnail' );
	private $_meta_boxes = array(
		array( 'name' => 'detail', 'context' => 'side', 'priority' => 'default' )
	);
	private $_custom_fields = array(
		'position' => 'detail', 'fields' => array(
			array( 'name' => 'price', 'type' => 'number', 'context' => 'side', 'options' => array() ),
			array( 'name' => 'location', 'type' => 'text', 'context' => 'side', 'options' => array() ),
			array( 'name' => 'geo', 'type' => 'text', 'context' => 'side', 'options' => array() )
		)
	);

	public function set_supports( $supports ) {
		return $this->_supports = $supports;
	}

	public function get_supports() {
		return $this->_supports;
	}

	public function set_meta_boxes( $meta_boxes ) {
		return $this->_meta_boxes = $meta_boxes;
	}

	public function get_meta_boxes() {
		return $this->_meta_boxes;
	}

	public function set_custom_fields( $custom_fields ) {
		return $this->_custom_fields = $custom_fields;
	}

	public function get_custom_fields() {
		return $this->_custom_fields;
	}
}
