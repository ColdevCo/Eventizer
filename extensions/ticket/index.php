<?php
/*
Extension Name: Ticket
Extension Description: To Manage Tickets
Extension Creator: CoLD
*/

include_once( 'functions/redirect.php' );
include_once( 'functions/widget.php' );

class EventTicket {

	public function __construct() {
		add_action( 'event_init', array( $this, 'init' ) );
		add_action( 'event_save', array( $this, 'save' ) );
	}

	public function save( $post_id ) {
		global $wpdb;

		$table_name = $wpdb->prefix . "event_tickets";
		$wpdb->insert( $table_name,
			array(
				'event_id' => $post_id,
				'name'     => $_POST['ev_ticket_name'],
				'quota'    => $_POST['ev_ticket_quota']
			)
		);
	}

	public function get_ticket_name() {
		global $wpdb, $post;

		$table_name = $wpdb->prefix . "event_tickets";
		$ticket     = $wpdb->get_row( "SELECT * FROM $table_name WHERE event_id = $post->ID" );

		return $ticket->name;
	}

	public function get_ticket_quota() {
		global $wpdb, $post;

		$table_name = $wpdb->prefix . "event_tickets";
		$ticket     = $wpdb->get_row( "SELECT * FROM $table_name WHERE event_id = $post->ID" );

		return $ticket->quota;
	}

	public function get_ticket_max_tickets_per_person() {
		global $wpdb, $post;

		$table_name = $wpdb->prefix . "event_tickets";
		$ticket     = $wpdb->get_row( "SELECT * FROM $table_name WHERE event_id = $post->ID" );

		return $ticket->max_tickets_per_person;
	}

	public function init() {
		$this->register_ticket_page();
		$this->enable();
		$this->render();
	}

	public function enable() {
		global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "
		CREATE TABLE IF NOT EXISTS {$wpdb->prefix}event_tickets (
						event_id int(11) NOT NULL,
						name tinytext DEFAULT '' NOT NULL,
						quota int(11) DEFAULT -1 NOT NULL,
						created_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
						max_tickets_per_person int(11) DEFAULT 1,
						UNIQUE KEY event_id (event_id)
						)";
        dbDelta( $sql );

        $sql = "
		CREATE TABLE IF NOT EXISTS {$wpdb->prefix}event_attendees (
						id int(11) AUTO_INCREMENT,
						event_id int(11) NOT NULL,
						email tinytext DEFAULT '' NOT NULL,
						first_name tinytext DEFAULT '' NOT NULL,
						last_name tinytext DEFAULT '' NOT NULL,
						phone tinytext DEFAULT '' NOT NULL,
						guest_no int(3),
						UNIQUE KEY id (id)
						)";
		dbDelta( $sql );
	}

	public function details_form() {
		include 'view/tickets-form.php';
	}

	public function render() {
		add_action( 'event_render', function() {
			add_action( 'add_meta_boxes', function() {
				add_meta_box( 'event-tickets-box', 'Event Tickets', array( $this , 'details_form' ) , 'event', 'normal', 'low' );
			} );
		} );
	}

	public function register_ticket_page() {
		add_action( 'admin_menu', function () {
			add_submenu_page( 'edit.php?post_type=event', 'Attendant', 'Attendant', 'manage_options', 'ticket-attendant', function () {
				include_once( 'view/ticket-manager.php' );
			} );
		} );
	}

}

return new EventTicket();