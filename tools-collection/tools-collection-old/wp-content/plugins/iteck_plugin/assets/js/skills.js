(function($) {
    "use strict";

    //Skills 



function iteck_load_content_area_scripts($) {

    /* progress circle */ 
    $('.skills.style-1').each(function () {
        var startcolor = $(this).data('startcolor'),
            endcolor = $(this).data('endcolor'),
            num = $(this).data('num'),
            speed = $(this).data('speed'),
            suffix = $(this).data('suffix');
        $(this).circleProgress({
            value: 1,
            fill: endcolor,
            emptyFill: startcolor,
            thickness: 5,
            size: 140,
            animation: {duration: speed, easing: "circleProgressEasing"},
        }).on('circle-animation-progress', function (event, progress) {
            $(this).find('.num').html(Math.round(num * progress) + suffix);
        });
    });

}


    /* Circle Bars - Knob */
    if (typeof ($.fn.knob) !== undefined) {
        $('.knob.knob-percent.dial').each(function () {
            var $this = $(this),
                knobVal = $this.attr('data-rel');
            $this.knob({
                'draw': function () {
                },
                'format': function (value) {
                    return value + '%';
                }
            });
            $this.appear(function () {
                $({
                    value: 0
                }).animate({
                    value: knobVal
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function () {
                        $this.val(Math.ceil(this.value)).trigger('change');
                    }
                });
            }, {
                accX: 0,
                accY: -150
            });
        });
    }



})(jQuery);