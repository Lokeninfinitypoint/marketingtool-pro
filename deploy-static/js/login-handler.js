/**
 * Login Handler - Processes login and redirects to dashboard
 * Like madgicx.com - forces login before dashboard access
 */

function handleLogin(form) {
  const email = form.email.value;
  const password = form.password.value;
  const rememberMe = form.querySelector('input[type="checkbox"]').checked;
  
  if (!email || !password) {
    alert('Please enter both email and password');
    return;
  }
  
  // Show loading
  const submitBtn = form.querySelector('button[type="submit"]');
  const originalText = submitBtn.textContent;
  submitBtn.textContent = 'Signing in...';
  submitBtn.disabled = true;
  
  // Wait for auth system
  if (typeof window.authSystem === 'undefined') {
    const authScript = document.createElement('script');
    authScript.src = '/js/auth.js';
    document.head.appendChild(authScript);
    authScript.onload = () => processLogin(email, password, rememberMe, submitBtn, originalText);
  } else {
    processLogin(email, password, rememberMe, submitBtn, originalText);
  }
}

function processLogin(email, password, rememberMe, submitBtn, originalText) {
  try {
    // Login using auth system
    const authData = window.authSystem.login(email, password);
    
    if (authData) {
      // Get redirect URL from query params
      const urlParams = new URLSearchParams(window.location.search);
      const redirectUrl = urlParams.get('redirect') || 'https://dashboard.marketingtool.pro/';
      
      // Show success message
      submitBtn.textContent = '✓ Success! Redirecting...';
      submitBtn.style.background = '#10B981';
      
      // Redirect to dashboard after short delay
      setTimeout(() => {
        window.location.href = redirectUrl;
      }, 500);
    } else {
      throw new Error('Login failed');
    }
  } catch (error) {
    console.error('Login error:', error);
    submitBtn.textContent = originalText;
    submitBtn.disabled = false;
    alert('Login failed. Please try again.');
  }
}

// Auto-fill if already logged in (but still show login page)
window.addEventListener('DOMContentLoaded', function() {
  if (typeof window.authSystem !== 'undefined') {
    const authData = window.authSystem.getAuthData();
    if (authData && window.authSystem.isAuthenticated()) {
      // User is already logged in - redirect to dashboard
      const urlParams = new URLSearchParams(window.location.search);
      const redirectUrl = urlParams.get('redirect') || 'https://dashboard.marketingtool.pro/';
      window.location.href = redirectUrl;
    }
  }
});
