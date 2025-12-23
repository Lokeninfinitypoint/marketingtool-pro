(function($) {
    "use strict";
	
	 $(window).on("load", function() {
		 
				
			$('#page_template').on('change', function() {
				  if( $('#page_template').val() == 'blank-builder.php')
				  {
					$("#portfolio_meta_box").hide();
				  }
				  else
				  {
					$("#portfolio_meta_box").show();
				  }
			});
			
			if( $('#page_template').val() == 'blank-builder.php')
			{
			  $("#portfolio_meta_box").hide();
			}
			else
			{
			  $("#portfolio_meta_box").show();
			}
			
	
	});
})(jQuery);