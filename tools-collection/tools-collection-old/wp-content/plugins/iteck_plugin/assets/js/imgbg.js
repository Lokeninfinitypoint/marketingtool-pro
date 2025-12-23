(function($) {
    "use strict";
    //replace the data-background into background image
    $(".img-bg,.slider-img-bg").not('.home-slider .slider-img-bg,.home-video-box .slider-img-bg').each(function() {
        var imG = $(this).data('background');
		if (imG) {
        	$(this).css('background-image', "url('" + imG + "') " );
		}
       
    });
})(jQuery);