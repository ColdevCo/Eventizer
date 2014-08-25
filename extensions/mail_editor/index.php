<?php
/*
Extension Name: Mail Editor
Extension Description: To Edit Mail Content
Extension Creator: CoLD
*/

class EventMail {

    public function __construct() {
        add_action( 'event_init', array( $this, 'init' ) );
    }

    public function init() {
        $this->create_event_mails_table();
        $this->register_to_setting();
    }

    public function create_event_mails_table() {
        global $wpdb;

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $sql = "
		CREATE TABLE IF NOT EXISTS {$wpdb->prefix}event_mails (
		                id int(11) NOT NULL AUTO_INCREMENT,
						subject tinytext DEFAULT '',
						content text DEFAULT '',
						context tinytext DEFAULT '',
						UNIQUE KEY id (id)
						)";
        dbDelta( $sql );
    }

    public function register_to_setting() {
        add_filter( 'ev_setting_tabs', function( $tabs ) {

            $tabs['mail'] = __EVENT_EXTENSION_PATH__ . 'mail_editor/view/setting-mail.php';

            return $tabs;
        });
    }

    public static function get_mails() {
        global $wpdb;

        $table_name = $wpdb->prefix . "event_mails";

        $tickets = $wpdb->get_results( "SELECT * FROM `{$table_name}`" );

        return $tickets;
    }

    public static function get_mail( $mail_id = 0 ) {
        global $wpdb;

        $table_name = $wpdb->prefix . "event_mails";

        $tickets = $wpdb->get_results( "SELECT * FROM `{$table_name}` WHERE `id` >= {$mail_id} ORDER BY `id` ASC LIMIT 1" );

        return $tickets[0];
    }

    public static function get_mail_by_context( $context ) {
        global $wpdb;

        $table_name = $wpdb->prefix . "event_mails";

        $tickets = $wpdb->get_results( "SELECT * FROM `{$table_name}` WHERE `context` = '{$context}' ORDER BY `id` ASC LIMIT 1" );

        return $tickets[0];
    }

    public static function update( $mail_id, $data ) {
        global $wpdb;

        $table_name = $wpdb->prefix . "event_mails";

        $wpdb->update(
            $table_name,
            array(
                'subject' => $data['cem_mail_editor_subject'],
                'content' => $data['cem_mail_editor_content']
            ),
            array( 'id' => $mail_id ),
            array( '%s', '%s' ),
            array( '%d' )
        );
    }
}

return new EventMail();