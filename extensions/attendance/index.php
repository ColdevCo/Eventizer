<?php
/*
Extension Name: Attendance
Extension Description: Attendance
Extension Creator: CoLD
*/

new EventAttendance;

class EventAttendance {

	public function save()
	{

	}

	public function add_field()
	{

	}

	public function install()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . "event_attendance";
		$sql 		= "CREATE TABLE $table_name (
						id int(11) NOT NULL AUTO_INCREMENT,
						event_id int(11) NOT NULL,
						email tinytext DEFAULT '' NOT NULL,
						first_name tinytext DEFAULT '' NOT NULL,
						last_name tinytext DEFAULT '' NOT NULL,
						phone tinytext DEFAULT '' NOT NULL,
						guest_no int(3) DEFAULT 1 NOT NULL,
						UNIQUE KEY id (id)
						);";
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	public function render()
	{

	}

}