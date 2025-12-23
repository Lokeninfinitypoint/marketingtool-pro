/**
 * MarketingTool.pro App Access Gate
 * Production-grade: Security, error handling, performance optimized
 * Blocks app access without authentication, handles 425 tools
 */

(function () {
  "use strict";

  // Configuration
  const CONFIG = {
    checkInterval: 30000, // 30 seconds
    sessionTimeout: 3600000, // 1 hour
    maxRetries: 3,
    retryDelay: 2000,
  };

  // Check if we're on app page
  const isAppPage =
    window.location.hostname === "app.marketingtool.pro" ||
    window.location.pathname.includes("/app");

  if (!isAppPage) {
    return; // Not an app page, skip
  }

  // Storage helper with error handling
  const storage = {
    get: (key) => {
      try {
        return (
          localStorage.getItem(key) ||
          sessionStorage.getItem(key) ||
          getCookie(key)
        );
      } catch (e) {
        console.warn("Storage read error:", e);
        return null;
      }
    },
    set: (key, value) => {
      try {
        localStorage.setItem(key, value);
        return true;
      } catch (e) {
        try {
          sessionStorage.setItem(key, value);
          return true;
        } catch (e2) {
          console.warn("Storage write error:", e2);
          return false;
        }
      }
    },
    remove: (key) => {
      try {
        localStorage.removeItem(key);
        sessionStorage.removeItem(key);
        document.cookie = `${key}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
      } catch (e) {
        console.warn("Storage remove error:", e);
      }
    },
  };

  // Cookie helper
  function getCookie(name) {
    try {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(";").shift();
      return null;
    } catch (e) {
      return null;
    }
  }

  // Auth check with error handling
  function checkAuth() {
    try {
      const token = storage.get("auth_token");

      if (!token) {
        return { valid: false, reason: "no_token" };
      }

      // Check subscription status
      const subscriptionData = storage.get("subscription_data");
      if (subscriptionData) {
        try {
          const sub = JSON.parse(subscriptionData);
          return checkSubscriptionStatus(sub);
        } catch (e) {
          console.warn("Subscription data parse error:", e);
          return { valid: false, reason: "invalid_subscription" };
        }
      }

      // If no subscription data, allow access (will be checked by backend later)
      return { valid: true };
    } catch (error) {
      console.error("Auth check error:", error);
      return { valid: false, reason: "error" };
    }
  }

  // Subscription status check
  function checkSubscriptionStatus(subscription) {
    if (!subscription || !subscription.valid) {
      const reason =
        subscription?.reason === "trial_expired"
          ? "trial_expired"
          : "subscription_required";
      return { valid: false, reason };
    }

    // Check trial end date if on trial
    if (subscription.plan_type === "trial" && subscription.trial_end_date) {
      try {
        const trialEnd = new Date(subscription.trial_end_date);
        const now = new Date();

        if (isNaN(trialEnd.getTime())) {
          return { valid: false, reason: "invalid_date" };
        }

        if (now > trialEnd) {
          return { valid: false, reason: "trial_expired" };
        }

        // Show trial days remaining
        const daysRemaining = Math.ceil(
          (trialEnd - now) / (1000 * 60 * 60 * 24)
        );
        if (daysRemaining <= 2) {
          showTrialWarning(daysRemaining);
        }

        return { valid: true, daysRemaining };
      } catch (e) {
        console.warn("Date parsing error:", e);
        return { valid: true }; // Allow access on error
      }
    }

    return { valid: true };
  }

  // Redirect helpers with error handling
  function redirectToLogin() {
    try {
      const returnUrl = encodeURIComponent(window.location.href);
      window.location.href = `https://marketingtool.pro/login/?return=${returnUrl}`;
    } catch (e) {
      window.location.href = "https://marketingtool.pro/login/";
    }
  }

  function redirectToPricing(message) {
    try {
      if (message) {
        sessionStorage.setItem("pricing_message", message);
      }
      window.location.href = "https://marketingtool.pro/pricing/";
    } catch (e) {
      window.location.href = "https://marketingtool.pro/pricing/";
    }
  }

  // Show trial warning banner
  function showTrialWarning(daysRemaining) {
    // Remove existing warning
    const existing = document.getElementById("trial-warning-banner");
    if (existing) return;

    const warning = document.createElement("div");
    warning.id = "trial-warning-banner";
    warning.setAttribute("role", "alert");
    warning.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
      padding: 12px 20px;
      text-align: center;
      z-index: 10001;
      font-weight: 600;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      font-size: 14px;
    `;
    warning.innerHTML = `
      <span>⚠️ Your free trial expires in ${daysRemaining} day${
      daysRemaining !== 1 ? "s" : ""
    }. 
      <a href="/pricing/" style="color: white; text-decoration: underline; font-weight: 700;">Upgrade Now</a>
      </span>
      <button onclick="this.parentElement.remove()" 
              aria-label="Close warning"
              style="float: right; background: rgba(255,255,255,0.2); border: none; color: white; padding: 4px 12px; border-radius: 4px; cursor: pointer; margin-left: 20px;">✕</button>
    `;
    document.body.insertBefore(warning, document.body.firstChild);

    // Adjust body padding
    document.body.style.paddingTop = "48px";
  }

  // Block iframe load with modal
  function blockIframeLoad() {
    try {
      const iframes = document.querySelectorAll('iframe[src*="taskade"]');
      iframes.forEach((iframe) => {
        iframe.style.display = "none";
        iframe.src = "about:blank";
      });

      showUpgradeModal();
    } catch (e) {
      console.error("Block iframe error:", e);
      redirectToPricing();
    }
  }

  // Show upgrade modal (accessible)
  function showUpgradeModal() {
    // Remove existing modal
    const existing = document.getElementById("upgrade-modal");
    if (existing) return;

    const modal = document.createElement("div");
    modal.id = "upgrade-modal";
    modal.setAttribute("role", "dialog");
    modal.setAttribute("aria-modal", "true");
    modal.setAttribute("aria-labelledby", "modal-title");
    modal.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10000;
      color: white;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    `;

    const message =
      sessionStorage.getItem("pricing_message") ||
      "Please subscribe to access MarketingTool.pro";

    modal.innerHTML = `
      <div style="background: #1e293b; border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 16px; padding: 40px; max-width: 500px; text-align: center;">
        <h2 id="modal-title" style="font-size: 24px; margin-bottom: 16px; color: #ffffff;">Subscription Required</h2>
        <p style="margin-bottom: 24px; color: rgba(255, 255, 255, 0.8); line-height: 1.6;">
          ${message}
        </p>
        <div style="display: flex; gap: 12px; justify-content: center;">
          <a href="/pricing/" style="
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
          ">View Pricing</a>
          <a href="/login/" style="
            background: transparent;
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: inline-block;
          ">Login</a>
        </div>
      </div>
    `;
    document.body.appendChild(modal);

    // Clear pricing message
    sessionStorage.removeItem("pricing_message");

    // Close on escape
    document.addEventListener("keydown", function escHandler(e) {
      if (e.key === "Escape") {
        modal.remove();
        document.removeEventListener("keydown", escHandler);
      }
    });
  }

  // Main gate check with retry logic
  function enforceAccessGate(retries = 0) {
    try {
      const authResult = checkAuth();

      if (!authResult.valid) {
        if (
          authResult.reason === "trial_expired" ||
          authResult.reason === "subscription_required"
        ) {
          redirectToPricing(
            authResult.reason === "trial_expired"
              ? "Your 7-day free trial has expired. Please upgrade to continue."
              : "Please subscribe to access the app."
          );
        } else if (authResult.reason === "no_token") {
          redirectToLogin();
        } else {
          // Retry on error
          if (retries < CONFIG.maxRetries) {
            setTimeout(() => enforceAccessGate(retries + 1), CONFIG.retryDelay);
          } else {
            blockIframeLoad();
          }
        }
        return false;
      }

      return true;
    } catch (error) {
      console.error("Access gate error:", error);
      if (retries < CONFIG.maxRetries) {
        setTimeout(() => enforceAccessGate(retries + 1), CONFIG.retryDelay);
        return false;
      }
      return true; // Allow on persistent errors
    }
  }

  // Run immediately
  if (!enforceAccessGate()) {
    return;
  }

  // Periodic check
  const checkInterval = setInterval(() => {
    if (!enforceAccessGate()) {
      clearInterval(checkInterval);
    }
  }, CONFIG.checkInterval);

  // Cleanup on page unload
  window.addEventListener("beforeunload", () => {
    clearInterval(checkInterval);
  });

  // Also check on iframe load
  document.addEventListener("DOMContentLoaded", function () {
    const iframes = document.querySelectorAll('iframe[src*="taskade"]');
    iframes.forEach((iframe) => {
      iframe.addEventListener("load", function () {
        if (!enforceAccessGate()) {
          iframe.style.display = "none";
        }
      });
    });
  });
})();

 * Production-grade: Security, error handling, performance optimized
 * Blocks app access without authentication, handles 425 tools
 */

(function () {
  "use strict";

  // Configuration
  const CONFIG = {
    checkInterval: 30000, // 30 seconds
    sessionTimeout: 3600000, // 1 hour
    maxRetries: 3,
    retryDelay: 2000,
  };

  // Check if we're on app page
  const isAppPage =
    window.location.hostname === "app.marketingtool.pro" ||
    window.location.pathname.includes("/app");

  if (!isAppPage) {
    return; // Not an app page, skip
  }

  // Storage helper with error handling
  const storage = {
    get: (key) => {
      try {
        return (
          localStorage.getItem(key) ||
          sessionStorage.getItem(key) ||
          getCookie(key)
        );
      } catch (e) {
        console.warn("Storage read error:", e);
        return null;
      }
    },
    set: (key, value) => {
      try {
        localStorage.setItem(key, value);
        return true;
      } catch (e) {
        try {
          sessionStorage.setItem(key, value);
          return true;
        } catch (e2) {
          console.warn("Storage write error:", e2);
          return false;
        }
      }
    },
    remove: (key) => {
      try {
        localStorage.removeItem(key);
        sessionStorage.removeItem(key);
        document.cookie = `${key}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
      } catch (e) {
        console.warn("Storage remove error:", e);
      }
    },
  };

  // Cookie helper
  function getCookie(name) {
    try {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(";").shift();
      return null;
    } catch (e) {
      return null;
    }
  }

  // Auth check with error handling
  function checkAuth() {
    try {
      const token = storage.get("auth_token");

      if (!token) {
        return { valid: false, reason: "no_token" };
      }

      // Check subscription status
      const subscriptionData = storage.get("subscription_data");
      if (subscriptionData) {
        try {
          const sub = JSON.parse(subscriptionData);
          return checkSubscriptionStatus(sub);
        } catch (e) {
          console.warn("Subscription data parse error:", e);
          return { valid: false, reason: "invalid_subscription" };
        }
      }

      // If no subscription data, allow access (will be checked by backend later)
      return { valid: true };
    } catch (error) {
      console.error("Auth check error:", error);
      return { valid: false, reason: "error" };
    }
  }

  // Subscription status check
  function checkSubscriptionStatus(subscription) {
    if (!subscription || !subscription.valid) {
      const reason =
        subscription?.reason === "trial_expired"
          ? "trial_expired"
          : "subscription_required";
      return { valid: false, reason };
    }

    // Check trial end date if on trial
    if (subscription.plan_type === "trial" && subscription.trial_end_date) {
      try {
        const trialEnd = new Date(subscription.trial_end_date);
        const now = new Date();

        if (isNaN(trialEnd.getTime())) {
          return { valid: false, reason: "invalid_date" };
        }

        if (now > trialEnd) {
          return { valid: false, reason: "trial_expired" };
        }

        // Show trial days remaining
        const daysRemaining = Math.ceil(
          (trialEnd - now) / (1000 * 60 * 60 * 24)
        );
        if (daysRemaining <= 2) {
          showTrialWarning(daysRemaining);
        }

        return { valid: true, daysRemaining };
      } catch (e) {
        console.warn("Date parsing error:", e);
        return { valid: true }; // Allow access on error
      }
    }

    return { valid: true };
  }

  // Redirect helpers with error handling
  function redirectToLogin() {
    try {
      const returnUrl = encodeURIComponent(window.location.href);
      window.location.href = `https://marketingtool.pro/login/?return=${returnUrl}`;
    } catch (e) {
      window.location.href = "https://marketingtool.pro/login/";
    }
  }

  function redirectToPricing(message) {
    try {
      if (message) {
        sessionStorage.setItem("pricing_message", message);
      }
      window.location.href = "https://marketingtool.pro/pricing/";
    } catch (e) {
      window.location.href = "https://marketingtool.pro/pricing/";
    }
  }

  // Show trial warning banner
  function showTrialWarning(daysRemaining) {
    // Remove existing warning
    const existing = document.getElementById("trial-warning-banner");
    if (existing) return;

    const warning = document.createElement("div");
    warning.id = "trial-warning-banner";
    warning.setAttribute("role", "alert");
    warning.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
      padding: 12px 20px;
      text-align: center;
      z-index: 10001;
      font-weight: 600;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      font-size: 14px;
    `;
    warning.innerHTML = `
      <span>⚠️ Your free trial expires in ${daysRemaining} day${
      daysRemaining !== 1 ? "s" : ""
    }. 
      <a href="/pricing/" style="color: white; text-decoration: underline; font-weight: 700;">Upgrade Now</a>
      </span>
      <button onclick="this.parentElement.remove()" 
              aria-label="Close warning"
              style="float: right; background: rgba(255,255,255,0.2); border: none; color: white; padding: 4px 12px; border-radius: 4px; cursor: pointer; margin-left: 20px;">✕</button>
    `;
    document.body.insertBefore(warning, document.body.firstChild);

    // Adjust body padding
    document.body.style.paddingTop = "48px";
  }

  // Block iframe load with modal
  function blockIframeLoad() {
    try {
      const iframes = document.querySelectorAll('iframe[src*="taskade"]');
      iframes.forEach((iframe) => {
        iframe.style.display = "none";
        iframe.src = "about:blank";
      });

      showUpgradeModal();
    } catch (e) {
      console.error("Block iframe error:", e);
      redirectToPricing();
    }
  }

  // Show upgrade modal (accessible)
  function showUpgradeModal() {
    // Remove existing modal
    const existing = document.getElementById("upgrade-modal");
    if (existing) return;

    const modal = document.createElement("div");
    modal.id = "upgrade-modal";
    modal.setAttribute("role", "dialog");
    modal.setAttribute("aria-modal", "true");
    modal.setAttribute("aria-labelledby", "modal-title");
    modal.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10000;
      color: white;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    `;

    const message =
      sessionStorage.getItem("pricing_message") ||
      "Please subscribe to access MarketingTool.pro";

    modal.innerHTML = `
      <div style="background: #1e293b; border: 1px solid rgba(99, 102, 241, 0.3); border-radius: 16px; padding: 40px; max-width: 500px; text-align: center;">
        <h2 id="modal-title" style="font-size: 24px; margin-bottom: 16px; color: #ffffff;">Subscription Required</h2>
        <p style="margin-bottom: 24px; color: rgba(255, 255, 255, 0.8); line-height: 1.6;">
          ${message}
        </p>
        <div style="display: flex; gap: 12px; justify-content: center;">
          <a href="/pricing/" style="
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
          ">View Pricing</a>
          <a href="/login/" style="
            background: transparent;
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: inline-block;
          ">Login</a>
        </div>
      </div>
    `;
    document.body.appendChild(modal);

    // Clear pricing message
    sessionStorage.removeItem("pricing_message");

    // Close on escape
    document.addEventListener("keydown", function escHandler(e) {
      if (e.key === "Escape") {
        modal.remove();
        document.removeEventListener("keydown", escHandler);
      }
    });
  }

  // Main gate check with retry logic
  function enforceAccessGate(retries = 0) {
    try {
      const authResult = checkAuth();

      if (!authResult.valid) {
        if (
          authResult.reason === "trial_expired" ||
          authResult.reason === "subscription_required"
        ) {
          redirectToPricing(
            authResult.reason === "trial_expired"
              ? "Your 7-day free trial has expired. Please upgrade to continue."
              : "Please subscribe to access the app."
          );
        } else if (authResult.reason === "no_token") {
          redirectToLogin();
        } else {
          // Retry on error
          if (retries < CONFIG.maxRetries) {
            setTimeout(() => enforceAccessGate(retries + 1), CONFIG.retryDelay);
          } else {
            blockIframeLoad();
          }
        }
        return false;
      }

      return true;
    } catch (error) {
      console.error("Access gate error:", error);
      if (retries < CONFIG.maxRetries) {
        setTimeout(() => enforceAccessGate(retries + 1), CONFIG.retryDelay);
        return false;
      }
      return true; // Allow on persistent errors
    }
  }

  // Run immediately
  if (!enforceAccessGate()) {
    return;
  }

  // Periodic check
  const checkInterval = setInterval(() => {
    if (!enforceAccessGate()) {
      clearInterval(checkInterval);
    }
  }, CONFIG.checkInterval);

  // Cleanup on page unload
  window.addEventListener("beforeunload", () => {
    clearInterval(checkInterval);
  });

  // Also check on iframe load
  document.addEventListener("DOMContentLoaded", function () {
    const iframes = document.querySelectorAll('iframe[src*="taskade"]');
    iframes.forEach((iframe) => {
      iframe.addEventListener("load", function () {
        if (!enforceAccessGate()) {
          iframe.style.display = "none";
        }
      });
    });
  });
})();
