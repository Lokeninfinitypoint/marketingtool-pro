(function($) {
    "use strict";
  

	$(window).on("load", function() { // makes sure the whole site is loaded

		$('.popup-portfolio').magnificPopup({
			type: 'image',
			gallery: {
				enabiteck: true
			}
		});


    });
	
   
	



})(jQuery);