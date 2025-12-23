//Main scripts 

(function($) {
	"use strict";


    window.Iteck = {
	    init: function () {
	    	this.body = $('body');
	    }
	};


	$.fn.changeElementType = function(newType) {
		var attrs = {};
		if (!(this[0] && this[0].attributes))
			return;

		$.each(this[0].attributes, function(idx, attr) {
			attrs[attr.nodeName] = attr.nodeValue;
		});
		this.replaceWith(function() {
			return $("<" + newType + "/>", attrs).append($(this).contents());
		});
	}

	$(window).on("load", function() { // makes sure the whole site is loaded  

		Splitting();

		//script for mobile menu 
		$('.mobile-wrapper').each(function() {
			var $this = $(this);
			$(this).find('.hamburger').on('click', function(event) {
				$this.find('.fat-nav').fadeToggle();
				$this.find('.fat-nav').toggleClass('active');
				$(this).toggleClass('active');
				$('body').toggleClass('nav-active');
				event.preventDefault();
			});
		}); 
		
		$('.fat-list').changeElementType('ul');
		$('.fat-nav a').on('click', function(event) {
			$('.fat-nav').removeClass('active');
			$('.fat-nav').fadeOut();
			$('.hamburger').removeClass('active');
			$('body').removeClass('nav-active');
		});
		$( '<a href="#" class="menu-item-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></a>' 
			).insertAfter( '.fat-nav .menu-item-has-children > a, .fat-nav .page_item_has_children > a' );
		$('.fat-nav .menu-item-has-children .menu-item-icon, .fat-nav .page_item_has_children .menu-item-icon').on('click', function(t) {
          t.stopPropagation(),
            t.preventDefault(),
            $(this).toggleClass('active');
          var n = $(this).next('ul'),
            o = $(this)
              .closest('ul')
              .children('li');
          o
            .find('.sub-menu')
            .not(n)
            .slideUp('fast'),
          o
            .find('.children')
            .not(n)
            .slideUp('fast'),
            o
              .find('.menu-item-icon')
              .not(this)
              .removeClass('active'),
            n.slideToggle('fast');
        })

		//sticky navigation 
		$(".stuck-nav").sticky({
			topSpacing: 0,
		});

		//reduce next/prev link title
		$('.pagi-title').each(function(){
			$(this).text($(this).text().substring(0,38));
		 });


		if (Modernizr.touch) {
			//add class on touch device
			$('body').addClass('no-para');
		} 
	});





	// script popup image
	$('.popup-img').magnificPopup({
		type: 'image'
	});

	// script fixed-sidebar 
	$('.fixed-sidebar').theiaStickySidebar({
	  // Settings
	  additionalMarginTop: 105
	});

	// script popup image
	$('.blog-popup-img').magnificPopup({
		type: 'image',
		gallery: {
			enabiteck: true
		}
	});

	// Video responsive
	$("body").fitVids();

	//script for navigation(superfish) 
	$('.main-menu.menu-wrapper ul').superfish({
		delay: 400, //delay on mouseout
		animation: {
			opacity: 'show',
			height: 'show'
		}, // fade-in and slide-down animation 
		animationOut: {
			opacity: 'hide',
			height: 'hide'
		},
		speed: 200, //  animation speed
		speedOut: 200,
		autoArrows: false // disable generation of arrow mark-up
	})


	/*--------------------------Menu style-3---------------------------*/


	    $('.iteck-menu.style-3 .navigation > li').on('mouseenter', function () {
        $(this).css("opacity", "1").siblings().css("opacity", ".5");
	    });

	    $('.iteck-menu.style-3 .navigation > li').on('mouseleave', function () {
	        $(".iteck-menu.style-3 .navigation > li").css("opacity", "1");
	    });
/*-------------------------------------------------------------------------------------*/ 
    var wind = $(window);
    function iteckNoScroll() {
        window.scrollTo(0, 0);
    }

    function iteckHeader() {
        var open = false,
            navDark = $(".topnav.dark"),
            myNav = $(".topnav"),
            logoChan = $(".topnav.dark .logo img"),
            lastScroll = 0;

        $(".sub-menu .link").removeClass("link").addClass("sub-link");
        myNav.addClass("no-scroll");
        wind.on("scroll", function () {
            var bodyScroll = wind.scrollTop(),
                navbar = $(".topnav");

            navbar.removeClass("no-scroll");

            if (navbar.hasClass('scroll-bt')) {

                if (bodyScroll > lastScroll) {
                    navbar.removeClass("nav-scroll");
                } else {
                    navbar.addClass("nav-scroll");
                }

            } else {

                if (bodyScroll > 100) {
                    navbar.addClass("nav-scroll");
                } else {
                    navbar.removeClass("nav-scroll");
                }
            }
            lastScroll = bodyScroll;
            if (lastScroll == 0 && navbar.hasClass('scroll-bt')) {
                navbar.removeClass("nav-scroll").addClass("no-scroll");
            }
        });

        $('.topnav .menu-icon, .topnav .hamburger').on('click', function () {
            open = !open;
            $('.topnav').toggleClass("open");
            $('.hamenu').toggleClass("open");
            $('.hamburger').toggleClass("is-active");
            if (open) {
                $('.hamenu').animate({ left: 0 });
                $('.topnav .menu-icon .text').addClass('open');
                navDark.addClass("navlit");
                logoChan.attr('src', 'img/logo-light.png');
                window.addEventListener('scroll', witeckNoScroll);
            } else {
                $('.hamenu').delay(300).animate({ left: "-100%" });
                $('.topnav .menu-icon .text').removeClass('open');
                navDark.removeClass("navlit");
                logoChan.attr('src', 'img/logo-dark.png');
                window.removeEventListener('scroll', witeckNoScroll);
            }
        });

        $('.internal .link').on('click', function () {
            open = !open;
            $('.hamenu').addClass('has-internal-link').toggleClass("open").css({ left: "-100%",opacity: "0" });
            $('.topnav').toggleClass("open");
            $('.hamburger').toggleClass("is-active");

            $('.topnav .menu-icon .text').removeClass('open');
            navDark.removeClass("navlit");
            window.removeEventListener('scroll', witeckNoScroll);
            setTimeout(function() {
                $('.hamenu').css({ opacity: "1" }).removeClass('has-internal-link');
            }, 2000);
        });
        
        $('.hamenu .menu-links .main-menu li').on('mouseenter', function () {
            $(this).css("opacity", "1").siblings().css("opacity", ".5");
        });
        $('.hamenu .menu-links .main-menu li').on('mouseleave', function () {
            $(".hamenu .menu-links .main-menu li").css("opacity", "1");
        });
        $('li .dmenu').on('click', function () {
            $(".main-menu").addClass("gosub");
            $(this).parents('.sub-menu').removeClass("sub-open");
            $(this).parent().next().addClass("sub-open");
        });
        $('.sub-menu.depth_0 > ul > li > div > .sub-link.back').on('click', function () {
            $(".main-menu").removeClass("gosub");
            var parent0 = $(this).parents('.sub-menu');
            parent0.removeClass("sub-open");
        });
        $('.sub-menu.depth_1 > ul > li > div > .sub-link.back').on('click', function () {
            var parent = $(this).parents('.sub-menu.depth_1');
            var parparent = parent.parents('.sub-menu.depth_0');
            parent.removeClass("sub-open");
            parparent.addClass("sub-open");
        });
        $('.sub-menu.depth_2 > ul > li > div > .sub-link.back').on('click', function () {
            var parent = $(this).parents('.sub-menu.depth_2');
            var parparent = parent.parents('.sub-menu.depth_1');
            parent.removeClass("sub-open");
            parparent.addClass("sub-open");
        });
    };
    iteckHeader();


    /* ===============================  Data attributes  =============================== */ 

	function iteck_data_attributes() {

		/*----------------------------------------------tooltip--------------------------*/
	    $('[data-tooltip-tit]').hover(function () {
	        $('<div class="div-tooltip-tit"></div>').text($(this).attr('data-tooltip-tit')).appendTo('.iteck-tooltip').fadeIn('slow');
	    }, function () {
	        $('.div-tooltip-tit').remove();
	    }).mousemove(function (e) {
	        $('.div-tooltip-tit').css({ top: e.clientY + 10, left: e.clientX + 20 })
	    });
	    $('[data-tooltip-sub]').hover(function () {
	        $('<div class="div-tooltip-sub"></div>').text($(this).attr('data-tooltip-sub')).appendTo('.iteck-tooltip').fadeIn('slow');
	    }, function () {
	        $('.div-tooltip-sub').remove();
	    }).mousemove(function (e) {
	        $('.div-tooltip-sub').css({ top: e.clientY + 60, left: e.clientX + 20 })
	    });

	    /*-------------------------------------------Background-------------------------*/ 
	    
        var pageSection = $(".bg-img, section");
	    pageSection.each(function (indx) {

	        if ($(this).attr("data-background")) {
	            $(this).css("background-image", "url(" + $(this).data("background") + ")");
	        }
	    });
	};
	iteck_data_attributes();

/*===================================================isotope portfolio 1,2,3 ==============================================*/ 

	$(window).on("load", function() { // makes sure the whole site is loaded
		// filter items when filter link is clicked
		var $container = $('.portfolio-body');
		$('.port-filter a').on('click', function() {
			var selector = $(this).attr('data-filter');
			$container.isotope({
				itemSelector: '.port-item',
				filter: selector
			});
			return false;
		});

	});

    /* ===============================  isotope Masonery  =============================== */
    $(window).on("load", function() { // makes sure the whole site is loaded

    $('.iteck-gallery').isotope({
        itemSelector: '.items'
    });

    var $gallery = $('.iteck-gallery').isotope();

    $('.filtering').on('click', 'span', function () {
        var filterValue = $(this).attr('data-filter');
        $gallery.isotope({ filter: filterValue });
    });

    $('.filtering').on('click', 'span', function () {
        $(this).addClass('active').siblings().removeClass('active');
    });
    });


    /*------------------------------------------------------------------------------*/

	$(window).on("load", function() { // makes sure the whole site is loaded
		// filter items when filter link is clicked
		var $container = $('.iteck-gallery');
		$('.filtering span').on('click', function() {
			var selector = $(this).attr('data-filter');
			$container.isotope({
				itemSelector: '.items',
				filter: selector
			});
			return false;
		});

	});


    /* ===============================  Header Search  =============================== */


	 
    $(document).on('click', '.close-black-block', function(event) {
        event.preventDefault();
        $('.search-icon-header').removeClass('open');
        $(".focus-input").focus();
    });

    $(document).on('click', '.search-icon-header > a.search', function(event) {
        event.preventDefault();
        $('.search-icon-header').addClass('open');
        $(".focus-input").focus();
    });

	$(document).on('click', '.iteck-search-icon a', function(event) {
        event.preventDefault();
        $('.search-icon-header').addClass('open');
        $(".focus-input").focus();
    });


	//add image mask
	$('.bg-with-mask').each(function() {
		$(this).append('<div class="slider-mask"></div>');
	});

	//slider for blog slider
	$('.blog-slider').slick({
		autoplay: true,
		dots: false,
		nextArrow: '<i class="fa fa-arrow-right"></i>',
		prevArrow: '<i class="fa fa-arrow-left"></i>',
		speed: 800,
		fade: true,
		pauseOnHover: false,
		pauseOnFocus: false
	});

	//replace the data-background into background image
	$(".blog-img-bg").each(function() {
		var imG = $(this).data('background');
		$(this).css('background-image', "url('" + imG + "') "

		);
	});
	
	//change h5 class for custom footer
	$(".iteck-custom-footer div[class*='elementor-widget-wp-'] h5").each(function() {
		$(this).addClass("elementor-heading-title");
	});
	
	//sticky custom header
	$('.elementor-section:has(.custom-sticky)').first().addClass('stuck-nav iteck-custom-nav'); 
	
	//adding/removing sticky menu class
	$('.stuck-nav').on('sticky-start', function() {
		$(this).addClass('iteck-sticky-menu');
		$(this).find('.iteck-nav,.mobile-wrapper').addClass('iteck-stick')
	});
	$('.stuck-nav').on('sticky-end', function() {
		$(this).removeClass('iteck-sticky-menu');
		$(this).find('.iteck-nav,.mobile-wrapper').removeClass('iteck-stick')
	});
	
	//add class for hovering team & hovering icon
	$('.elementor-widget-iteck-team-hover,.elementor-widget-iteck-texticon-hover').each(function() {
		$(this).closest('.elementor-column-wrap').addClass('hovering');
	});

	//-----------------------------Woocommerce------------------------------------------------------//

	$('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>'
	).insertAfter('.quantity input');

	$('.quantity').each(function() {
		var t, n, o, i;
	  var e = $(this);
		t = e.find('input[type="number"]');
		n = e.find('.quantity-up');
		o = e.find('.quantity-down');
		i = t.attr('min');
	  n.on('click', function() {
		var n = parseFloat(t.val());
		  o = n + 1;
		e.find('input').val(o), e.find('input').trigger('change');
	  });

	  o.on('click', function() {
		  var n = parseFloat(t.val());
		  if (n <= i) var o = n;
		  else var o = n - 1;
		  e.find('input').val(o), e.find('input').trigger('change');
		});

	  });

	$(function () {
		var wow = new WOW({
			boxClass: 'wow',
			animateClass: 'animated',
			offset: 0,
			mobile: false,
			live: true,
			scrollContainer: null,
		});
		wow.init();
	});

//remove empty tags
$('strong:empty').remove();
$('p:empty').not('[role="status"]').remove();
$("form.track_order").unwrap();

	//-----------------------------Cursor animation ------------------------------------------------------//

	function mousecursor() {
	    if ($("body")) {
	        const e = document.querySelector(".cursor-inner"),
	            t = document.querySelector(".cursor-outer");
	        let n, i = 0,
	            o = !1;
	        window.onmousemove = function (s) {
	            o || (t.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)"), e.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)", n = s.clientY, i = s.clientX
	        }, $("body").on("mouseenter", "a, .cursor-pointer", function () {
	            e.classList.add("cursor-hover"), t.classList.add("cursor-hover")
	        }), $("body").on("mouseleave", "a, .cursor-pointer", function () {
	            $(this).is("a") && $(this).closest(".cursor-pointer").length || (e.classList.remove("cursor-hover"), t.classList.remove("cursor-hover"))
	        }), e.style.visibility = "visible", t.style.visibility = "visible"
	    }
	};
 
	if ($(".mouse-cursor").size()) {
	    mousecursor();
	}
	


	//----------------------------Chatc Animation-------------------------------------------------------//
	// Go through a sentence, wrap its characters with spans
	function setUpCharacters() {
	  var $sentences = $('.iteck-fadinup-split .elementor-heading-title');

	  // Run for each sentence
	  $sentences.each(function() {
	    var $sentence = $(this);
	    var newContent = '';
	    var i=0;

	    // Go through all characters of the sentence
	    for (i = 0; i < $sentence.text().length; i++) {
	      var substring = $sentence.text().substr(i, 1);

	      // If we have a character, wrap it
	      if (substring != " ") {
	        newContent += '<span style="--char-index:'+i+';">' + substring +'</span>';
	      } else {
	        newContent += substring;
	      } 
	    }

	    // Replace content
	    $sentence.html(newContent); 
	  });
	}
	setUpCharacters();


    //Cache reference to window and animation items
	var $animation_elements = $('.iteck-fadinup-split .elementor-heading-title span');
	var $window = $(window);

	function Iteck_check_if_in_view() {
	  var window_height = $window.height();
	  var window_top_position = $window.scrollTop();
	  var window_bottom_position = (window_top_position + window_height);
	 
	  $.each($animation_elements, function() {
	    var $element = $(this);
	    var element_height = $element.outerHeight();
	    var element_top_position = $element.offset().top;
	    var element_bottom_position = (element_top_position + element_height);
	 
	    //check to see if this current container is within viewport
	    if ((element_bottom_position >= window_top_position) &&
	        (element_top_position <= window_bottom_position)) {
	      $element.addClass('active');
	    } 
	  });
	}

	$window.on('scroll resize', Iteck_check_if_in_view);
	$window.trigger('scroll');


    /* ===============================  fade slideshow  =============================== */ 
	$(window).scroll(function () {
	    var scrolled = $(this).scrollTop();
	    $('.fixed-slider .caption , .fixed-slider .capt .parlx').css({
	        'transform': 'translate3d(0, ' + -(scrolled * 0.20) + 'px, 0)',
	        'opacity': 1 - scrolled / 600
	    });
	});

	/* ===============================  Scroll back to top  =============================== */

	$(document).ready(function () {
	    "use strict";

	    var progressPath = document.querySelector('.progress-wrap path');
	    var pathLength = progressPath.getTotalLength();
	    progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
	    progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
	    progressPath.style.strokeDashoffset = pathLength;
	    progressPath.getBoundingClientRect();
	    progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';
	    var updateProgress = function () {
	        var scroll = $(window).scrollTop();
	        var height = $(document).height() - $(window).height();
	        var progress = pathLength - (scroll * pathLength / height);
	        progressPath.style.strokeDashoffset = progress;
	    }
	    updateProgress();
	    $(window).scroll(updateProgress);
	    var offset = 150;
	    var duration = 550;
	    jQuery(window).on('scroll', function () {
	        if (jQuery(this).scrollTop() > offset) {
	            jQuery('.progress-wrap').addClass('active-progress');
	        } else {
	            jQuery('.progress-wrap').removeClass('active-progress');
	        }
	    });
	    jQuery('.progress-wrap').on('click', function (event) {
	        event.preventDefault();
	        jQuery('html, body').animate({ scrollTop: 0 }, duration);
	        return false;
	    })


	});

	/* ===============================  Progress  =============================== */ 


	function iteck_load_content_area_scripts($) {

	    /* progress circle */ 
	    $('.skills.style-1').each(function () {
	        var startcolor = $(this).data('bgcolor'),
	            endcolor = $(this).data('fgcolor'),
	            num = $(this).data('num'),
	            speed = $(this).data('speed'),
	            thickness = $(this).data('thickness'),
	            suffix = $(this).data('suffix');
	        $(this).circleProgress({
	            value: 1,
	            fill: endcolor,
	            emptyFill: startcolor,
	            thickness: thickness,
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

	/* ===============================  Progress bar  =============================== */
	var wind = $(window);
    wind.on('scroll', function () {
        $(".skill-progress .progres").each(function () {
            var bottom_of_object =
                $(this).offset().top + $(this).outerHeight();
            var bottom_of_window =
                $(window).scrollTop() + $(window).height();
            var myVal = $(this).attr('data-value');
            if (bottom_of_window > bottom_of_object) {
                $(this).css({
                    width: myVal
                });
            }
        });
    });


  	/* ===============================  Intersection Observer  =============================== */

	  if (!!window.IntersectionObserver) {
		let observer = new IntersectionObserver((entries, observer) => {
		  entries.forEach(entry => {
			if (entry.isIntersecting) {
			  entry.target.classList.add("animated");
			  observer.unobserve(entry.target);
			}
		  });
		}, {
		  rootMargin: "0px 0px -100px 0px"
		});
		document.querySelectorAll('.animate').forEach(block => {
		  observer.observe(block)
		});
	  } else {
		document.querySelectorAll('.animate').forEach(block => {
		  block.classList.remove('animate')
		});
	  }

	/* ===============================  Swiper showcases with data  =============================== */


	$('[data-carousel="swiper"]').each(function () {

	    var containe = $(this).find('[data-swiper="container"]').attr('id');
	    var pagination = $(this).find('[data-swiper="pagination"]').attr('id');
	    var prev = $(this).find('[data-swiper="prev"]').attr('id');
	    var next = $(this).find('[data-swiper="next"]').attr('id');
	    var items = $(this).data('items');
	    var autoplay = $(this).data('autoplay');
	    var iSlide = $(this).data('initial');
	    var loop = $(this).data('loop');
	    var parallax = $(this).data('parallax');
	    var space = $(this).data('space');
	    var speed = $(this).data('speed');
	    var center = $(this).data('center');
	    var effect = $(this).data('effect');
	    var direction = $(this).data('direction');
	    var mousewheel = $(this).data('mousewheel');

	    // Configuration
	    var conf = {

	    };

	    // Responsive
	    if ($(this).hasClass('showcase-grid')) {
	        var conf = {

	            navigation: {
	                nextEl: '.swiper-button-next',
	                prevEl: '.swiper-button-prev'
	            },

	            breakpoints: {
	                0: {
	                    slidesPerView: 1,
	                },
	                640: {
	                    slidesPerView: 2,
	                },
	                768: {
	                    slidesPerView: 2,
	                },
	                1024: {
	                    slidesPerView: 4,
	                },
	            }
	        };
	    };

	    if ($(this).hasClass('showcase-carus')) { 
	        var conf = {

	            navigation: {
	                nextEl: '.swiper-button-next',
	                prevEl: '.swiper-button-prev'
	            },

	            breakpoints: {
	                0: {
	                    slidesPerView: 1,
	                    spaceBetween: 0,
	                },
	                640: {
	                    slidesPerView: 1,
	                    spaceBetween: 0,
	                },
	                768: {
	                    slidesPerView: 2,
	                    spaceBetween: 30,
	                },
	                1024: {
	                    slidesPerView: 2,
	                    spaceBetween: 200,
	                },
	            }
	        };
	    };

	    if (items) {
	        conf.slidesPerView = items
	    };
	    if (autoplay) {
	        conf.autoplay = autoplay
	    };
	    if (iSlide) {
	        conf.initialSlide = iSlide
	    };
	    if (center) {
	        conf.centeredSlides = center
	    };
	    if (loop) {
	        conf.loop = loop
	    };
	    if (parallax) {
	        conf.parallax = parallax
	    };
	    if (space) {
	        conf.spaceBetween = space
	    };
	    if (speed) {
	        conf.speed = speed
	    };
	    if (mousewheel) {
	        conf.mousewheel = mousewheel
	    };
	    if (effect) {
	        conf.effect = effect
	    };
	    if (direction) {
	        conf.direction = direction
	    };
	    if (prev) {
	        conf.prevButton = '#' + prev
	    };
	    if (next) {
	        conf.nextButton = '#' + next
	    };
	    if (pagination) {
	        conf.pagination = '#' + pagination,
	            conf.paginationClickable = true
	    };

	    // Initialization
	    if (containe) {
	        var initID = '#' + containe;
	        var init = new Swiper(initID, conf);
	    };
	});

	/* ===============================  offcanvas  ======================================= */
    var open = false;

    $('.iteck-offcanvas .menu-icon').on('click', function () {
        open = !open;

        $('.side-panel').toggleClass("open");
        $('.iteck-offcanvas .menu-icon .text').toggleClass("open");
    });

    /* ===============================  Preloader page   =============================== */

		var paceOptions = { 
		    ajax: true,
		    document: true,
		    eventLag: false
		};

		Pace.on('done', function () {
		    $('#preloader').addClass("isdone");
		    $('.loading-text').addClass("isdone");
		});
    /* ===============================  Dark/Light switcher   =============================== */ 

	    function handlePageColorSwitcher() { 
	        var switcher = $('.iteck-mode-switcher');
	        var switcherOn = $('.iteck-mode-switcher-item');
	        var switcherDark = $('.iteck-mode-switcher-item.dark');
	        var switcherAuto = $('.iteck-mode-switcher-item.auto');
	        var switcherLight = $('.iteck-mode-switcher-item.light');
	        var switcherItems = switcher.find('.iteck-mode-switcher-item, .iteck-mode-switcher-toddler');
	        
	        equalSize(switcherItems, 'width');
	        
	        if ($('body').hasClass('iteck-dark-mode'))   {
	            switcher.addClass('dark');
	        }else if ($('body').hasClass('iteck-auto-mode'))   {
	            switcher.addClass('auto');
	        }

	        switcherDark.on('click', function(){
	            switcherOn.removeClass('on');
	            switcherDark.addClass('on');
	            switcher.removeClass('light auto');
	            switcher.addClass('dark');
	            $('body').removeClass('iteck-auto-mode');
	            $('body').addClass('iteck-dark-mode');

	        });
	        switcherAuto.on('click', function(){
	            switcherOn.removeClass('on');
	            switcherAuto.addClass('on');
	            switcher.removeClass('dark light');
	            switcher.addClass('auto');
	            $('body').removeClass('iteck-dark-mode');
	            $('body').addClass('iteck-auto-mode');
	        });
	        switcherLight.on('click', function(){
	            switcherOn.removeClass('on');
	            switcherLight.addClass('on');
	            switcher.removeClass('dark auto');
	            switcher.addClass('light');
	            $('body').removeClass('iteck-dark-mode');
	            $('body').removeClass('iteck-auto-mode');
	        });

   		}
	    function equalSize(items, attr) {
	        var maxSize = 0;
	        var value = 0;

	        items.each(function(){
	            value = $(this).css(attr).replace(/(^\d+)(.+$)/i,'$1');
	            
	            if (value > maxSize) {
	                maxSize = +($(this).css(attr).replace(/(^\d+)(.+$)/i,'$1'));
	            }
	        });

	        var css = {};
	        css[attr] = maxSize;

	        items.css(css);
	    }

	/* ===============================  Excute work  =============================== */ 
		$(window).on('load', function () {
			handlePageColorSwitcher();
		});
	/* ===============================  Counter  =============================== */
		var counterContainer = $('.counter');
	    if (counterContainer.length) {
			console.log(counterContainer);
	        counterContainer.countUp({
	            delay: counterContainer.data('itecksteps'),
	            time: counterContainer.data('iteckspeed')
	        });
	    }

    /* ===============================  sticky header/footer  =============================== */  

	$(window).on("scroll", function(){

		var site_header = $('.iteck-custom-header').outerHeight() + 1;
		if ($(window).scrollTop() >= site_header) {	    	
			$('.iteck-sticky-top').addClass('is-stuck');	
		}else {
			$('.iteck-sticky-top').removeClass('is-stuck');		              
		}

		var site_footer = $('.iteck-custom-footer').outerHeight() + 1;
		if ($(window).scrollTop() + $(window).height() >= site_footer) {	    	
			$('.iteck-sticky-bottom').addClass('is-stuck');	
		}else {
			$('.iteck-sticky-bottom').removeClass('is-stuck');		              
		}
	});
	
	/* ===============================  related-postes-slider  =============================== */ 

    var swiper = new Swiper('.related-postes-slider .swiper-container', {
        slidesPerView: 3,
        spaceBetween: 80,
        centeredSlides: true,
        speed: 1000,
        pagination: false,
        navigation: {
            nextEl: '.related-postes-slider .swiper-button-next',
            prevEl: '.related-postes-slider .swiper-button-prev',
        },
        mousewheel: false,
        keyboard: true,
        autoplay: {
            delay: 4000,
        },
        loop: true,
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            480: {
                slidesPerView: 1,
            },
            787: {
                slidesPerView: 2,
            },
            991: {
                slidesPerView: 2,
            },
            1200: {
                slidesPerView: 3,
            }
        }
    });

	/* ===============================  Blog Slider  =============================== */ 

	var swiper = new Swiper('.blog-details-slider .swiper-container', {
		slidesPerView: 1,
		spaceBetween: 0,
		effect: "fade",
		speed: 1000,
		pagination: {
			el: ".blog-details-slider .swiper-pagination",
			clickable: "true",
		},
		navigation: {
			nextEl: '.blog-details-slider .swiper-button-next',
			prevEl: '.blog-details-slider .swiper-button-prev',
		},
		mousewheel: false,
		keyboard: true,
		autoplay: {
			delay: 4000,
		},
		loop: true
	});


	/* ===============================  The end of scripts  =============================== */ 

})(jQuery);


