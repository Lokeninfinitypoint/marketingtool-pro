(function ($) {
    "use strict";
  
    // --------- navbar active ---------
    $(".iteck-toggle-tabs .faq-category li a").on("click", function () {
      $(this).addClass("active");
      $(this).parent().siblings().children("a").removeClass("active");
    })
    
    $( ".iteck-toggle-tabs .faq-category li span" ).each(function( index ) {
      var count = index + 1;
      var itemsCount = $('.iteck-toggle-tabs .accordion-item').prevAll('h5.group-'+count).first().nextUntil('h5.group-'+(count+1)).length;
      var text = itemsCount;
      if(itemsCount < 10) {
        itemsCount = '0'+itemsCount
      }
      $(this).text(itemsCount);
      count++;
    });
  
  })(jQuery);