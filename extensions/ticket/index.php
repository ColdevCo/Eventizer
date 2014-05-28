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
		add_action( 'event_init' , array( $this , 'init' ) );
		add_action( 'event_save' , array( $this , 'save' ) );
	}

	public function save( $post_id )
	{
		global $wpdb;

		$table_name = $wpdb->prefix . "event_tickets";
		$wpdb->insert( $table_name , 
			array( 
				'event_id' => $post_id , 
				'name' => $_POST[ 'ev_ticket_name' ] , 
				'quota' => $_POST[ 'ev_ticket_quota' ] 
				) 
			);
	}

	public function get_ticket_name()
	{
		global $wpdb, $post;

		$table_name = $wpdb->prefix . "event_tickets";
		$ticket = $wpdb->get_row("SELECT * FROM $table_name WHERE event_id = $post->ID");

		return $ticket->name;
	}

	public function get_ticket_quota()
	{
		global $wpdb, $post;

		$table_name = $wpdb->prefix . "event_tickets";
		$ticket = $wpdb->get_row("SELECT * FROM $table_name WHERE event_id = $post->ID");

		return $ticket->quota;
	}

	public function init()
	{
		$this->enable();
		$this->render();
	}

	public function enable()
	{
		global $wpdb;

		$sql 		= "
		CREATE TABLE IF NOT EXISTS {$wpdb->prefix}event_tickets (
						event_id int(11) NOT NULL,
						name tinytext DEFAULT '' NOT NULL,
						quota int(11) DEFAULT -1 NOT NULL,
						created_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
						UNIQUE KEY event_id (event_id)
						);
		CREATE TABLE IF NOT EXISTS {$wpdb->prefix}event_attendances (
						id int(11) AUTO_INCREMENT,
						event_id int(11) NOT NULL,
						email tinytext DEFAULT '' NOT NULL,
						first_name tinytext DEFAULT '' NOT NULL,
						last_name tinytext DEFAULT '' NOT NULL,
						phone tinytext DEFAULT '' NOT NULL,
						guest_no int(3),
						UNIQUE KEY id (id)
						)";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	public function render()
	{
		add_filter( 'add_event_fields' , function( $fields ) {
			$fields[] = text( 'ev_ticket_name' , array( 'label' => 'Ticket Name' , 'value' =>  $this->get_ticket_name() ) );
			$fields[] = text( 'ev_ticket_quota' , array( 'label' => 'Quota' , 'value' => $this->get_ticket_quota() ) );
			return $fields;
		} );
	}

}

return new EventTicket();