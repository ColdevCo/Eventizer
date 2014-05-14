<?php
/**
 * Plugin Name: Test Event
 * Plugin URI: http://www.facebook.com
 * Description: Test Event
 * Version: 1.0
 * Author: Me
 * Author URI: http://www.facebook.com
 */

define( '__EVENT_ASSETS_PATH__' , plugin_dir_path( __FILE__ ) . 'assets/' );
define( '__EVENT_LIBRARIES_PATH__' , plugin_dir_path( __FILE__ ) . 'lib/' );
define( '__EVENT_I18N_PATH__' , plugin_dir_path( __FILE__ ) . 'i18n/' );
define( '__EVENT_TEMPLATE_PATH__' , plugin_dir_path( __FILE__ ) . 'templates/' );
define( '__EVENT_EXTENSION_PATH__' , plugin_dir_path( __FILE__ ) . 'extensions/' );
define( '__EVENT_WIDGET_PATH__' , plugin_dir_path( __FILE__ ) . 'widget/' );

register_activation_hook( __FILE__ , function() {
	add_option( 'Install_Event_Setting', 'true' );	
} );

$event = new Event;

class Event {
	private $_supports = array( 'title', 'editor', 'thumbnail' );
	private $_custom_fields = array(
			array( 'name' => 'ev_date' , 'label' => 'Date' , 'type' => 'datepicker' ),
			array( 'name' => 'ev_price' , 'label' => 'Price' , 'type' => 'text' ),
			array( 'name' => 'ev_location' , 'label' => 'Location' , 'type' => 'textarea' )
		);

	public function __construct() {
		add_action( 'init' , array( $this , 'init' ) );
		add_action( 'save_post' , array( $this , 'save' ) );
	}

	public function add_support( $support )
	{
		array_push( $this->_supports , $support );
	}

	public function set_supports( $supports )
	{
		$this->_supports = $supports;
	}

	public function get_supports() {
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

	public function get_meta_boxes() {
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

	public function get_custom_fields()
	{
		return $this->_custom_fields;
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
			'supports'		=> $this->_supports,
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

	public function init()
	{
		$this->register_event_post_type();
		$this->render();

		if( is_admin() && get_option( 'Install_Event_Setting' ) == 'true' ) {
			$this->create_event_setting_table();
			delete_option( 'Install_Event_Setting' );
		}

		add_action( 'admin_menu' , function() {
			add_submenu_page( 'edit.php?post_type=event' , 'setting' , 'Setting' , 'manage_options' , 'event-setting' , function() { include 'templates/setting.php'; } );		
		} );
	}

	public function details_form()
	{
		require "lib/field-type.php";

		wp_nonce_field( 'ev_details_box', 'ev_details_box_nonce' );

		$fields   = array();

		foreach( $this->_custom_fields as $custom_field ) {
			$field = '';
			switch( $custom_field['type'] ) {
				case 'text': 
					$field = text( $custom_field['name'] , array( 'label' => $custom_field['label'] ) );
					break;
				case 'textarea': 
					$field = textarea( $custom_field['name'] , array( 'label' => $custom_field['label'] ) );
					break;
				default:
					$field = text( $custom_field['name'] , array( 'label' => $custom_field['label'] ) );
			}

			array_push( $fields , $field );
		}
		
		$fields = apply_filters( 'add_event_fields', $fields );

		foreach ( $fields as $field ) {
			echo $field;
		}
	}

	public function render()
	{
		add_action( 'add_meta_boxes', function() {
			add_meta_box( 'event-details-box', 'Detail', array( $this , 'details_form' ) , 'event', 'normal', 'low' );
		} );

		do_action( 'event_render' );

		$tokens = token_get_all( file_get_contents( __EVENT_EXTENSION_PATH__ . 'ticket/index.php' ) );
		$comments = array();
		foreach($tokens as $token) {
			if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
				$comments[] = $token[1];
			}
		}

		print_r( explode( "\n" , $comments[0] ) );
	}

	public function scan_extensions()
	{
		$ext_dir = plugin_dir_path( __FILE__ ) . 'lib/';
		$dh = opendir( $ext_dir );

		$exts = [];
		while ( false !== ( $filename = readdir( $dh ) ) ) {
			if( is_dir( $filename ) && $filename != '.' && $filename != '..' ) {
				array_push( $exts , $filename );
			}
		}

		return $exts;
	}

	public function load_enabled_extensions()
	{

	}

	public function save( $post_id )
	{
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

		foreach( $this->_custom_fields as $custom_field ) {
			update_post_meta( $post_id, $custom_field['name'], $custom_field['name'] );
		}

		do_action( 'event_save' , $post_id );
	}
}
