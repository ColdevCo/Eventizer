/**
 * Created by kki on 6/16/14.
 */

jQuery(document).ready(function () {
	EvTicket.setWidth();
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
		console.log(totalWidth);

		this.wrapper.width(totalWidth);
	}
}