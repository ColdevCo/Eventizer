<?php
/**
 * Plugin Name: Coldev Event Management
 * Plugin URI: http://www.coldev.co
 * Description: Event Management Plugin developed by Coldev
 * Version: 1.0
 * Author: Coldev
 * Author URI: http://www.coldev.co
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( '__EVENT_ASSETS_PATH__' , plugin_dir_path( __FILE__ ) . 'assets/' );
define( '__EVENT_LIBRARIES_PATH__' , plugin_dir_path( __FILE__ ) . 'lib/' );
define( '__EVENT_I18N_PATH__' , plugin_dir_path( __FILE__ ) . 'i18n/' );
define( '__EVENT_TEMPLATE_PATH__' , plugin_dir_path( __FILE__ ) . 'templates/' );
define( '__EVENT_EXTENSION_PATH__' , plugin_dir_path( __FILE__ ) . 'extensions/' );
define( '__EVENT_WIDGET_PATH__' , plugin_dir_path( __FILE__ ) . 'widgets/' );

define( '__EVENT_EXTENSION_URL__' , plugins_url() . '/extensions/' );
define( '__EVENT_WIDGET_URL__' , plugins_url() . '/widgets/' );

include_once( 'lib/event_options.php' );
include_once( 'lib/field-type.php' );
include_once( 'lib/template_helpers.php' );
include_once( 'lib/extension.php' );
include_once( 'lib/widget.php' );
include_once( 'lib/shortcodes.php' );

register_activation_hook( __FILE__ , function() {
	add_option( 'Install_Event_Setting', 'true' );	
} );

if ( ! class_exists( 'Event' ) ) :

class Event {
	private $_supports = array( 'title', 'editor', 'thumbnail' );
	private $_custom_fields = array(
			array( 'name' => 'ev_start_time' , 'label' => 'Start Time' , 'type' => 'datetimepicker' ),
			array( 'name' => 'ev_end_time' , 'label' => 'End Time' , 'type' => 'datetimepicker' ),
			array( 'name' => 'ev_price' , 'label' => 'Price' , 'type' => 'text' ),
			array( 'name' => 'ev_location' , 'label' => 'Location' , 'type' => 'textarea' ),
			array( 'name' => 'ev_map' , 'label' => 'Map' , 'type' => 'gmap' )
		);

	public function __construct() {
		add_action( 'init' , array( $this , 'init' ) );
		do_action( 'event_init' );

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
				'menu_name'		=> 'Events',
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
			'public'		=> true,
			'menu_icon'		=> 'dashicons-calendar'
		);
		register_post_type( 'event' , $args );
	}

    public function register_event_taxonomies()
    {
        $event_category_labels = array(
            'name'          => _x( 'Categories', 'taxonomy general name' ),
            'singular_name' => _x( 'Category', 'taxonomy singular name' ),
            'search_items'  =>  __( 'Search Categories' ),
            'popular_items' => __( 'Popular Categories' ),
            'all_items'     => __( 'All Categories' ),
            'edit_item'     => __( 'Edit Category' ),
            'update_item'   => __( 'Update Category' ),
            'add_new_item'  => __( 'Add New Category' ),
            'menu_name'     => __( 'Categories' ),
        );
        $event_category = array(
            'hierarchical'      => true,
            'show_ui'           => true,
            'rewrite'           => array( 'slug' => 'event-category' ),
            'labels'            => $event_category_labels
        );

        register_taxonomy( 'event-category', 'event', $event_category );
        register_taxonomy( 'event-tag', 'event' );
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
		$wpdb->insert( $table_name , array( 'name' => 'enabled_extensions' , 	'value' => '' ) );
	}

	public function init()
	{
		$this->register_event_post_type();
        $this->register_event_taxonomies();
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
				case 'datepicker':
					$field = datepicker( $custom_field['name'] , array( 'label' => $custom_field['label'] ) );
					break;
				case 'datetimepicker':
					$field = datetimepicker( $custom_field['name'] , array( 'label' => $custom_field['label'] ) );
					break;
				case 'gmap':
					$field = gmap( $custom_field['name'] , array( 'label' => $custom_field['label'] ) );
					break;
				default:
					$field = text( $custom_field['name'] , array( 'label' => $custom_field['label'] ) );
			}

			array_push( $fields , $field );
		}
		
		$fields = apply_filters( 'add_event_fields', $fields );

		foreach ( $fields as $field ) {
			// echo $field;
		}

		include 'templates/event-details-form.php';
	}

	public function render()
	{
		add_action( 'add_meta_boxes', function() {
			add_meta_box( 'event-details-box', 'Event Details', array( $this , 'details_form' ) , 'event', 'normal', 'low' );
		} );

		do_action( 'event_render' );
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
			switch ( $custom_field['type'] ) {
				case 'datetimepicker':
					update_post_meta( $post_id, $custom_field['name'], date( 'Y-m-d H:i', strtotime( $_POST[ $custom_field['name'] ] ) ) );
					break;
				case 'gmap':
					update_post_meta( $post_id, $custom_field['name'] . '-lat', $_POST[ $custom_field['name'] . '-lat' ] );
					update_post_meta( $post_id, $custom_field['name'] . '-lng', $_POST[ $custom_field['name'] . '-lng' ] );
					break;
				default:
					update_post_meta( $post_id, $custom_field['name'], $_POST[ $custom_field['name'] ] );
					break;
			}
		}

		do_action( 'event_save' , $post_id );
	}
}

endif;

return new Event();