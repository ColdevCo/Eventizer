<?php

global $post;
$post_id = $post->ID;

?>
<style type="text/css">

    .form-ticket {
        background-color: #F1F1F1;
        margin: 0 -12px;
        padding: 40px;
    }

    .hidden {
        display: none;
    }

    .input-group.use_tickets {
        margin: 20px 40px 20px 33px;
    }

    label.input-ev_using_tickets {
        margin-right: 30px;
    }

    div.ticket-list {
        width: 100%;
        margin: 20px 0 40px;
    }

    div.ticket-list table {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
    }

    div.ticket-list table th,
    div.ticket-list table td {
        padding: 8px;
        font-size: 14px;
        line-height: 14pt;
        text-align: left;
    }

    div.ticket-list table th {
        border-bottom: 1px solid #111;
        vertical-align: bottom;
    }

    div.ticket-list table td {
        border-bottom: 1px solid #EAEAEA;
        vertical-align: middle;
    }

    div.ticket-list table button.delete {
        display: inline-block;
        cursor: pointer;
        background-color: #9A1616;
        color: #FFF;
        padding: 2px 8px;
        text-align: center;
        vertical-align: middle;
        border: 1px solid transparent;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
    }

    #ev_ticket_start_sell,
    #ev_ticket_stop_sell {
        border: 1px #d9d9d9 solid;
        width: 175px;
        height: 100%;
        margin: 0 15px 0 0;
        padding: 5px;
    }

    .input-ev_ticket_start_sell-time,
    .input-ev_ticket_stop_sell-time {
        display: inline-block;
    }

    #ev_ticket_name {
        width: 400px;
        height: 100%;
        padding: 5px;
    }

    #ev_ticket_min {
        width: 175px;
        height: 100%;
        padding: 5px;
    }

    #ev_ticket_max {
        width: 175px;
        height: 100%;
        padding: 5px;
    }

    #ev_ticket_qty {
        width: 175px;
        height: 100%;
        padding: 5px;
    }

    #ev_ticket_price {
        width: 175px;
        height: 100%;
        padding: 5px;
    }

    #ev_ticket_submit {
        display: inline-block;
        cursor: pointer;
        background-color: #16499A;
        color: #FFF;
        padding: 6px 30px;
        margin: 25px 0 0;
        text-align: center;
        vertical-align: middle;
        border: 1px solid transparent;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
    }

</style>

<div class="input-group use_tickets">

    <?php $form = new Form( $post_id ); ?>

    <?= HTML::label('Using Tickets?', 'ev_using_tickets', array('class' => 'left')); ?>

    <?= $form->radio('Yes', 'ev_using_tickets', array('class' => 'input-ev_using_tickets', 'checked' => true)); ?>
    <?= $form->radio('No, it\'s free event', 'ev_using_tickets', array('class' => 'input-ev_using_tickets', 'value' => 'no')); ?>

    <?php
    $hidden = '';
    $use_tickets = get_post_meta( $post_id, 'ev_using_tickets', true );

    if ( $use_tickets != '' && $use_tickets == 'no' ) {
        $hidden = ' hidden';
    }
    ?>

</div>

