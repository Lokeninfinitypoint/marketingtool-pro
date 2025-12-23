(function ($) {
	"use strict";
    let minValue = $( ".iteck-products-filters #amount1" ).data('min-value');
    let maxValue = $( ".iteck-products-filters #amount2" ).data('max-value');
    let maxStoreValue = $( ".iteck-products-filters .amount-input" ).data('max-store-value');
    $( ".iteck-products-filters #slider-range" ).slider({
            range: true,
            min: 0,
            max: maxStoreValue,
            values: [ minValue, maxValue ],
            slide: function( event, ui ) {
            $( "#amount1" ).val( ui.values[ 0 ] );
            $( "#amount2" ).val( ui.values[ 1 ] );
        }
    });
    $( ".iteck-products-filters #amount1" ).val( $( "#slider-range" ).slider( "values", 0 ) );
    $( ".iteck-products-filters #amount2" ).val( $( "#slider-range" ).slider( "values", 1 ) );

	$(document).ready(function(){
		$('.iteck-products-filters .form-check .form-check-input').change(function(){
			$('.iteck-products-filters .filters-form').submit();
		});
	});

    // --------- grid list view ---------
    $(".iteck-product .grid-list-btns").on( "click", ".bttn" , function(){
        $(this).addClass("active").siblings().removeClass("active");
    })

    $(".iteck-product .grid-list-btns").on( "click", ".list-btn" , function(){
        $(".iteck-product .products").addClass("list-view");
    })

    $(".iteck-product .grid-list-btns").on( "click", ".grid-btn" , function(){
        $(".iteck-product .products").removeClass("list-view");
    })

})(jQuery);