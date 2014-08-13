<style>
.subscribe-to-mailchimp > * {
	display: block;
	width: 100%;
}
</style>
<p class="subscribe-to-mailchimp">
	<label for="<?php echo $this->get_field_id( 'mailchimp-apikey' ); ?>">API Key: </label>
	<input type="text" id="<?php echo $this->get_field_id( 'mailchimp-apikey' ); ?>" name="<?php echo $this->get_field_name( 'mailchimp-apikey' ); ?>" value="<?php echo $mailchimp_apikey; ?>" >
</p>
<p class="subscribe-to-mailchimp">
	<label for="<?php echo $this->get_field_id( 'mailchimp-list-id' ); ?>">List ID: </label>
	<input type="text" id="<?php echo $this->get_field_id( 'mailchimp-list-id' ); ?>" name="<?php echo $this->get_field_name( 'mailchimp-list-id' ); ?>" value="<?php echo $mailchimp_list_id; ?>" >
</p>