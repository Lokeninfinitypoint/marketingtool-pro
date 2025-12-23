		(function($) {
		    "use strict";
		    //video background setting
		    if (Modernizr.touch) {
		        //replace the data-background into background image
		        $(".slider-img-bg").each(function() {
		            var imG = $(this).data('background');
		            $(this).css('background-image', "url('" + imG + "') "

		            );
		        });
		    } else {
		            var BV = new $.BigVideo({
		                container: $('.home-video-box'),
		                useFlashForFirefox: false
		            });
		            BV.init();

		            BV.show(
		                $(".bg-vid").data("link"), //video source directlink 
		                {
		                    ambient: $(".bg-vid").data("ambient")
		                } //mute true
		            ) 
		    }

		})(jQuery);