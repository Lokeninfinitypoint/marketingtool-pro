/**
 * Tab Component - Madgicx Style
 * Handles tab switching for hero sections
 */

(function() {
  'use strict';

  document.addEventListener('DOMContentLoaded', function() {
    initTabs();
  });

  function initTabs() {
    const tabContainers = document.querySelectorAll('.screen-tabs_component');
    
    tabContainers.forEach(container => {
      const tabs = container.querySelectorAll('.screen-tabs_link');
      const panes = container.querySelectorAll('.screen-tabs_pane');
      
      tabs.forEach((tab, index) => {
        tab.addEventListener('click', function(e) {
          e.preventDefault();
          
          // Remove active classes
          tabs.forEach(t => {
            t.classList.remove('w--current');
            t.setAttribute('aria-selected', 'false');
            t.setAttribute('tabindex', '-1');
          });
          
          panes.forEach(p => {
            p.classList.remove('w--tab-active');
            p.style.opacity = '0';
          });
          
          // Add active classes
          tab.classList.add('w--current');
          tab.setAttribute('aria-selected', 'true');
          tab.removeAttribute('tabindex');
          
          if (panes[index]) {
            panes[index].classList.add('w--tab-active');
            setTimeout(() => {
              panes[index].style.opacity = '1';
            }, 50);
          }
        });
      });
    });
  }
})();
