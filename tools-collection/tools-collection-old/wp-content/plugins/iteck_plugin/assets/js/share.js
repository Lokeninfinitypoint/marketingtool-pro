(function($) {
    "use strict";


		$(window).on("load", function() {
			$('.iteck-share-box a, .iteck-social-share a').on('click', function() {
				window.open(this.href,"","menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600");return false;
				});
		});


})(jQuery);