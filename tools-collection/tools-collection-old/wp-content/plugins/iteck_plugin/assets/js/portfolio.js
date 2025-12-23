(function ($) {
	"use strict";

	// ---------- portfolio mixitup -----------
    var containerEl = document.querySelector('.mix-container');
    var mixer = mixitup(containerEl);

    $(window).on('load', function () {
        $('.iteck-portfolio .mix-container.hover-overlay').isotope({
            itemSelector: '.mix-container.hover-overlay > div'
        });
    });

})(jQuery);