(function ($) {
	"use strict";
    
    var containerEl = document.querySelector('.iteck-products-tabs .items');
    var mixer = mixitup(containerEl);
    // ----- active & unactive -----
    $(".iteck-products-tabs .mix_tabs").on("click" , ".tab-link" , function(){
        $(this).addClass("active").siblings().removeClass("active");
    })

})(jQuery);