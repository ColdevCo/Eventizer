/**
 * Created by kki on 6/16/14.
 */

jQuery(document).ready(function () {
	EvTicket.setWidth();

    jQuery('.ticket-details').hide().eq(0).show();
    jQuery('#cem_widget_ticket-ticket_id').bind('change',function(){
        var ticket_id = jQuery(this).val();

        jQuery('.ticket-details').hide();
        jQuery('.ticket-details.ticket-' + ticket_id).show();
    });
});

var EvTicket = {
	selector: jQuery('.ev-ticket-widget'),
	wrapper : jQuery('.ev-ticket-widget .event-wrap'),
	setWidth: function () {
		var items = this.selector.find('.event-list').find('.event-item');
		var totalWidth = 0;
		items.each(function () {
			totalWidth += jQuery(this).outerWidth(true);
			jQuery(this).click(function () {
				jQuery(items).each(function () {
					jQuery(items).removeClass('active');
				});
				jQuery(this).addClass('active');
			})
		});

		this.wrapper.width(totalWidth);
	}
}