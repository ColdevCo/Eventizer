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

        $tickets = $_POST['ticket'];
        foreach( $tickets as $ticket ) {

            if ( isset($ticket['id']) ) {
                if ( $ticket['delete'] === 'true' ) {
                    $wpdb->delete( $table_name, array( 'id' => $ticket['id'] ), array( '%d' ) );
                } else {
                    $wpdb->update(
                        $table_name,
                        array(
                            'event_id' => $post_id,
                            'name'     => $ticket['name'],
                            'start_sell_date' => $ticket['start_sell_date'],
                            'stop_sell_date'  => $ticket['stop_sell_date'],
                            'min_buy' => $ticket['min_buy'],
                            'max_buy' => $ticket['max_buy'],
                            'quota'   => $ticket['quantity'],
                            'price'   => $ticket['price']
                        ),
                        array( 'id' => $ticket['id'] ),
                        array( '%d', '%s', '%s', '%s', '%d', '%d', '%d', '%d' ),
                        array( '%d' )
                    );
                }
            } else {
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
	}

    public static function get_event_tickets( $event_id, $in_date_range = false ) {
        global $wpdb;

        $table_name = $wpdb->prefix . "event_tickets";

        $conditions = "";
        if ( $in_date_range ) {
            $current_datetime = date("Y-m-d H:i:s");
            $conditions = " AND `start_sell_date` <= '{$current_datetime}' AND `stop_sell_date` >= '{$current_datetime}'";
        }

        $tickets = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE `event_id` = {$event_id}" . $conditions );

        return $tickets;
    }

    public static function get_attendees( $filter = array(), &$page = 1 ) {
        global $wpdb;

        $table_name = $wpdb->prefix . "event_attendees";

        $filters = array();
        if ( is_array($filter) and ! empty($filter) ) {
            foreach ( $filter as $key => $value ) {

                if ( ! is_array( $value ) ) {
                    array_push( $filters, "`{$key}` LIKE '%{$value}%'");
                }

                if ( is_array( $value ) and ! empty( $value ) ) {
                    array_push( $filters, "`{$key}` IN (" . implode(',', $value) . ")");
                }
            }
        }

        $sql = "SELECT * FROM {$table_name}";
        $sql .= empty($filters) ? '' : " WHERE " . implode(' AND ', $filters);

        $total = $wpdb->get_var( "SELECT COUNT(1) FROM (${sql}) AS `total`" );
        $items_per_page = 10;

        $attendees = $wpdb->get_results( $sql . " LIMIT " . (($page - 1) * $items_per_page) . ",{$items_per_page}" );
        $paginate = paginate_links( array(
            'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'format' => '?paged=%#%',
            'current' => $page,
            'total' => ceil($total / $items_per_page)
        ) );

        return (object) array('data' => $attendees, 'paginate' => $paginate);
    }

    public static function get_ticket_name( $ticket_id ) {
        global $wpdb;

        $table_name = $wpdb->prefix . "event_tickets";
        $ticket     = $wpdb->get_row( "SELECT * FROM {$table_name} WHERE `id` = {$ticket_id}" );

        return $ticket->name;
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
						start_sell_date datetime,
						stop_sell_date datetime,
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
						email tinytext DEFAULT '' NOT NULL,
						name tinytext DEFAULT '' NOT NULL,
						phone tinytext DEFAULT '' NOT NULL,
						event_id int(11) NOT NULL,
						ticket_id int(11) NOT NULL,
						quantity int(11),
						UNIQUE KEY id (id)
						)";
		dbDelta( $sql );

        if ( get_event_options( 'widget_ticket_featured_event' ) == '' ) {
            add_event_options( 'widget_ticket_featured_event', 'upcoming' );
        }
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