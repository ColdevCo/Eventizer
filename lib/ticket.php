<?php

/*
 * 
 *
 */

class EventTicket {

	public function save()
	{

	}

	public function enable()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . "event_tickets";
		$sql 		= "CREATE TABLE $table_name (
						event_id int(11) NOT NULL,
						name tinytext DEFAULT '' NOT NULL,
						quota int(11) DEFAULT -1 NOT NULL,
						created_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
						UNIQUE KEY event_id (event_id)
						);";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	public function render()
	{
		require "field-type.php";

		add_filter( 'add_fields' , function( $fields ) {
			$fields[] = text( 'ev_ticket_name', array( 'label' => 'Ticket Name' ) );
			$fields[] = text( 'ev_ticket_quota', array( 'label' => 'Quota' ) );
			return $fields;
		} );
	}

}