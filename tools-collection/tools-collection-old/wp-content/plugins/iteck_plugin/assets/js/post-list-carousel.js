(function($) {
    "use strict";

     // -------- blog effect --------
     $(".iteck-post-list-carousel .blog_box").mouseenter(function(){
        $(this).find(".text").slideDown();
    })
    $(".iteck-post-list-carousel .blog_box").mouseleave(function(){
        $(this).find(".text").slideUp();
    })

})(jQuery);