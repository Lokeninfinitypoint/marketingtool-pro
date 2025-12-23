( function( api ) {

	// Extends our custom "marketing-agency" section.
	api.sectionConstructor['marketing-agency'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );

jQuery( document ).ready(function($) {
	var this_obj = marketing_agency_customizer_params;
  	var api = wp.customize;

	// Reset options
	$('.refresh-btn').on('click', function() {
		var dataValue = $(this).attr('data-value');
		$.get( marketing_agency_customizer_params.ajaxurl + '?action=' + dataValue, function( data ) {
	  		window.location.reload();
		});
	});

	$('.reset-button').on('click', function() {
		var $this = $(this);
		$this.closest('.customize-control').find('.kt-modal').show();
	});

	$('.close').on('click', function() {
		$(this).closest('.kt-modal').hide();
	});

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if ($(event.target).hasClass('kt-modal')) {
		  $('.kt-modal').hide();
		}
	}
});