(function($) {
    "use strict";


    $(window).on("load", function() { // makes sure the whole site is loaded

		//slider for home with work slider
		$('.home-work-slider').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			autoplay: true,
			nextArrow: '<i class="fa fa-arrow-right"></i>',
			prevArrow: '<i class="fa fa-arrow-left"></i>',
			responsive: [{
					breakpoint: 1024,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 3,
						infinite: true,
						dots: false
					}
				},
				{
					breakpoint: 600,
					settings: {
						slidesToShow: 2,
						slidesToScroll: 2
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 1,
						slidesToScroll: 1
					}
				}
			]
		});

		
    });


})(jQuery);