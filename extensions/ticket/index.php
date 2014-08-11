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

        update_post_meta( $post_id, 'ev_using_tickets', $_POST[$post_id]['ev_using_tickets'] );

        $wpdb->delete( $table_name, array( 'event_id' => $post_id ), array( '%d' ) );

        $tickets = $_POST['ticket'];
        foreach( $tickets as $ticket ) {

            $wpdb->insert( $table_name,
                array(
                    'event_id' => $post_id,
                    'name'     => $ticket['name'],
                    'start_sell_date' => $ticket['start_sell_date'],
                    'stop_sell_date'  => $ticket['stop_sell_date'],
                    'min_buy' => $ticket['min_buy'],
                    'max_buy' => $ticket['max_buy'],
                    'quota'   => $ticket['quantity'],
                    'price'   => $ticket['price']
                )
            );
        }
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
		                id int(11) NOT NULL AUTO_INCREMENT,
						event_id int(11) NOT NULL,
						name tinytext DEFAULT '' NOT NULL,
						start_sell_date char(100),
						stop_sell_date char(100),
						min_buy int(11) DEFAULT 1 NOT NULL,
						max_buy int(11)DEFAULT 1 NOT NULL,
						quota int(11) DEFAULT -1 NOT NULL,
						price int(11) DEFAULT 0 NOT NULL,
						UNIQUE KEY id (id)
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
			add_submenu_page( 'edit.php?post_type=event', 'Attendees', 'Attendees', 'manage_options', 'event-attendees', function () {
				include_once( 'view/attendees.php' );
			} );
		} );
	}

}

return new EventTicket();