<?php

function add_mail( $data ) {
    global $wpdb;

    $table_name = $wpdb->prefix . "event_mails";

    $wpdb->insert( $table_name, array(
        'subject' => $data['cem_mail_editor_subject'],
        'content' => $data['cem_mail_editor_content'],
        'context' => $data['cem_mail_editor_context']
    ) );
}

function update_mail( $mail_id, $data ) {
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

function delete_mail( $context ) {
    global $wpdb;

    $table_name = $wpdb->prefix . "event_mails";

    $wpdb->delete( $table_name, array( 'context' => $context ) );
}

function get_all_mails() {
    global $wpdb;

    $table_name = $wpdb->prefix . "event_mails";

    $tickets = $wpdb->get_results( "SELECT * FROM `{$table_name}`" );

    return $tickets;
}

function get_mail( $id = 0 ) {
    global $wpdb;

    $table_name = $wpdb->prefix . "event_mails";

    $tickets = $wpdb->get_results( "SELECT * FROM `{$table_name}` WHERE `id` >= {$id} ORDER BY `id` ASC LIMIT 1" );

    return $tickets[0];
}

function get_mail_by_context( $context ) {
    global $wpdb;

    $table_name = $wpdb->prefix . "event_mails";

    $tickets = $wpdb->get_results( "SELECT * FROM `{$table_name}` WHERE `context` = '{$context}' ORDER BY `id` ASC LIMIT 1" );

    return $tickets[0];
}