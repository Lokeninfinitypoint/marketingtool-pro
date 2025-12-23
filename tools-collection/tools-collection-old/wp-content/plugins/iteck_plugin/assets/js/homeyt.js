		(function($) {
			"use strict";
			if (!Modernizr.touch) {
				$(".bg-youtube").mb_YTPlayer();
				} else {
				//replace the data-background into background image
				$(".slider-img-bg").each(function() {
					var imG = $(this).data('background');
					$(this).css('background-image', "url('" + imG + "') "
			
					);
				});
			}
		})(jQuery);