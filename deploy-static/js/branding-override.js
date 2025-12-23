/**
 * MarketingTool.pro Branding Override
 * Production-grade: Handles 425 tools, edge cases, performance optimized
 */

(function () {
  "use strict";

  // Configuration
  const CONFIG = {
    pollInterval: 1500,
    maxReplacements: 10000, // Prevent infinite loops
    debounceDelay: 100,
    observerOptions: {
      childList: true,
      subtree: true,
      characterData: true,
    },
  };

  let replacementCount = 0;
  let isProcessing = false;

  // Debounced replacement function
  const debounce = (func, wait) => {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  };

  // Optimized text replacement in text nodes only
  const replaceTextInNode = (node) => {
    if (!node || !window.TEXT_REPLACEMENTS) return;
    if (replacementCount > CONFIG.maxReplacements) return;

    if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
      let text = node.textContent;
      let modified = false;

      Object.entries(window.TEXT_REPLACEMENTS).forEach(([k, v]) => {
        if (k && v !== undefined && text.includes(k)) {
          try {
            const regex = new RegExp(
              k.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"),
              "gi"
            );
            const newText = text.replace(regex, v);
            if (newText !== text) {
              text = newText;
              modified = true;
              replacementCount++;
            }
          } catch (e) {
            console.warn("Replacement error:", k, e);
          }
        }
      });

      if (modified && replacementCount < CONFIG.maxReplacements) {
        node.textContent = text;
      }
    }
  };

  // Replace text in document (optimized)
  const replaceText = () => {
    if (isProcessing || !window.TEXT_REPLACEMENTS || !document.body) return;

    isProcessing = true;
    replacementCount = 0;

    try {
      // Replace in title
      if (document.title) {
        document.title = document.title.replace(
          /Taskade/gi,
          "MarketingTool.pro"
        );
        if (!document.title.includes("MarketingTool.pro")) {
          document.title = "MarketingTool.pro — AI Ad Optimization Platform";
        }
      }

      // Replace in text nodes only (more efficient)
      const walker = document.createTreeWalker(
        document.body,
        NodeFilter.SHOW_TEXT,
        {
          acceptNode: function (node) {
            // Skip script, style, and hidden elements
            const parent = node.parentElement;
            if (!parent) return NodeFilter.FILTER_REJECT;

            const tagName = parent.tagName?.toLowerCase();
            if (["script", "style", "noscript"].includes(tagName)) {
              return NodeFilter.FILTER_REJECT;
            }

            if (parent.closest("[data-no-replace]")) {
              return NodeFilter.FILTER_REJECT;
            }

            return NodeFilter.FILTER_ACCEPT;
          },
        },
        false
      );

      const textNodes = [];
      let node;
      while ((node = walker.nextNode())) {
        if (node.textContent.trim()) {
          textNodes.push(node);
        }
      }

      textNodes.forEach(replaceTextInNode);

      // Replace in attributes
      const elements = document.querySelectorAll(
        "[href], [src], [alt], [title], [placeholder], [aria-label]"
      );
      elements.forEach((el) => {
        ["href", "src", "alt", "title", "placeholder", "aria-label"].forEach(
          (attr) => {
            const value = el.getAttribute(attr);
            if (value && typeof value === "string") {
              Object.entries(window.TEXT_REPLACEMENTS).forEach(([k, v]) => {
                if (value.includes(k) && k && v !== undefined) {
                  try {
                    const regex = new RegExp(
                      k.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"),
                      "gi"
                    );
                    const newValue = value.replace(regex, v);
                    if (newValue !== value) {
                      el.setAttribute(attr, newValue);
                    }
                  } catch (e) {
                    // Silent fail for attributes
                  }
                }
              });
            }
          }
        );
      });
    } catch (error) {
      console.error("Branding replacement error:", error);
    } finally {
      isProcessing = false;
    }
  };

  // Debounced replacement
  const debouncedReplace = debounce(replaceText, CONFIG.debounceDelay);

  // Update document title immediately
  document.title = "MarketingTool.pro — AI Ad Optimization Platform";

  // Override favicon
  const updateFavicon = () => {
    let favicon = document.querySelector(
      "link[rel='icon'], link[rel='shortcut icon']"
    );
    if (!favicon) {
      favicon = document.createElement("link");
      favicon.rel = "icon";
      document.head.appendChild(favicon);
    }
    favicon.href = "/assets/logo.svg";
    favicon.type = "image/svg+xml";
  };
  updateFavicon();

  // Replace text on DOM load
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      replaceText();
      setupLinkRedirection();
      setupObserver();
    });
  } else {
    replaceText();
    setupLinkRedirection();
    setupObserver();
  }

  // Poll for dynamic content (debounced)
  const pollInterval = setInterval(() => {
    if (document.body && replacementCount < CONFIG.maxReplacements) {
      debouncedReplace();
    }
  }, CONFIG.pollInterval);

  // Stop polling after 5 minutes (assumes page loaded)
  setTimeout(() => clearInterval(pollInterval), 300000);

  // Setup link redirection
  function setupLinkRedirection() {
    const redirectLinks = () => {
      document.querySelectorAll("a").forEach((a) => {
        if (!a.dataset.replaced) {
          const href = a.href || a.getAttribute("href") || "";
          if (
            href.includes("billing") ||
            href.includes("upgrade") ||
            (href.includes("taskade.com") &&
              !href.includes("marketingtool.pro"))
          ) {
            a.href = "/pricing";
            a.dataset.replaced = "true";
            a.addEventListener(
              "click",
              (e) => {
                e.preventDefault();
                window.location.href = "/pricing";
              },
              true
            );
          }
        }
      });
    };

    redirectLinks();
    setInterval(redirectLinks, 2000);
  }

  // Mutation observer for dynamic content
  function setupObserver() {
    if (!document.body) return;

    const observer = new MutationObserver((mutations) => {
      if (isProcessing || replacementCount > CONFIG.maxReplacements) return;

      let shouldReplace = false;
      mutations.forEach((mutation) => {
        if (mutation.addedNodes.length > 0) {
          shouldReplace = true;
        }
        if (
          mutation.type === "characterData" &&
          mutation.target.textContent.trim()
        ) {
          shouldReplace = true;
        }
      });

      if (shouldReplace) {
        debouncedReplace();
        setupLinkRedirection();
      }
    });

    observer.observe(document.body, CONFIG.observerOptions);

    // Cleanup on page unload
    window.addEventListener("beforeunload", () => {
      observer.disconnect();
    });
  }

  // Handle iframe content (if accessible)
  const handleIframes = () => {
    document.querySelectorAll("iframe").forEach((iframe) => {
      try {
        iframe.addEventListener("load", function () {
          try {
            const iframeDoc =
              iframe.contentDocument || iframe.contentWindow.document;
            if (iframeDoc && iframeDoc.body) {
              // Try to replace in iframe (may fail due to CORS)
              replaceTextInNode(iframeDoc.body);
            }
          } catch (e) {
            // CORS restriction - expected
          }
        });
      } catch (e) {
        // Cannot access iframe
      }
    });
  };

  // Setup iframe handling
  setTimeout(handleIframes, 2000);

  // Error handling
  window.addEventListener(
    "error",
    (e) => {
      if (e.message && e.message.includes("branding")) {
        console.warn("Branding override error handled:", e.message);
        e.preventDefault();
      }
    },
    true
  );
})();

 * Production-grade: Handles 425 tools, edge cases, performance optimized
 */

