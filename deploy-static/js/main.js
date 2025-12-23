/**
 * AITool.Software - Main Website JavaScript
 * Handles navigation, menu interactions, and shared functionality
 */

(function() {
  'use strict';

  // Initialize when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initHeader();
    initMobileMenu();
    initAnimations();
  });

  /**
   * Initialize header behavior
   */
  function initHeader() {
    const header = document.querySelector('.header');
    if (!header) return;

    let lastScroll = 0;
    
    window.addEventListener('scroll', function() {
      const currentScroll = window.pageYOffset;
      
      if (currentScroll > 100) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
      
      lastScroll = currentScroll;
    });
  }

  /**
   * Initialize mobile menu
   */
  function initMobileMenu() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuToggle && navMenu) {
      mobileMenuToggle.addEventListener('click', function() {
        navMenu.classList.toggle('active');
        mobileMenuToggle.classList.toggle('active');
      });
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
      if (navMenu && navMenu.classList.contains('active')) {
        if (!navMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
          navMenu.classList.remove('active');
          if (mobileMenuToggle) mobileMenuToggle.classList.remove('active');
        }
      }
    });
  }

  /**
   * Initialize scroll animations (Madgicx-style)
   */
  function initAnimations() {
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -100px 0px'
    };

    // Standard fade-in animations
    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('fade-in-up');
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);

    // Observe elements with animation class
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
      observer.observe(el);
    });

    // Madgicx-style staggered scroll animations
    const staggeredObserver = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          // Apply transform and opacity reset
          entry.target.style.transform = 'translate3d(0px, 0rem, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg)';
          entry.target.style.opacity = '1';
          staggeredObserver.unobserve(entry.target);
        }
      });
    }, observerOptions);

    // Observe staggered animation elements
    document.querySelectorAll('.is-first-scroll-in, .is-second-scroll-in, .is-third-scroll-in, .is-fourth-scroll-in').forEach(el => {
      staggeredObserver.observe(el);
    });

    // Animate slot elements (for feature screens)
    const slotObserver = new IntersectionObserver(function(entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          staggeredObserver.unobserve(entry.target);
        }
      });
    }, observerOptions);

    document.querySelectorAll('.animate-slot').forEach(el => {
      slotObserver.observe(el);
    });
  }

  /**
   * Smooth scroll for anchor links
   */
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href === '#') return;
      
      const target = document.querySelector(href);
      if (target) {
        e.preventDefault();
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });

})();
