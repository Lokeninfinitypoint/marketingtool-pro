!(function ($) {

    function geekfolioProgress() {

        $(".skills-circle .skill").each(function () {

            c4.circleProgress({
                startAngle: -Math.PI / 2 * 1,
                value: myVal,
                thickness: 4,
                size: 140,
                fill: { color: "#ff5e57" }
            });

        });

        var wind = $(window);
        wind.on('scroll', function () {
            $(".skill-progress .progres").each(function () {
                var myVal = $(this).attr('data-value');
                var bottom_of_object =
                    $(this).offset().top + $(this).outerHeight();
                var bottom_of_window =
                    $(window).scrollTop() + $(window).height();
                var myVal = $(this).attr('data-value');
                if (bottom_of_window > bottom_of_object) {
                    $(this).css({
                        width: myVal
                    });
                }

            });
        });

    }

    jQuery(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-progress.default', geekfolioProgress);
    })
})(jQuery); 