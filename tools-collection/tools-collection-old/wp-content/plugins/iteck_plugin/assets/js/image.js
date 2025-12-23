(function($) {
    "use strict";

    $(window).on("load", function () {
        var imageUp = document.getElementsByClassName('thumparallax');
        new simpleParallax(imageUp, {
            delay: 1,
            scale: 1.1
        });

        var imageDown = document.getElementsByClassName('thumparallax-down');
        new simpleParallax(imageDown, {
            orientation: 'down',
            delay: 1,
            scale: 1.1
        });
    });

})(jQuery);