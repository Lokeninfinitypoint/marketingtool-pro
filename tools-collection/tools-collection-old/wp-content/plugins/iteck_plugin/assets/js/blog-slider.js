(function($) {
    "use strict";


    $(window).on("load", function() { // makes sure the whole site is loaded


		//slider client slider
		$('.post-slider').each(function() {
			var $slide = $(this).data('slide');
			var $tabs = $(this).data('slide-tablet');
			var $mobile = $(this).data('slide-mobile');
			$(this).slick({
				slidesToShow: $slide,
				slidesToScroll: 1,
				nextArrow: '<i class="fa fa-arrow-right"></i>',
                prevArrow: '<i class="fa fa-arrow-left"></i>',
				autoplay: true,
				responsive: [{
						breakpoint: 1024,
						settings: {
							slidesToShow: $tabs,
							slidesToScroll: 1,
						}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: $mobile,
							slidesToScroll: 1,
						}
					}
				]
			});
		});
    });


})(jQuery);