<?php
/**
 * Plugin Name: Eventizer
 * Plugin URI: http://www.coldev.co
 * Description: Event Management Plugin developed by Coldev
 * Version: 1.0
 * Author: Coldev
 * Author URI: http://www.coldev.co
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( '__EVENTIZER_ASSETS_PATH__'     , plugin_dir_path( __FILE__ ) . 'assets/' );
define( '__EVENTIZER_LIBRARIES_PATH__'  , plugin_dir_path( __FILE__ ) . 'lib/' );
define( '__EVENTIZER_TEMPLATES_PATH__'  , plugin_dir_path( __FILE__ ) . 'templates/' );
define( '__EVENTIZER_EXTENSIONS_PATH__' , plugin_dir_path( __FILE__ ) . 'extensions/' );
define( '__EVENTIZER_WIDGETS_PATH__'    , plugin_dir_path( __FILE__ ) . 'widgets/' );

define( '__EVENTIZER_EXTENSIONS_URL__'  , plugins_url() . '/extensions/' );
define( '__EVENTIZER_WIDGETS_URL__'     , plugins_url() . '/widgets/' );

include_once( 'lib/event_options.php' );
include_once( 'lib/mail_editor.php' );
include_once( 'lib/template_helpers.php' );
include_once( 'lib/extension.php' );
include_once( 'lib/widget.php' );
include_once( 'lib/shortcodes.php' );

register_activation_hook( __FILE__ , function() {
	add_option( 'Install_Eventizer_Setting', 'true' );
} );

if ( ! class_exists( 'Eventizer' ) ) :

class Eventizer {

    public function __construct() {
		add_action( 'init' , array( $this , 'init' ) );
        do_action( 'eventizer_init' );

		add_action( 'save_post' , array( $this , 'save' ) );
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
			'supports'		=> array( 'title', 'editor', 'excerpt', 'thumbnail' ),
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

		$table_name = $wpdb->prefix . "eventizer_options";
		$sql 		= "CREATE TABLE {$table_name} (
						id int(11) NOT NULL AUTO_INCREMENT,
						name tinytext DEFAULT '' NOT NULL,
						value text DEFAULT '' NOT NULL,
						UNIQUE KEY id (id)
						);";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$wpdb->insert( $table_name , array( 'name' => 'default_currency' , 	'value' => 'IDR' ) );
		$wpdb->insert( $table_name , array( 'name' => 'enabled_extensions' , 'value' => '' ) );
	}

    public function create_event_mails_table()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . "eventizer_mails";
        $sql = "
		CREATE TABLE IF NOT EXISTS {$table_name} (
		                id int(11) NOT NULL AUTO_INCREMENT,
						subject tinytext DEFAULT '',
						content text DEFAULT '',
						context tinytext DEFAULT '',
						UNIQUE KEY id (id)
						)";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );

        $wpdb->insert( $table_name , array( 'subject' => 'Thanks' , 'content' => 'Test', 'context' => 'Order' ) );
    }

    public function add_events_page()
    {
        $my_post = array(
            'post_type'     => 'page',
            'post_title'    => 'Events',
            'post_name'     => 'event_list',
            'post_status'   => 'publish',
        );
        wp_insert_post( $my_post );
    }

    public function register_default_event_list_template()
    {
        add_filter( 'page_template', function( $page_template ){

            if ( is_page( 'event_list' ) ) {
                if( file_exists( __EVENTIZER_TEMPLATES_PATH__. 'event_list_template.php' ) )
                    $page_template = __EVENTIZER_TEMPLATES_PATH__. 'event_list_template.php';
            }

            return $page_template;
        });
    }

    public function register_default_single_event_template()
    {
        add_filter('single_template', function( $single ){
            global $wp_query, $post;

            if ( $post->post_type == "event" ){
                if( file_exists( __EVENTIZER_TEMPLATES_PATH__. 'single_event_default.php' ) )
                    $single = __EVENTIZER_TEMPLATES_PATH__ . 'single_event_default.php';
            }
            return $single;
        });
    }

	public function init()
	{
		$this->register_event_post_type();
        $this->register_event_taxonomies();
        $this->register_default_single_event_template();
        $this->register_default_event_list_template();
		$this->render();

		if( is_admin() && get_option( 'Install_Eventizer_Setting' ) == 'true' ) {
			$this->create_event_setting_table();
            $this->create_event_mails_table();
            $this->add_events_page();
			delete_option( 'Install_Eventizer_Setting' );
        }

        add_action( 'admin_menu' , function() {
			add_submenu_page( 'edit.php?post_type=event' , 'setting' , 'Setting' , 'manage_options' , 'event-setting' , function() {

                $setting_tabs = array();
                $setting_tabs['general'] = __EVENTIZER_TEMPLATES_PATH__ . 'setting-general.php';
                $setting_tabs['mail']    = __EVENTIZER_TEMPLATES_PATH__ . 'setting-mail.php';

                $setting_tabs = apply_filters( 'ev_setting_tabs', $setting_tabs );

                include 'templates/setting.php';
            } );
		} );
	}

	public function details_form()
	{
		wp_nonce_field( 'ev_details_box', 'ev_details_box_nonce' );

        include 'templates/event-details-form.php';
	}

	public function render()
	{
		add_action( 'add_meta_boxes', function() {
			add_meta_box( 'event-details-box', 'Event Details', array( $this , 'details_form' ) , 'event', 'normal', 'low' );
		} );

		do_action( 'eventizer_render' );
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


        $event      = $_POST[ $post_id ];

        /* Event Date & Time */
        $allday     = $event['ev_allday'];
        $start_date = $event['ev_start_date'];
        $end_date   = $event['ev_end_date'];
        $start_time = $event['ev_start_time-hour'] . ':' . $event['ev_start_time-minute'] . ' ' . $event['ev_start_time-meridiem'];
        $end_time   = $event['ev_end_time-hour'] . ':' . $event['ev_end_time-minute'] . ' ' . $event['ev_end_time-meridiem'];

        update_post_meta( $post_id, 'ev_allday', $allday );
        update_post_meta( $post_id, 'ev_start_date', date('Y-m-d', strtotime($start_date)) );
        update_post_meta( $post_id, 'ev_end_date', date('Y-m-d', strtotime($end_date)) );

        update_post_meta( $post_id, 'ev_start_time', $start_time );
        update_post_meta( $post_id, 'ev_end_time', $end_time );

        /* Event Location */
        $latLng = implode( ',', array($event['ev_venue_location-lat'], $event['ev_venue_location-lng']) );

        update_post_meta( $post_id, 'ev_venue_name', $event['ev_venue_name'] );
        update_post_meta( $post_id, 'ev_venue_address', $event['ev_venue_address'] );
        update_post_meta( $post_id, 'ev_venue_location', $latLng );

		do_action( 'eventizer_save' , $post_id );
	}
}

endif;

return new Eventizer();