(function($) {
    "use strict";

        // img-box-slider slider  
        $('.img-box-slider').slick({ 
            autoplay: false,
            dots: true,
            slidesToShow: 4,
            nextArrow: '<i class="fa fa-angle-right"></i>',
            prevArrow: '<i class="fa fa-angle-left"></i>',
            slidesToScroll: 3, 
            arrows: false,
            autoplaySpeed: 3000,
            speed:800,
            fade: false,
            pauseOnHover: false,
            pauseOnFocus: false,
            responsive: [{
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: false
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: false
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false
                }
            },
            ]
        });

        $(window).on('resize', function() {
        $('.img-box-slider').slick('resize');
        });

})(jQuery);