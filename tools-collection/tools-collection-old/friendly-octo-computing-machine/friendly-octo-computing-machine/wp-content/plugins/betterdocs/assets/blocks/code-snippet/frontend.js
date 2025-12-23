/******/ (() => { // webpackBootstrap
/*!*****************************************************************!*\
  !*** ./react-src/gutenberg/blocks/code-snippet/src/frontend.js ***!
  \*****************************************************************/
/**
 * BetterDocs Code Snippet Block Frontend JavaScript
 * This file handles the frontend functionality for the code snippet block
 */

// Initialize code snippets when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
  // Check if the main code snippet functionality is available
  // It should be loaded separately via the main assets
  if (window.BetterDocsCodeSnippet) {
    window.BetterDocsCodeSnippet.init();
  } else {
    // If not available, wait a bit and try again
    setTimeout(function () {
      if (window.BetterDocsCodeSnippet) {
        window.BetterDocsCodeSnippet.init();
      }
    }, 100);
  }
});
/******/ })()
;
//# sourceMappingURL=frontend.js.map