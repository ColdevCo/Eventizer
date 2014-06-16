<?php

$args = array(
	'post_type'      => 'event',
	'post_status'    => 'publish',
	'posts_per_page' => - 1,
	'paged'          => 1,
	'order'          => 'ASC'
);
$events = get_posts( $args );

function widget_javascript() {
	ob_start();
	?>

	<script>alert('wew');</script>

	<?php
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}

wp_enqueue_script( 'ev-ticket', plugins_url( '', dirname( __FILE__ ) ) . '/js/ev-ticket.js', array( 'jquery' ) );
wp_enqueue_style( 'ev-ticket-style', plugins_url( '', dirname( __FILE__ ) ) . '/css/ev-ticket.css' );

?>
<div class="ev-ticket-widget">
	<div class="panel panel-default">
		<div class="panel-body">
			<form role="form" action="?process=ticket_widget" method="post">
				<?php if ( is_page() ) : ?>
					<div class="event-list">
						<div class="event-wrap">
							<?php foreach ( $events as $event ) : ?>
								<div class="event-item" data-event-id="<?= $event->ID; ?>">
									<?= get_the_post_thumbnail( $event->ID, array( 150, 150 ) ); ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php else : ?>
					<input type="hidden" name="ev_ticket-id" value="<?php the_ID(); ?>">
				<?php
				endif;
				?>
				<div class="form-group">
					<label for="ev_ticket-first-name">First Name</label>
					<input type="text" class="form-control" name="ev_ticket-first-name" id="ev_ticket-first-name" placeholder="First Name">
				</div>
				<div class="form-group">
					<label for="ev_ticket-last-name">Last Name</label>
					<input type="text" class="form-control" name="ev_ticket-last-name" id="ev_ticket-last-name" placeholder="Last Name">
				</div>
				<div class="form-group">
					<label for="ev_ticket-email">Email</label>
					<input type="email" class="form-control" name="ev_ticket-email" id="ev_ticket-email" placeholder="Email">
				</div>
				<div class="form-group">
					<label for="ev_ticket-phone">Phone</label>
					<input type="text" class="form-control" name="ev_ticket-phone" id="ev_ticket-phone" placeholder="Phone">
				</div>
				<div class="form-group row">
					<label for="ev_ticket-qty" class="col-sm-8 control-label">Qty</label>

					<div class="col-sm-4">
						<input type="text" class="form-control" name="ev_ticket-qty" id="ev_ticket-qty" placeholder="Qty">
					</div>
				</div>
				<button type="submit" class="btn btn-default" style="margin-top: 10px;">Submit</button>
			</form>
		</div>
	</div>
</div>