(function () {
  "use strict";

  // Configuration
  const CONFIG = {
    pollInterval: 1500,
    maxReplacements: 10000, // Prevent infinite loops
    debounceDelay: 100,
    observerOptions: {
      childList: true,
      subtree: true,
      characterData: true,
    },
  };

  let replacementCount = 0;
  let isProcessing = false;

  // Debounced replacement function
  const debounce = (func, wait) => {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  };

  // Optimized text replacement in text nodes only
  const replaceTextInNode = (node) => {
    if (!node || !window.TEXT_REPLACEMENTS) return;
    if (replacementCount > CONFIG.maxReplacements) return;

    if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
      let text = node.textContent;
      let modified = false;

      Object.entries(window.TEXT_REPLACEMENTS).forEach(([k, v]) => {
        if (k && v !== undefined && text.includes(k)) {
          try {
            const regex = new RegExp(
              k.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"),
              "gi"
            );
            const newText = text.replace(regex, v);
            if (newText !== text) {
              text = newText;
              modified = true;
              replacementCount++;
            }
          } catch (e) {
            console.warn("Replacement error:", k, e);
          }
        }
      });

      if (modified && replacementCount < CONFIG.maxReplacements) {
        node.textContent = text;
      }
    }
  };

  // Replace text in document (optimized)
  const replaceText = () => {
    if (isProcessing || !window.TEXT_REPLACEMENTS || !document.body) return;

    isProcessing = true;
    replacementCount = 0;

    try {
      // Replace in title
      if (document.title) {
        document.title = document.title.replace(
          /Taskade/gi,
          "MarketingTool.pro"
        );
        if (!document.title.includes("MarketingTool.pro")) {
          document.title = "MarketingTool.pro — AI Ad Optimization Platform";
        }
      }

      // Replace in text nodes only (more efficient)
      const walker = document.createTreeWalker(
        document.body,
        NodeFilter.SHOW_TEXT,
        {
          acceptNode: function (node) {
            // Skip script, style, and hidden elements
            const parent = node.parentElement;
            if (!parent) return NodeFilter.FILTER_REJECT;

            const tagName = parent.tagName?.toLowerCase();
            if (["script", "style", "noscript"].includes(tagName)) {
              return NodeFilter.FILTER_REJECT;
            }

            if (parent.closest("[data-no-replace]")) {
              return NodeFilter.FILTER_REJECT;
            }

            return NodeFilter.FILTER_ACCEPT;
          },
        },
        false
      );

      const textNodes = [];
      let node;
      while ((node = walker.nextNode())) {
        if (node.textContent.trim()) {
          textNodes.push(node);
        }
      }

      textNodes.forEach(replaceTextInNode);

      // Replace in attributes
      const elements = document.querySelectorAll(
        "[href], [src], [alt], [title], [placeholder], [aria-label]"
      );
      elements.forEach((el) => {
        ["href", "src", "alt", "title", "placeholder", "aria-label"].forEach(
          (attr) => {
            const value = el.getAttribute(attr);
            if (value && typeof value === "string") {
              Object.entries(window.TEXT_REPLACEMENTS).forEach(([k, v]) => {
                if (value.includes(k) && k && v !== undefined) {
                  try {
                    const regex = new RegExp(
                      k.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"),
                      "gi"
                    );
                    const newValue = value.replace(regex, v);
                    if (newValue !== value) {
                      el.setAttribute(attr, newValue);
                    }
                  } catch (e) {
                    // Silent fail for attributes
                  }
                }
              });
            }
          }
        );
      });
    } catch (error) {
      console.error("Branding replacement error:", error);
    } finally {
      isProcessing = false;
    }
  };

  // Debounced replacement
  const debouncedReplace = debounce(replaceText, CONFIG.debounceDelay);

  // Update document title immediately
  document.title = "MarketingTool.pro — AI Ad Optimization Platform";

  // Override favicon
  const updateFavicon = () => {
    let favicon = document.querySelector(
      "link[rel='icon'], link[rel='shortcut icon']"
    );
    if (!favicon) {
      favicon = document.createElement("link");
      favicon.rel = "icon";
      document.head.appendChild(favicon);
    }
    favicon.href = "/assets/logo.svg";
    favicon.type = "image/svg+xml";
  };
  updateFavicon();

  // Replace text on DOM load
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      replaceText();
      setupLinkRedirection();
      setupObserver();
    });
  } else {
    replaceText();
    setupLinkRedirection();
    setupObserver();
  }

  // Poll for dynamic content (debounced)
  const pollInterval = setInterval(() => {
    if (document.body && replacementCount < CONFIG.maxReplacements) {
      debouncedReplace();
    }
  }, CONFIG.pollInterval);

  // Stop polling after 5 minutes (assumes page loaded)
  setTimeout(() => clearInterval(pollInterval), 300000);

  // Setup link redirection
  function setupLinkRedirection() {
    const redirectLinks = () => {
      document.querySelectorAll("a").forEach((a) => {
        if (!a.dataset.replaced) {
          const href = a.href || a.getAttribute("href") || "";
          if (
            href.includes("billing") ||
            href.includes("upgrade") ||
            (href.includes("taskade.com") &&
              !href.includes("marketingtool.pro"))
          ) {
            a.href = "/pricing";
            a.dataset.replaced = "true";
            a.addEventListener(
              "click",
              (e) => {
                e.preventDefault();
                window.location.href = "/pricing";
              },
              true
            );
          }
        }
      });
    };

    redirectLinks();
    setInterval(redirectLinks, 2000);
  }

  // Mutation observer for dynamic content
  function setupObserver() {
    if (!document.body) return;

    const observer = new MutationObserver((mutations) => {
      if (isProcessing || replacementCount > CONFIG.maxReplacements) return;

      let shouldReplace = false;
      mutations.forEach((mutation) => {
        if (mutation.addedNodes.length > 0) {
          shouldReplace = true;
        }
        if (
          mutation.type === "characterData" &&
          mutation.target.textContent.trim()
        ) {
          shouldReplace = true;
        }
      });

      if (shouldReplace) {
        debouncedReplace();
        setupLinkRedirection();
      }
    });

    observer.observe(document.body, CONFIG.observerOptions);

    // Cleanup on page unload
    window.addEventListener("beforeunload", () => {
      observer.disconnect();
    });
  }

  // Handle iframe content (if accessible)
  const handleIframes = () => {
    document.querySelectorAll("iframe").forEach((iframe) => {
      try {
        iframe.addEventListener("load", function () {
          try {
            const iframeDoc =
              iframe.contentDocument || iframe.contentWindow.document;
            if (iframeDoc && iframeDoc.body) {
              // Try to replace in iframe (may fail due to CORS)
              replaceTextInNode(iframeDoc.body);
            }
          } catch (e) {
            // CORS restriction - expected
          }
        });
      } catch (e) {
        // Cannot access iframe
      }
    });
  };

  // Setup iframe handling
  setTimeout(handleIframes, 2000);

  // Error handling
  window.addEventListener(
    "error",
    (e) => {
      if (e.message && e.message.includes("branding")) {
        console.warn("Branding override error handled:", e.message);
        e.preventDefault();
      }
    },
    true
  );
})();
