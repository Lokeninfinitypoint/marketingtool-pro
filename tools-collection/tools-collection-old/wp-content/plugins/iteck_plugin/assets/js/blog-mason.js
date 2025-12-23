(function($) {
    "use strict";


    $(window).on("load", function() { // makes sure the whole site is loaded

		$('.blog-body').each(function() {
			$(this).isotope();
		});
    });


})(jQuery);