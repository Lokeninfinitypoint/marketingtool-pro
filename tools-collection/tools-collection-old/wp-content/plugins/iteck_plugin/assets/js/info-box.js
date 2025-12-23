(function($) {
    "use strict";

    $(window).on("load", function() {

        $('.iteck-info-box.style-10 .items').each(function () {
            $(this).on('mouseenter', function () {
                $(this).addClass("active");
                $('.iteck-info-box.style-10 .items').not(this).removeClass("active");
            });
        });
        
    });

})(jQuery);