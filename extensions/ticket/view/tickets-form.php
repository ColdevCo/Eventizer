<style type="text/css">
    table {
        border: 0;
    }

    table tr:not(:last-child) {
        border-bottom: 1px solid #333;
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
</style>

<?php $form = new Form( $post_id ); ?>

<div class="input-group">

    <?= HTML::label('Using Tickets?', 'ev_using_tickets', array('class' => 'left')); ?>

    <?= $form->radio('Yes', 'ev_using_tickets', array('class' => 'input-ev_allday', 'checked' => true)); ?>
    <?= $form->radio('No', 'ev_using_tickets', array('class' => 'input-ev_allday')); ?>

</div>

<div class="input-group">

    <?= HTML::label('Name', 'ev_ticket_name', array('class' => 'left')); ?>
    <?= $form->text('ev_ticket_name', array('id' => 'ev_ticket_name')); ?>

</div>

<div class="input-group">

    <?= HTML::label('Price', 'ev_ticket_price', array('class' => 'left')); ?>
    <?= $form->text('ev_ticket_price', array('id' => 'ev_ticket_price')); ?>

</div>

<div class="input-group">

    <?= HTML::label('Start Selling Date', 'ev_ticket_start_sell', array('class' => 'left')); ?>

    <?= $form->datepicker('ev_ticket_start_sell-date', array('id' => 'ev_ticket_start_sell')); ?>
    <?= $form->timepicker('ev_ticket_start_sell-time', array('class' => 'timepicker input-ev_ticket_start_sell-time')); ?>

</div>

<div class="input-group">

    <?= HTML::label('Stop Selling Date', 'ev_ticket_stop_sell', array('class' => 'left')); ?>

    <?= $form->datepicker('ev_ticket_stop_sell', array('id' => 'ev_ticket_stop_sell')); ?>
    <?= $form->timepicker('ev_ticket_stop_sell-time', array('class' => 'timepicker input-ev_ticket_start_sell-time')); ?>

</div>

<div class="input-group">

    <?= HTML::label('Min. Tickets Buy', 'ev_ticket_min', array('class' => 'left')); ?>
    <?= $form->text('ev_ticket_min', array('id' => 'ev_ticket_min')); ?>

</div>

<div class="input-group">

    <?= HTML::label('Max. Tickets Buy', 'ev_ticket_max', array('class' => 'left')); ?>
    <?= $form->text('ev_ticket_max', array('id' => 'ev_ticket_max')); ?>

</div>

<div class="input-group">

    <?= HTML::label('Quantity', 'ev_ticket_qty', array('class' => 'left')); ?>
    <?= $form->text('ev_ticket_qty', array('id' => 'ev_ticket_qty')); ?>

</div>

<table>
	<thead>
		<tr>
			<th>No.</th>
			<th>Name</th>
			<th>Sell Date</th>
			<th>Tickets</th>
			<th>Quantity</th>
			<th>Price</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>No.</td>
			<td>Name</td>
			<td>Sell Date</td>
			<td>Tickets</td>
			<td>Quantity</td>
			<td>Price</td>
			<td>Edit | Delete</td>
		</tr>
		<tr>
			<td>No.</td>
			<td>Name</td>
			<td>Sell Date</td>
			<td>Tickets</td>
			<td>Quantity</td>
			<td>Price</td>
			<td>Edit | Delete</td>
		</tr>
		<tr>
			<td>No.</td>
			<td>Name</td>
			<td>Sell Date</td>
			<td>Tickets</td>
			<td>Quantity</td>
			<td>Price</td>
			<td>Edit | Delete</td>
		</tr>
	</tbody>
</table>

<script type="text/javascript">
    jQuery(document).ready(function(){
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
    });
</script>