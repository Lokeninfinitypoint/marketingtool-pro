(function($) {
    "use strict";


    $(window).on("load", function() { // makes sure the whole site is loaded 


		//slider for home slider 
		$('.home-slider').slick({
			autoplay: true,
			dots: true,
			nextArrow: '<i class="fa fa-angle-right"></i>',
			prevArrow: '<i class="fa fa-angle-left"></i>',
			speed: 1000,
			fade: true,
			pauseOnHover: false,
			pauseOnFocus: false,
		    dotsClass: 'custom-paging',
		    customPaging: function (slider, i) {
		        console.log(slider);
		        return  (i + 1) + '/' + slider.slideCount;
		    }
		});


		
    });


})(jQuery);