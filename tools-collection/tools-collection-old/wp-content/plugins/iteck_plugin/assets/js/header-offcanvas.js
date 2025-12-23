jQuery(document).ready(function ($) {
   //Header Search 
    $(document).on('click', '.close-black-block-offcanvas', function(event) {
        event.preventDefault();
        $('.side-panel').removeClass('open');
    });

    $(document).on('click', '.header-offcanvas-icon > a.offcanvas', function(event) {
        event.preventDefault();
        $('.side-panel').addClass('open');
    });
});
