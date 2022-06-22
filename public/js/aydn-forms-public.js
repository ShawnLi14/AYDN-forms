	jQuery.noConflict();
	(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(document).ready(function($) {
	    $('#my_aydn .hour').change(function() {
	    	var total = Number($('#hours').val()) + Number($('#extra_hours').val());
  			$('#total_hours').val(total);
		});
		$(".date").flatpickr();
		$('.time').flatpickr({
		    enableTime: true,
		    noCalendar: true,
		    dateFormat: "h:i K",
	      });
		$('#signup-form .preferredName').change(function(){
			var name = $('#v_firstname').val() + " " + $('#v_lastname').val();
			$('#v_name').val(name);
		});
		$('#hours_search').on('click', function(index) {
			var totalHours = 0;
			var start_date = Date.parse($('#hours_search_start_date').val());
			var end_date = Date.parse($('#hours_search_end_date').val());
			var alertMessage = "Please enter the following: ";
			var datesSet = !isNaN(end_date) && !isNaN(start_date);
			if(isNaN(start_date)) alertMessage += "start date; ";
			if(isNaN(end_date)) alertMessage += "end date; ";
			if(datesSet){
				$('#hours_table .hours_row').each(function() {
					var date = Date.parse($(this).find('.event_date').text());
					if(date <= end_date && date >= start_date){
						totalHours += parseInt($(this).find('.total_hours').text());
						$(this).show();
					}
					else{
						$(this).hide();
					}
				});
			}
			else{alert(alertMessage);}
			$('#hours_submitted').text(totalHours);
		});
	});
})( jQuery );
