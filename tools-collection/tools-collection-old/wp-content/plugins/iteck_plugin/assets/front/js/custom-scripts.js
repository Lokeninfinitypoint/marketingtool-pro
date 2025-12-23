
!(function ($) {


    

    function postListCarousel($scope) {
        // ------------ blog sliders -----------
        var swiper = new Swiper('.iteck-post-list-carousel .blog_slider .swiper-container', {
            slidesPerView: 3,
            spaceBetween: 24,
            speed: 1000,
            pagination: {
                el: ".iteck-post-list-carousel .blog_slider .swiper-pagination"
            },
            navigation: {
                nextEl: '.iteck-post-list-carousel .blog_slider .swiper-button-next',
                prevEl: '.iteck-post-list-carousel .blog_slider .swiper-button-prev',
            },
            mousewheel: false,
            keyboard: true,
            autoplay: {
                delay: 4000,
            },
            // loop: true,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                480: {
                    slidesPerView: 2,
                },
                787: {
                    slidesPerView: 2,
                },
                991: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 4,
                }
            }
        });
    }

    /* Portfolio-carousel */ 
    function iteckPortfolioCarousel($scope, $) {
        $scope.find('.iteck-portfolio-carousel').each(function () {
            var slider;
            var nextArrow;
            var prevArrow;
            var pagination;
            var sliderItems = $(this).data('slider-items');
            var spaceBetween = $(this).data('slides-spacing');
            $scope.find('.iteck-portfolio-carousel .portfolio_slider .swiper-container').each(function () {
                slider = this;
            });
            $scope.find('.iteck-portfolio-carousel .portfolio_slider .swiper-button-next').each(function () {
                nextArrow = this;
            });
            $scope.find('.iteck-portfolio-carousel .portfolio_slider .swiper-button-prev').each(function () {
                prevArrow = this;
            });
            $scope.find('.iteck-portfolio-carousel .portfolio_slider .swiper-pagination').each(function () {
                pagination = this;
            });
            var swiper = new Swiper(slider, {
                slidesPerView: sliderItems,
                spaceBetween: spaceBetween,
                speed: 1000,
                pagination: {
                    el: pagination
                },
                navigation: {
                    nextEl: nextArrow,
                    prevEl: prevArrow,
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
                        slidesPerView: 2,
                    },
                    787: {
                        slidesPerView: 2,
                    },
                    991: {
                        slidesPerView: sliderItems,
                    },
                    1200: {
                        slidesPerView: sliderItems,
                    }
                }
            });
        });
    }

    function iteckTestimonialsSlider(){
        var slidesPerView = $('.iteck-testimonials-slider').data('slider-settings');
        var swiper = new Swiper('.iteck-testimonials-slider .testi-slider', {
            slidesPerView: 3,
            spaceBetween: 30,
            centeredSlides: true,
            speed: 1500,
            pagination: false,
            navigation: {
                nextEl: '.iteck-testimonials-slider .swiper-button-next',
                prevEl: '.iteck-testimonials-slider .swiper-button-prev',
            },
            mousewheel: false,
            keyboard: true,
            autoplay: {
                delay: 5000,
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
                    slidesPerView: slidesPerView.items,
                },
                991: {
                    slidesPerView: slidesPerView.items,
                },
                1200: {
                    slidesPerView: slidesPerView.items,
                }
            }
        });
    }

    function iteckTestimonialsShow(){
        var swiper = new Swiper('.iteck-testimonials-show .swiper-container', {
            spaceBetween: 30,
            // centeredSlides: true,
            slidesPerView: 3,
            direction: 'vertical',
            speed: 1500,
            autoplay: {
                delay: 6000,
            },
            loop: true,
            pagination: false,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
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
    }

    /* Portfolio-carousel */ 
    function iteckPortfolioTabsCarousel($scope, $) {
        var swiper = new Swiper('.iteck-slider-tabs-portfolio.style-1 .swiper-container', {
            // slidesPerView: 5,
            spaceBetween: 40,
            // centeredSlides: true,
            speed: 1000,
            pagination: false,
            navigation: {
                nextEl: '.iteck-slider-tabs-portfolio .swiper-button-next',
                prevEl: '.iteck-slider-tabs-portfolio .swiper-button-prev',
            },
            mousewheel: false,
            keyboard: true,
            autoplay: {
                delay: 5000,
            },
            observer: true,
            observeParents: true,
            loop: false,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                480: {
                    slidesPerView: 1,
                },
                787: {
                    slidesPerView: 1,
                },
                991: {
                    slidesPerView: 2,
                },
                1200: {
                    slidesPerView: 2,
                }
            }
        });
        
        var swiper = new Swiper('.iteck-slider-tabs-portfolio.style-2 .swiper-container', {
            spaceBetween: 30,
            // centeredSlides: true,
            // slidesPerView: 6,
            observer: true,
            observeParents: true,
            speed: 1000,
            autoplay: {
                delay: 5000,
            },
            loop: true,
            pagination: false,
            navigation: false,
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
                    slidesPerView: 2,
                }
            }
        });
    }

    /* Info Box Carousel */ 
    function iteckInfoBoxCarousel($scope, $) {
        // ------------ services sliders -----------
        var swiper = new Swiper('.iteck-info-box-carousel.carousel .services_slider.normal-slider .swiper-container', {
            slidesPerView: 3,
            spaceBetween: 24,
            speed: 1000,
            pagination: {
                el: ".iteck-info-box.carousel .services_slider .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: '.iteck-info-box.carousel .services_slider .swiper-button-next',
                prevEl: '.iteck-info-box.carousel .services_slider .swiper-button-prev',
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
                    slidesPerView: 2,
                },
                787: {
                    slidesPerView: 2,
                },
                991: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 3,
                }
            }
        });
        var swiper = new Swiper('.iteck-info-box-carousel.carousel .services_slider.continued-slide .swiper-container', {
            spaceBetween: 50,
            // centeredSlides: true,
            // slidesPerView: 6,
            speed: 10000,
            autoplay: {
                delay: 1,
            },
            loop: true,
            //allowTouchMove: false,
            disableOnInteraction: true,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                480: {
                    slidesPerView: 1,
                },
                787: {
                    slidesPerView: 1,
                },
                991: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 4,
                }
            }
        });
    }

    /* Info Box Wave Carousel */ 
    function iteckInfoBoxWaveCarousel($scope, $) {
        var swiper = new Swiper('.iteck-info-box-carousel.wave-carousel .services_slider .swiper-container', {
            slidesPerView: 6,
            centeredSlides: true,
            spaceBetween: 0,
            speed: 1000,
            pagination: false,
            navigation: false,
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
                    slidesPerView: 4,
                },
                1200: {
                    slidesPerView: 6,
                }
            },
            on: {
                slideChange: function () {
                    var activeIndex = this.activeIndex;
                    var realIndex = this.slides.eq(activeIndex).attr('data-swiper-slide-index');
                    $('.swiper-slide').removeClass('swiper-slide-nth-prev-2 swiper-slide-nth-next-2');
                    $('.swiper-slide[data-swiper-slide-index="'+realIndex+'"]').prev().prev().addClass('swiper-slide-nth-prev-2');
                    $('.swiper-slide[data-swiper-slide-index="'+realIndex+'"]').next().next().addClass('swiper-slide-nth-next-2');
                },
            }
        });
    }

    /* Video Card Carousel */ 
    function iteckVideoCardCarousel($scope, $) {
        var swiper = new Swiper('.iteck-video-card-carousel .swiper-container', {
            slidesPerView: 1,
            spaceBetween: 0,
            speed: 1000,
            pagination: {
                el: ".iteck-video-card-carousel .swiper-pagination",
                clickable: true,
            },
            navigation: false,
            mousewheel: false,
            keyboard: true,
            autoplay: {
                delay: 6000,
            },
            loop: true,
        });
    }

    /* Info Box hover Carousel */ 
    function iteckInfoBoxHoverCardsCarousel($scope, $) {
        var swiper = new Swiper('.iteck-info-box-carousel.hover-cards .services_slider .swiper-container', {
            spaceBetween: 50,
            centeredSlides: true,
            // slidesPerView: 6,
            speed: 10000,
            autoplay: {
                delay: 1,
            },
            loop: true,
            // allowTouchMove: false,
            disableOnInteraction: true,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                480: {
                    slidesPerView: 1,
                },
                787: {
                    slidesPerView: 1,
                },
                991: {
                    slidesPerView: 2,
                },
                1200: {
                    slidesPerView: 3,
                }
            }
        });
    }

    /* Projects Carousel */ 
    function iteckProjectsCarousel($scope, $) {
        var swiper = new Swiper('.iteck-projects-carousel .swiper-container', {
            slidesPerView: 1,
            spaceBetween: 0,
            speed: 1200,
            pagination: false,
            navigation: {
                nextEl: '.iteck-projects-carousel .swiper-button-next',
                prevEl: '.iteck-projects-carousel .swiper-button-prev',
            },
            mousewheel: false,
            keyboard: true,
            autoplay: {
                delay: 4000,
            },
            loop: false,
        });
    }

    /* Testimonails Carousel */ 
    function iteckTestimonialCarousel($scope, $) {
        // ------------ Testiominals sliders -----------
        var slidesPerView = $('.iteck-testimonial-carousel').data('slider-settings');
        
        var swiperParams = {
            slidesPerView: slidesPerView.items,
            spaceBetween: 0,
            speed: 1000,
            pagination: {
                el: ".iteck-testimonial-carousel .reviews_slider .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: '.iteck-testimonial-carousel .reviews_slider .swiper-button-next',
                prevEl: '.iteck-testimonial-carousel .reviews_slider .swiper-button-prev',
            },
            mousewheel: false,
            keyboard: true,
            autoplay: {
                delay: 4000,
            },
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
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: slidesPerView.items,
                }
            },
            loop: true,
        }

        if(slidesPerView.items < 2){
            swiperParams.effect = "fade"
        };

        var swiper = new Swiper('.iteck-testimonial-carousel.style-1 .reviews_slider .swiper-container', swiperParams );
    };

    /* Testimonails Carousel */ 
    function iteckCollections($scope, $) {
        
        var swiper = new Swiper('.iteck-collections .collections-slider .swiper-container', {
            spaceBetween: 30,
            // centeredSlides: true,
            // slidesPerView: 6,
            speed: 1000,
            autoplay: {
                delay: 5000,
            },
            loop: false,
            pagination: false,
            navigation: false,
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
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 3,
                }
            }
        });
    };

    /* Testimonails Carousel */ 
    function iteckTestimonialCarousel2($scope, $) {
        
        var slidesPerView = $('.iteck-testimonial-carousel').data('slider-settings');

        // ------------ Testiominals sliders -----------
        var swiper = new Swiper('.iteck-testimonial-carousel.style-2 .reviews_slider .swiper-container', {
            slidesPerView: slidesPerView.items,
            spaceBetween: 100,
            speed: 1000,
            pagination: {
                el: ".iteck-testimonial-carousel.style-2 .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: '.iteck-testimonial-carousel.style-2 .swiper-button-next',
                prevEl: '.iteck-testimonial-carousel.style-2 .swiper-button-prev',
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
                    slidesPerView: slidesPerView.items,
                }
            }
        });
    };

    /* Testimonails Carousel */ 
    function iteckTestimonialCarousel3($scope, $) {
        
        var slidesPerView = $('.iteck-testimonial-carousel').data('slider-settings');

        // ------------ Testiominals sliders -----------
        var swiper = new Swiper('.iteck-testimonial-carousel.cards-style .reviews_slider .swiper-container', {
            spaceBetween: 50,
            // centeredSlides: true,
            // slidesPerView: 6,
            speed: 1000,
            autoplay: {
                delay: 5000,
            },
            loop: false,
            pagination: false,
            navigation: false,
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
                    slidesPerView: slidesPerView.items,
                }
            }
        });
    };

    /* Screenshots Carousel */ 
    function iteckSceenshots($scope, $) {
        var swiper = new Swiper('.iteck-screenshots .screenshots-slider .swiper-container', {
            slidesPerView: 5,
            spaceBetween: 0,
            centeredSlides: true,
            speed: 1000,
            pagination: false,
            navigation: false,
            mousewheel: false,
            keyboard: true,
            autoplay: {
                delay: 4000,
            },
            loop: true,
            breakpoints: {
                0: {
                    slidesPerView: 2,
                },
                480: {
                    slidesPerView: 2,
                },
                787: {
                    slidesPerView: 3,
                },
                991: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 5,
                }
            }
        });
    }

    /* Related portfolio */ 
    function iteckRelatedPortfolios($scope, $) {
        var swiper = new Swiper('.slider-3items .swiper-container', {
            slidesPerView: 3,
            spaceBetween: 0,
            speed: 1000,
            pagination: {
                el: ".slider-3items .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: '.slider-3items .swiper-button-next',
                prevEl: '.slider-3items .swiper-button-prev',
            },
            mousewheel: false,
            keyboard: true,
            autoplay: {
                delay: 4000,
            },
            loop: false,
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
    }

    // ------------ clients sliders -----------
    function iteckClientsCarousel($scope, $) {
        $scope.find('.iteck-clients-carousel .swiper-container').each(function () {
            let SwiperBottom = new Swiper(this, {
                spaceBetween: $(this).data('space-between'),
                centeredSlides: true,
                slidesPerView: $(this).data('slides'),
                speed: 6000,
                autoplay: {
                    delay: 1,
                },
                loop: true,
                allowTouchMove: false,
                disableOnInteraction: true,
                breakpoints: {
                    0: {
                        slidesPerView: 2,
                    },
                    480: {
                        slidesPerView: 2,
                    },
                    787: {
                        slidesPerView: 3,
                    },
                    991: {
                        slidesPerView: $(this).data('slides'),
                    },
                    1200: {
                        slidesPerView: $(this).data('slides'),
                    }
                }
            });
        });
    }

    function iteckTextCarousel($scope, $) {
        var swiper = new Swiper('.iteck-text-carousel .swiper-container', {
            spaceBetween: 0,
            centeredSlides: true,
            speed: 10000,
            autoplay: {
                delay: 1,
            },
            loop: true,
            disableOnInteraction: true,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                480: {
                    slidesPerView: 1,
                },
                787: {
                    slidesPerView: 1,
                },
                991: {
                    slidesPerView: 2,
                },
                1200: {
                    slidesPerView: 2,
                }
            }
        });
    }

    function iteckCareersCarousel($scope, $) {
        $scope.find('.iteck-careers-slider').each(function () {
            var slider;
            var nextArrow;
            var prevArrow;
            $scope.find('.iteck-careers-slider .swiper-container').each(function () {
                slider = this;
            });
            $scope.find('.iteck-careers-slider .swiper-button-next').each(function () {
                nextArrow = this;
            });
            $scope.find('.iteck-careers-slider .swiper-button-prev').each(function () {
                prevArrow = this;
            });
            var swiper = new Swiper(slider, {
                spaceBetween: 30,
                // centeredSlides: true,
                // slidesPerView: 6,
                speed: 1000,
                autoplay: {
                    delay: 5000,
                },
                loop: true,
                pagination: false,
                navigation: {
                    nextEl: nextArrow,
                    prevEl: prevArrow,
                },
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
                        slidesPerView: 3,
                    },
                    1200: {
                        slidesPerView: 3,
                    }
                }
            });
        });
    };

    function iteckInstagramSlider(){
            // instagram-slider
        var swiper = new Swiper('.iteck-instagram-slider', {
            slidesPerView: 5,
            spaceBetween: 0,
            centeredSlides: true,
            pagination: false,
            navigation: false,
            mousewheel: false,
            keyboard: true,
            speed: 30000,
            allowTouchMove: false,
            autoplay: {
                delay: 1,
            },
            loop: true,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                480: {
                    slidesPerView: 2,
                },
                787: {
                    slidesPerView: 3,
                },
                991: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 5,
                }
            }
        });
    }

    // ------------ clients sliders -----------
    function iteckImagesCarousel($scope, $) {
        var swiper = new Swiper('.iteck-images-carousel .swiper-container', {
            slidesPerView: 4,
            spaceBetween: 30,
            centeredSlides: true,
            speed: 1000,
            pagination: {
                el: ".swiper-pagination",
                clickable: "true",
            },
            navigation: false,
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
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 4,
                }
            }
        });
    }

    jQuery(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-portfolio-carousel.default', iteckPortfolioCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-portfolio-carousel.default', iteckPortfolioTabsCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-post-list-carousel.default', postListCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-infobox-carousel.default', iteckInfoBoxCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-text-carousel.default', iteckTextCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-infobox-carousel.default', iteckInfoBoxHoverCardsCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-infobox-carousel.default', iteckInfoBoxWaveCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-testimonials-slider.default', iteckTestimonialsSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-testimonial-carousel.default', iteckTestimonialCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-testimonial-carousel.default', iteckTestimonialCarousel2);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-testimonial-carousel.default', iteckTestimonialCarousel3);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-testimonials-show.default', iteckTestimonialsShow);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-instagram-cards.default', iteckInstagramSlider);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-screenshots.default', iteckSceenshots);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-related-portfolios.default', iteckRelatedPortfolios);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-clients-carousel.default', iteckClientsCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-careers.default', iteckCareersCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-images-carousel.default', iteckImagesCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-projects-carousel.default', iteckProjectsCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-video-card-carousel.default', iteckVideoCardCarousel);
        elementorFrontend.hooks.addAction('frontend/element_ready/iteck-collections.default', iteckCollections);

    });


})(jQuery); 
