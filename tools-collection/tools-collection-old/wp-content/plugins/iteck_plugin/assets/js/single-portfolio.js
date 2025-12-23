(function($) {
    "use strict";
  

	$(window).on("load", function() { // makes sure the whole site is loaded


		//isotope setting(portfolio gallery)
        var $container = $('.port-gallery-body');
        $container.imagesLoaded(function() {
            $container.isotope();
        });
		 // script popup image

		$('.popup-portfolio').magnificPopup({
			type: 'image',
			gallery: {
				enabiteck: true
			}
		});


    });
	
   
	
	$(".port-gallery-body .port-img ").each(function() {
        var imG = $(this).data('background');
        $(this).css('background-image', "url('" + imG + "') "

        );
    });
	
	$(".work-img-bg").each(function() {
        var imG = $(this).data('background');
        $(this).css('background-image', "url('" + imG + "') "

        );
    });




})(jQuery);