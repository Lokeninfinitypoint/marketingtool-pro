(function($) {
    "use strict";

	
    $('.upload_input, input[type=file]').on('change', function(event) {
        var files = event.target.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            $(".iteck_upload_button").html(file.name).css("color" , "#fff")
            // $("<div class='file__value'><div class='file__value--text'>" + file.name + "</div><div class='file__value--remove' data-id='" + file.name + "' ></div></div>").insertAfter('#upload_text');
        }
    });

})(jQuery);