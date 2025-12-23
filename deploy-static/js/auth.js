/**
 * MarketingTool.pro - Authentication System
 * V1: Frontend-only auth with localStorage
 * Pricing: 7 days free, $49 one-time, $99 app setup, $499 lifetime
 */

class AuthSystem {
  constructor() {
    this.storageKey = 'marketingtool_auth';
    this.trialDays = 7;
  }

  // Check if user is authenticated
  isAuthenticated() {
    const authData = this.getAuthData();
    if (!authData) return false;

    // Check if trial expired
    if (authData.plan === 'trial' && authData.trialEnd) {
      const trialEnd = new Date(authData.trialEnd);
      if (new Date() > trialEnd) {
        return false;
      }
    }

    return true;
  }

  // Get auth data from storage
  getAuthData() {
    try {
      const data = localStorage.getItem(this.storageKey);
      return data ? JSON.parse(data) : null;
    } catch (e) {
      return null;
    }
  }

  // Save auth data
  saveAuthData(data) {
    try {
      localStorage.setItem(this.storageKey, JSON.stringify(data));
      return true;
    } catch (e) {
      return false;
    }
  }

  // Create account (signup)
  signup(userData) {
    const trialEnd = new Date();
    trialEnd.setDate(trialEnd.getDate() + this.trialDays);

    const authData = {
      email: userData.email,
      name: userData.name,
      plan: 'trial',
      trialEnd: trialEnd.toISOString(),
      createdAt: new Date().toISOString(),
      subscription: null
    };

    this.saveAuthData(authData);
    return authData;
  }

  // Login
  login(email, password) {
    // V1: Mock login - check if user exists
    const existing = this.getAuthData();
    if (existing && existing.email === email) {
      return existing;
    }

    // Create trial account if doesn't exist
    const trialEnd = new Date();
    trialEnd.setDate(trialEnd.getDate() + this.trialDays);

    const authData = {
      email: email,
      name: email.split('@')[0],
      plan: 'trial',
      trialEnd: trialEnd.toISOString(),
      createdAt: new Date().toISOString(),
      subscription: null
    };

    this.saveAuthData(authData);
    return authData;
  }

  // Upgrade to paid plan
  upgrade(planType) {
    const authData = this.getAuthData();
    if (!authData) return false;

    authData.plan = planType;
    authData.subscription = {
      type: planType,
      purchasedAt: new Date().toISOString()
    };

    // Set plan-specific data
    if (planType === 'lifetime') {
      authData.subscription.expiresAt = null; // Never expires
    } else if (planType === 'one-time') {
      // $49 one-time - expires after 1 year
      const expiresAt = new Date();
      expiresAt.setFullYear(expiresAt.getFullYear() + 1);
      authData.subscription.expiresAt = expiresAt.toISOString();
    } else if (planType === 'app-setup') {
      // $99 app setup - expires after 1 year
      const expiresAt = new Date();
      expiresAt.setFullYear(expiresAt.getFullYear() + 1);
      authData.subscription.expiresAt = expiresAt.toISOString();
    }

    return this.saveAuthData(authData);
  }

  // Check subscription status
  getSubscriptionStatus() {
    const authData = this.getAuthData();
    if (!authData) {
      return { valid: false, reason: 'not_logged_in' };
    }

    // Check trial
    if (authData.plan === 'trial') {
      if (authData.trialEnd) {
        const trialEnd = new Date(authData.trialEnd);
        if (new Date() > trialEnd) {
          return { valid: false, reason: 'trial_expired' };
        }
        const daysRemaining = Math.ceil((trialEnd - new Date()) / (1000 * 60 * 60 * 24));
        return { valid: true, plan: 'trial', daysRemaining };
      }
    }

    // Check paid subscription
    if (authData.subscription) {
      if (authData.subscription.expiresAt) {
        const expiresAt = new Date(authData.subscription.expiresAt);
        if (new Date() > expiresAt) {
          return { valid: false, reason: 'subscription_expired' };
        }
      }
      return { valid: true, plan: authData.plan };
    }

    return { valid: false, reason: 'no_subscription' };
  }

  // Logout
  logout() {
    localStorage.removeItem(this.storageKey);
  }
}

// Global auth instance
window.authSystem = new AuthSystem();