<div class="form-ticket <?= $hidden; ?>">

    <div class="input-group">

        <?= HTML::label('Name', 'ev_ticket_name', array('class' => 'left')); ?>
        <?= HTML::text('ev_ticket_name', array('id' => 'ev_ticket_name')); ?>

    </div>

    <div class="input-group">

        <?= HTML::label('Price', 'ev_ticket_price', array('class' => 'left')); ?>
        <?= HTML::text('ev_ticket_price', array('id' => 'ev_ticket_price')); ?>

    </div>

    <div class="input-group">

        <?= HTML::label('Quantity', 'ev_ticket_qty', array('class' => 'left')); ?>
        <?= HTML::text('ev_ticket_qty', array('id' => 'ev_ticket_qty')); ?>

    </div>

    <hr class="input-divider" />

    <div class="input-group">

        <?= HTML::label('Start Selling Date', 'ev_ticket_start_sell', array('class' => 'left')); ?>

        <?= HTML::datepicker('ev_ticket_start_sell-date', array('id' => 'ev_ticket_start_sell')); ?>
        <?= HTML::timepicker('ev_ticket_start_sell-time', array('class' => 'timepicker input-ev_ticket_start_sell-time')); ?>

    </div>

    <div class="input-group">

        <?= HTML::label('Stop Selling Date', 'ev_ticket_stop_sell', array('class' => 'left')); ?>

        <?= HTML::datepicker('ev_ticket_stop_sell', array('id' => 'ev_ticket_stop_sell')); ?>
        <?= HTML::timepicker('ev_ticket_stop_sell-time', array('class' => 'timepicker input-ev_ticket_start_sell-time')); ?>

    </div>

    <div class="input-group">

        <?= HTML::label('Min. Tickets Buy', 'ev_ticket_min', array('class' => 'left')); ?>
        <?= HTML::text('ev_ticket_min', array('id' => 'ev_ticket_min')); ?>

    </div>

    <div class="input-group">

        <?= HTML::label('Max. Tickets Buy', 'ev_ticket_max', array('class' => 'left')); ?>
        <?= HTML::text('ev_ticket_max', array('id' => 'ev_ticket_max')); ?>

    </div>

    <div class="input-group">

        <?= HTML::button('Add', array('id' => 'ev_ticket_submit')); ?>

    </div>

</div>

