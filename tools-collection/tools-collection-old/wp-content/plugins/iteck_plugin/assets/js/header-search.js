jQuery(document).ready(function ($) {
   //Header Search 
    $(document).on('click', '.close-black-block', function(event) {
        event.preventDefault();
        $('.header-search-icon').removeClass('open');
        $(".focus-input").focus();
    });

    $(document).on('click', '.header-search-icon > a.search', function(event) {
        event.preventDefault();
        $('.header-search-icon').addClass('open');
        $(".focus-input").focus();
    });
});