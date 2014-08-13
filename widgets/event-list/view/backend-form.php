<style>
	#event-list-counter > span {
		margin-left: 4px;
	}
</style>

<p id="event-list-counter">
	<label for="<?php echo $this->get_field_id( 'event-list-counter' ); ?>">Show: </label>
	<span>
		<input type="text" id="<?php echo $this->get_field_id( 'event-list-counter' ); ?>" name="<?php echo $this->get_field_name( 'event-list-counter' ); ?>" size="3" value="<?php echo esc_attr( $event_list_counter ); ?>">
		upcoming events
	</span>
</p>