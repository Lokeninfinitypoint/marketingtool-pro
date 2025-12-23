function software_agency_menu_open_nav() {
	window.software_agency_responsiveMenu=true;
	jQuery(".sidenav").addClass('show');
}
function software_agency_menu_close_nav() {
	window.software_agency_responsiveMenu=false;
 	jQuery(".sidenav").removeClass('show');
}

jQuery(function($){
 	"use strict";
   	jQuery('.main-menu > ul').superfish({
		delay:       500,
		animation:   {opacity:'show',height:'show'},  
		speed:       'fast'
   	});
});

jQuery(document).ready(function () {
	window.software_agency_currentfocus=null;
  	software_agency_checkfocusdElement();
	var software_agency_body = document.querySelector('body');
	software_agency_body.addEventListener('keyup', software_agency_check_tab_press);
	var software_agency_gotoHome = false;
	var software_agency_gotoClose = false;
	window.software_agency_responsiveMenu=false;
 	function software_agency_checkfocusdElement(){
	 	if(window.software_agency_currentfocus=document.activeElement.className){
		 	window.software_agency_currentfocus=document.activeElement.className;
	 	}
 	}
 	function software_agency_check_tab_press(e) {
		"use strict";
		// pick passed event or global event object if passed one is empty
		e = e || event;
		var activeElement;

		if(window.innerWidth < 999){
		if (e.keyCode == 9) {
			if(window.software_agency_responsiveMenu){
			if (!e.shiftKey) {
				if(software_agency_gotoHome) {
					jQuery( ".main-menu ul:first li:first a:first-child" ).focus();
				}
			}
			if (jQuery("a.closebtn.mobile-menu").is(":focus")) {
				software_agency_gotoHome = true;
			} else {
				software_agency_gotoHome = false;
			}

		}else{

			if(window.software_agency_currentfocus=="responsivetoggle"){
				jQuery( "" ).focus();
			}}}
		}
		if (e.shiftKey && e.keyCode == 9) {
		if(window.innerWidth < 999){
			if(window.software_agency_currentfocus=="header-search"){
				jQuery(".responsivetoggle").focus();
			}else{
				if(window.software_agency_responsiveMenu){
				if(software_agency_gotoClose){
					jQuery("a.closebtn.mobile-menu").focus();
				}
				if (jQuery( ".main-menu ul:first li:first a:first-child" ).is(":focus")) {
					software_agency_gotoClose = true;
				} else {
					software_agency_gotoClose = false;
				}
			
			}else{

			if(window.software_agency_responsiveMenu){
			}}}}
		}
	 	software_agency_checkfocusdElement();
	}
});

jQuery('document').ready(function($){
    setTimeout(function () {
		jQuery("#preloader").fadeOut("slow");
    },1000);
    // Sticky Header
  	$(window).scroll(function(){
		var sticky = $('.header-sticky'),
			scroll = $(window).scrollTop();

		if (scroll >= 100) sticky.addClass('header-fixed');
		else sticky.removeClass('header-fixed');
	});
});

window.addEventListener('scroll', function() {
  var sticky = document.querySelector('.copyright-sticky');
  if (!sticky) return;

  var scrollTop = window.scrollY || document.documentElement.scrollTop;
  var windowHeight = window.innerHeight;
  var documentHeight = document.documentElement.scrollHeight;

  var isBottom = scrollTop + windowHeight >= documentHeight-100;

  if (scrollTop >= 100 && !isBottom) {
    sticky.classList.add('copyright-fixed');
  } else {
    sticky.classList.remove('copyright-fixed');
  }
});

jQuery(document).ready(function () {
	jQuery(window).scroll(function () {
	    if (jQuery(this).scrollTop() > 100) {
	        jQuery('.scrollup i').fadeIn();
	    } else {
	        jQuery('.scrollup i').fadeOut();
	    }
	});
	jQuery('.scrollup').click(function () {
	    jQuery("html, body").animate({
	        scrollTop: 0
	    }, 600);
	    return false;
	});
});