<div class="ticket-list <?= $hidden; ?>">
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Sell Date</th>
            <th>Min / Max Buy</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        global $wpdb, $post;

        $table_name = $wpdb->prefix . "event_tickets";
        $tickets    = $wpdb->get_results( "SELECT * FROM `{$table_name}` WHERE `event_id` = {$post->ID}" );

        $count = 0;
        foreach ( $tickets as $ticket ) :
        ?>
            <input type='hidden' name='ticket[<?= $count; ?>][id]' value='<?= $ticket->id; ?>' />
            <input type='hidden' name='ticket[<?= $count; ?>][delete]' value='false' />
            <tr>
                <input type='hidden' name='ticket[<?= $count; ?>][name]' value='<?= $ticket->name; ?>' />
                <input type='hidden' name='ticket[<?= $count; ?>][start_sell_date]' value='<?= $ticket->start_sell_date; ?>' />
                <input type='hidden' name='ticket[<?= $count; ?>][stop_sell_date]' value='<?= $ticket->stop_sell_date; ?>' />
                <input type='hidden' name='ticket[<?= $count; ?>][min_buy]' value='<?= $ticket->min_buy; ?>' />
                <input type='hidden' name='ticket[<?= $count; ?>][max_buy]' value='<?= $ticket->max_buy; ?>' />
                <input type='hidden' name='ticket[<?= $count; ?>][quantity]' value='<?= $ticket->quota; ?>' />
                <input type='hidden' name='ticket[<?= $count; ?>][price]' value='<?= $ticket->price; ?>' />
                <td><?= $ticket->name; ?></td>
                <td><?= 'From: ' . $ticket->start_sell_date . '<br />To: ' . $ticket->stop_sell_date; ?></td>
                <td><?= 'Min: ' . $ticket->min_buy . '<br />Max: ' . $ticket->max_buy; ?></td>
                <td><?= $ticket->quota; ?></td>
                <td><?= get_event_options( 'default_currency' ) . ' ' . number_format($ticket->price); ?></td>
                <td><button class="delete">Delete</button></td>
            </tr>
        <?php $count++; endforeach; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){

        jQuery('.input-ev_using_tickets').bind('change', function(){
            jQuery('.form-ticket').toggleClass('hidden');
            jQuery('.ticket-list').toggleClass('hidden');
        });

        jQuery('#ev_ticket_start_sell').datepicker({
            dateFormat: 'MM d, yy',
            onClose: function( selectedDate ) {
                jQuery('#ev_ticket_stop_sell').datepicker( "option", "minDate", selectedDate );
            }
        });

        jQuery('#ev_ticket_stop_sell').datepicker({
            dateFormat: 'MM d, yy',
            onClose: function( selectedDate ) {
                jQuery('#ev_ticket_start_sell').datepicker( "option", "maxDate", selectedDate );
            }
        });

        var ticket_count = jQuery('.ticket-list').find('tbody').children('tr').length;

        jQuery('#ev_ticket_submit').bind('click', function(e){

            e.preventDefault();

            var ticket_name = jQuery('#ev_ticket_name').val();
            var ticket_price = jQuery('#ev_ticket_price').val();
            var ticket_quantity = jQuery('#ev_ticket_qty').val();

            var ticket_start_sell_date = jQuery('#ev_ticket_start_sell').val();
            var ticket_start_sell_time = jQuery('select[name=ev_ticket_start_sell-time-hour]').val() + " : " + jQuery('select[name=ev_ticket_start_sell-time-minute]').val() + "&nbsp; " + jQuery('select[name=ev_ticket_start_sell-time-meridiem]').val();
            var ticket_stop_sell_date = jQuery('#ev_ticket_stop_sell').val();
            var ticket_stop_sell_time = jQuery('select[name=ev_ticket_stop_sell-time-hour]').val() + " : " + jQuery('select[name=ev_ticket_stop_sell-time-minute]').val() + "&nbsp; " + jQuery('select[name=ev_ticket_stop_sell-time-meridiem]').val();

            var ticket_start_sell = ticket_start_sell_date + " " + ticket_start_sell_time;
            var ticket_stop_sell = ticket_stop_sell_date + " " + ticket_stop_sell_time;

            var ticket_min_buy = jQuery('#ev_ticket_min').val();
            var ticket_max_buy = jQuery('#ev_ticket_max').val();

            if ( ticket_name == "" || ticket_price == "" || ticket_quantity == "" || ticket_start_sell == "" || ticket_stop_sell == "" || ticket_min_buy == "" || ticket_max_buy == "" ) {
                alert('Please fill all input in ticket form');
                return;
            }

            var ticket = "";
            ticket += "<tr>";
            ticket += "<input type='hidden' name='ticket[" + ticket_count + "][name]' value='" + ticket_name + "' />";
            ticket += "<input type='hidden' name='ticket[" + ticket_count + "][start_sell_date]' value='" + ticket_start_sell + "' />";
            ticket += "<input type='hidden' name='ticket[" + ticket_count + "][stop_sell_date]' value='" + ticket_stop_sell + "' />";
            ticket += "<input type='hidden' name='ticket[" + ticket_count + "][min_buy]' value='" + ticket_min_buy + "' />";
            ticket += "<input type='hidden' name='ticket[" + ticket_count + "][max_buy]' value='" + ticket_max_buy + "' />";
            ticket += "<input type='hidden' name='ticket[" + ticket_count + "][quantity]' value='" + ticket_quantity + "' />";
            ticket += "<input type='hidden' name='ticket[" + ticket_count + "][price]' value='" + ticket_price + "' />";
            ticket += "<td>" + ticket_name + "</td>";
            ticket += "<td>From:&nbsp; " + ticket_start_sell + "<br />To:&nbsp; " + ticket_stop_sell + "</td>";
            ticket += "<td>Min:&nbsp; " + ticket_min_buy + "<br />Max:&nbsp; " + ticket_max_buy + "</td>";
            ticket += "<td>" + ticket_quantity + "</td>";
            ticket += "<td><?= get_event_options( 'default_currency' ) ?> " + ticket_price.replace(/./g, function(c, i, a) {
                return i && c !== "." && !((a.length - i) % 3) ? ',' + c : c;
            }); + "</td>";
            ticket += "<td><button class='delete'>Delete</button></td>";
            ticket += "</tr>";

            jQuery('.ticket-list').find('tbody').append( ticket );

            ticket_count++;

            jQuery('.ticket-list button.delete').unbind('click');

            jQuery('.ticket-list button.delete').bind('click', function(e) {

                e.preventDefault();

                jQuery(this).parents('tr').prev().val('true');
                jQuery(this).parents('tr').remove();
            });

        });

        jQuery('.ticket-list button.delete').bind('click', function(e) {

            e.preventDefault();

            jQuery(this).parents('tr').prev().val('true');
            jQuery(this).parents('tr').remove();
        });
    });
</script>