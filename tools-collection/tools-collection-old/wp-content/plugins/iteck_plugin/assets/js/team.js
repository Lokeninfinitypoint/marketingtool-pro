(function($) {
    "use strict";

    //slider in team
    if ($('.team-slider').find('.team-box').length > 2) {

        $(".team-slider").owlCarousel({
            navigation: true,
            navigationText: ['<span class="slide-nav inleft"><i class="fa fa-long-arrow-left"></i></span >', '<span class="slide-nav inright"><i class="fa fa-long-arrow-right"></i></span >'],
            slideSpeed: 300,
            stopOnHover: true,
            autoplay: true,
            autoHeight: true,
            pagination: true,
            paginationSpeed: 300,
            singleItem: false,
            transitionStyle: "fade",
            items: 2,
            itemsDesktop: [1199, 2],
            itemsDesktopSmall: [979, 2],
            itemsTablet: [768, 1],
        });
    }

})(jQuery);