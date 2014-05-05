<?php
/**
 * Plugin Name: Test Event
 * Plugin URI: http://www.facebook.com
 * Description: Test Event
 * Version: 1.0
 * Author: Me
 * Author URI: http://www.facebook.com
 */

register_activation_hook( __FILE__ , 'register_event' );
add_action( 'init' , 'register_event' );
function register_event() {
	$args = array(
		'labels'		=> array(
			'name'			=> 'Events',
			'singular_name'	=> 'Event',
			'menu_name'		=> 'Event',
			'add_new'		=> 'Add New',
			'add_new_item'	=> 'Add New Event',
			'edit_item'		=> 'Edit Event',
			'new_item'		=> 'New Event',
			'view_item'		=> 'View Event',
			'search_items'	=> 'Search Events',
			'not_found'		=> 'No events found',
			'not_found_in_trash' => 'No events found in Trash'
			),
		'description'	=> 'Event Management',
		'supports'		=> array( 'title' , 'editor' , 'thumbnail' ),
		'menu_position'	=> 5,
		'public'		=> true
	);
	register_post_type( 'event' , $args );
}

add_action('admin_menu', 'register_event_setting_submenu');
function register_event_setting_submenu() {
	add_submenu_page( 'edit.php?post_type=event' , 'setting' , 'Setting' , 'manage_options' , 'event-setting' , function() { include 'templates/setting.php'; } );
}

add_action( 'add_meta_boxes', 'add_event_details_meta_box' );
function add_event_details_meta_box() {
	add_meta_box( 'event-details-box' , 'Detail' , 'details_box_html', 'event' , 'side' , 'high' );
}

function details_box_html() {
	global $post;
	
	wp_nonce_field( 'ev_details_box', 'ev_details_box_nonce' );

	$date 		= get_post_meta( $post->ID, 'ev_date', true );
	$price 		= get_post_meta( $post->ID, 'ev_price', true );
	$location 	= get_post_meta( $post->ID, 'ev_location', true );

	echo '<h4 style="margin-bottom: 5px">Date</h4>';
	echo '<input id="date" type="text" name="ev_date" value="' . esc_attr( $date ) . '"><br />';
	echo '<h4 style="margin-bottom: 5px">Price</h4>';
	echo '<input id="price" type="text" name="ev_price" value="' . esc_attr( $price ) . '"><br />';
	echo '<h4 style="margin-bottom: 5px">Location</h4>';
	echo '<input id="location" type="text" name="ev_location" value="' . esc_attr( $location ) . '"><br />';
}

add_action( 'save_post' , 'save_event_meta_value' );
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

	$date 		= sanitize_text_field( $_POST['ev_date'] );
	$price 		= sanitize_text_field( $_POST['ev_price'] );
	$location 	= sanitize_text_field( $_POST['ev_location'] );

	update_post_meta( $post_id, 'ev_date', $date );
	update_post_meta( $post_id, 'ev_price', $price );
	update_post_meta( $post_id, 'ev_location', $location );
}

class Event {
	private $_supports 		= array( 'title' , 'editor' , 'thumbnail' );
	private $_meta_boxes 	= array( 
			array( 'name' => 'detail' , 'context' => 'side' , 'priority' => 'default' ) 
		);
	private $_custom_fields = array(
			array( 'name' => 'ev_date' , 		'type' => 'datepicker' , 'box' => 'detail' ),
			array( 'name' => 'ev_price' , 		'type' => 'number' , 'box' => 'detail' ),
			array( 'name' => 'ev_location' , 	'type' => 'text' , 'box' => 'detail' )
		);

	public function __construct() {
		register_activation_hook( __FILE__ , 'register_event_post_type' );
		add_action('admin_menu', 'add_event_setting_submenu');
	}

	public function add_support( $support )
	{
		array_push( $this->_supports , $support );
	}

	public function set_supports( $supports )
	{
		$this->_supports = $supports;
	}

	public function get_supports()
	{
		return $this->_supports;
	}

	public function add_event_meta_box( $meta_boxes )
	{
		array_push( $this->_meta_boxes , $meta_boxes );
	}

	public function set_meta_boxes( $meta_boxes )
	{
		$this->_meta_boxes = $meta_boxes;
	}

	public function get_meta_boxes()
	{
		return $this->_meta_boxes;
	}

	public function add_custom_field( $custom_field )
	{
		array_push( $this->_custom_fields , $custom_field );
	}

	public function set_custom_fields( $custom_fields )
	{
		$this->_custom_fields = $custom_fields;
	}

	public function get_custom_fields( $box = '' )
	{
		if ( $box === '' )
			return $this->_custom_fields;
		else
			return array_filter( $this->_custom_fields , function($cf) { return $cf['box'] === $box; } );
	}

	public function register_event_post_type()
	{
		$args = array(
			'labels'		=> array(
				'name'			=> 'Events',
				'singular_name'	=> 'Event',
				'menu_name'		=> 'Event',
				'add_new'		=> 'Add New',
				'add_new_item'	=> 'Add New Event',
				'edit_item'		=> 'Edit Event',
				'new_item'		=> 'New Event',
				'view_item'		=> 'View Event',
				'search_items'	=> 'Search Events',
				'not_found'		=> 'No events found',
				'not_found_in_trash' => 'No events found in Trash'
				),
			'description'	=> 'Event Management',
			'supports'		=> array( 'title' , 'editor' , 'thumbnail' ),
			'menu_position'	=> 5,
			'public'		=> true
		);
		register_post_type( 'event' , $args );
	}

	public function create_event_setting_table()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . "event_settings";
		$sql 		= "CREATE TABLE $table_name (
						id int(11) NOT NULL AUTO_INCREMENT,
						name tinytext DEFAULT '' NOT NULL,
						value text DEFAULT '' NOT NULL,
						UNIQUE KEY id (id)
						);";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$wpdb->insert( $table_name , array( 'name' => 'default_currency' , 	'value' => 'IDR' ) );
		$wpdb->insert( $table_name , array( 'name' => 'event_ticket' , 		'value' => 'disable' ) );
		$wpdb->insert( $table_name , array( 'name' => 'event_attendance' , 	'value' => 'disable' ) );
	}

	public function add_event_setting_submenu()
	{
		add_submenu_page( 'edit.php?post_type=event' , 'setting' , 'Setting' , 'manage_options' , 'event-setting' , function() { include 'templates/setting.php'; } );
	}

	public function install()
	{
	}
}
