/**
 * Created by kki on 5/7/14.
 */

jQuery(document).ready(function () {
	jQuery('.ev-datepicker').datepicker();
	jQuery('.ev-colorpicker').spectrum({
		flat      : true,
		showInput : true,
		allowEmpty: true
	});
});