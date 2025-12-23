(function($) {
    "use strict";


    $(window).on("load", function() { // makes sure the whole site is loaded
        // img-box-slider slider  
        $('.work-process').slick({ 
            autoplay: true,
            dots: false,
            slidesToShow: 4,
            nextArrow: '<i class="fa fa-angle-right"></i>',
            prevArrow: '<i class="fa fa-angle-left"></i>',
            slidesToScroll: 4,
            arrows: false,
            autoplaySpeed: 2000,
            speed:1000,
            fade: false,
            pauseOnHover: true,
            pauseOnFocus: false,
            responsive: [{
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        infinite: true,
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        infinite: true,
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
            ]
        });
    });

})(jQuery);