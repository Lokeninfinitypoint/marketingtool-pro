/**
 * WordStream Clone Theme JavaScript
 */

jQuery(document).ready(function($) {
    
    // Mobile Menu Toggle
    $('.ws-mobile-menu-toggle').on('click', function() {
        $(this).toggleClass('active');
        $('.ws-nav-menu').toggleClass('ws-nav-menu-open');
        $('body').toggleClass('menu-open');
    });
    
    // Close mobile menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.ws-navigation, .ws-mobile-menu-toggle').length) {
            $('.ws-nav-menu').removeClass('ws-nav-menu-open');
            $('.ws-mobile-menu-toggle').removeClass('active');
            $('body').removeClass('menu-open');
        }
    });
    
    // Smooth scroll for internal links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        var target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            }, 600);
        }
    });
    
    // Header scroll effects
    var header = $('.ws-header');
    var scrollThreshold = 100;
    
    $(window).on('scroll', function() {
        var scrollTop = $(window).scrollTop();
        
        if (scrollTop > scrollThreshold) {
            header.addClass('ws-header-scrolled');
        } else {
            header.removeClass('ws-header-scrolled');
        }
    });
    
    // Animate stats numbers
    function animateStats() {
        $('.ws-stat-number').each(function() {
            var $this = $(this);
            var countTo = $this.text();
            
            // Extract number from text like "142M", "$15.9B", etc.
            var number = parseFloat(countTo.replace(/[^\d.]/g, ''));
            var suffix = countTo.replace(/[\d.]/g, '');
            
            if (!isNaN(number)) {
                $({ countNum: 0 }).animate({
                    countNum: number
                }, {
                    duration: 2000,
                    easing: 'swing',
                    step: function() {
                        $this.text(Math.floor(this.countNum) + suffix);
                    },
                    complete: function() {
                        $this.text(countTo);
                    }
                });
            }
        });
    }
    
    // Trigger stats animation when in viewport
    function checkStatsInView() {
        var statsSection = $('.ws-stats');
        if (statsSection.length) {
            var sectionTop = statsSection.offset().top;
            var sectionBottom = sectionTop + statsSection.height();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();
            
            if (sectionBottom > viewportTop && sectionTop < viewportBottom) {
                if (!statsSection.hasClass('animated')) {
                    statsSection.addClass('animated');
                    animateStats();
                }
            }
        }
    }
    
    $(window).on('scroll', checkStatsInView);
    checkStatsInView(); // Check on page load
    
    // Form validation for grader tools
    $('.ws-grader-form').on('submit', function(e) {
        var isValid = true;
        var form = $(this);
        
        // Clear previous errors
        form.find('.ws-error').removeClass('ws-error');
        form.find('.ws-error-message').remove();
        
        // Validate required fields
        form.find('input[required], select[required]').each(function() {
            var field = $(this);
            var value = field.val().trim();
            
            if (!value) {
                field.addClass('ws-error');
                field.after('<span class="ws-error-message">This field is required.</span>');
                isValid = false;
            }
        });
        
        // Validate email fields
        form.find('input[type="email"]').each(function() {
            var field = $(this);
            var email = field.val().trim();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                field.addClass('ws-error');
                field.after('<span class="ws-error-message">Please enter a valid email address.</span>');
                isValid = false;
            }
        });
        
        // Validate URL fields
        form.find('input[type="url"]').each(function() {
            var field = $(this);
            var url = field.val().trim();
            var urlRegex = /^https?:\/\/.+\..+$/;
            
            if (url && !urlRegex.test(url)) {
                field.addClass('ws-error');
                field.after('<span class="ws-error-message">Please enter a valid URL (including http:// or https://).</span>');
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            var firstError = form.find('.ws-error').first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 300);
            }
        }
    });
    
    // Tool cards hover effects
    $('.ws-tool-card, .ws-card, .ws-blog-post').hover(
        function() {
            $(this).addClass('hovered');
        },
        function() {
            $(this).removeClass('hovered');
        }
    );
    
    // Loading states for buttons
    $('.ws-btn').on('click', function() {
        var btn = $(this);
        if (btn.hasClass('ws-btn-loading')) return false;
        
        // Add loading state for form submissions
        if (btn.attr('type') === 'submit' || btn.closest('form').length) {
            btn.addClass('ws-btn-loading');
            btn.attr('data-original-text', btn.text());
            btn.text('Loading...');
            
            // Remove loading state after 3 seconds (fallback)
            setTimeout(function() {
                btn.removeClass('ws-btn-loading');
                btn.text(btn.attr('data-original-text'));
            }, 3000);
        }
    });
    
    // Add visual feedback for newsletter signup
    $('.ws-newsletter-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var email = form.find('input[type="email"]').val();
        
        if (email) {
            // Show success message
            form.html('<div class="ws-success-message"><strong>Thanks for subscribing!</strong> Check your email for confirmation.</div>');
        }
    });
    
    // Lazy load images
    if ('IntersectionObserver' in window) {
        var imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(function(img) {
            imageObserver.observe(img);
        });
    }
    
    // Add scroll-to-top button
    var scrollButton = $('<button class="ws-scroll-to-top" title="Back to top">↑</button>');
    $('body').append(scrollButton);
    
    scrollButton.on('click', function() {
        $('html, body').animate({ scrollTop: 0 }, 600);
    });
    
    $(window).on('scroll', function() {
        if ($(window).scrollTop() > 300) {
            scrollButton.addClass('visible');
        } else {
            scrollButton.removeClass('visible');
        }
    });
    
    // Initialize tooltips (if any)
    $('[data-tooltip]').each(function() {
        var element = $(this);
        var tooltip = $('<div class="ws-tooltip">' + element.attr('data-tooltip') + '</div>');
        
        element.hover(
            function() {
                $('body').append(tooltip);
                tooltip.fadeIn(200);
            },
            function() {
                tooltip.remove();
            }
        ).mousemove(function(e) {
            tooltip.css({
                left: e.pageX + 10,
                top: e.pageY + 10
            });
        });
    });
});

// Google Analytics integration (if GA is loaded)
function trackEvent(category, action, label, value) {
    if (typeof gtag !== 'undefined') {
        gtag('event', action, {
            event_category: category,
            event_label: label,
            value: value
        });
    }
}

// Track button clicks
jQuery(document).ready(function($) {
    $('.ws-btn').on('click', function() {
        var buttonText = $(this).text().trim();
        var buttonHref = $(this).attr('href') || '';
        trackEvent('Button Click', 'click', buttonText + ' - ' + buttonHref);
    });
    
    $('.ws-tool-card a').on('click', function() {
        var toolName = $(this).closest('.ws-tool-card').find('h4').text();
        trackEvent('Tool Access', 'click', toolName);
    });
});