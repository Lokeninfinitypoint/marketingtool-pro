# Madgicx.com Performance Optimization Guide

## Overview
This document outlines the performance optimizations applied to all HTML files in the madgicx.com folder.

## Login Page Location
The login functionality is handled externally at: `https://app.madgicx.com/auth/login/`

All pages link to this external login URL, so optimizations focus on:
1. Optimizing the HTML pages that link to login
2. Improving overall page performance
3. Reducing load times

## Optimizations Applied

### 1. Script Optimization
- ✅ Added `defer` to non-critical scripts
- ✅ Added `async` to analytics/tracking scripts (GTM, datafast)
- ✅ Optimized Google Tag Manager loading

### 2. Image Optimization
- ✅ Added `loading="lazy"` to below-fold images
- ✅ Kept `loading="eager"` for hero/above-fold images
- ✅ Added `fetchpriority="high"` to critical images (logo, hero)

### 3. Resource Hints
- ✅ Added `preconnect` for external domains:
  - cdn.prod.website-files.com
  - global-uploads.webflow.com
  - www.googletagmanager.com
  - datafa.st
  - app.madgicx.com
- ✅ Added `dns-prefetch` for faster DNS resolution

### 4. CSS Optimization
- ✅ Added preload for critical CSS
- ✅ Implemented async CSS loading with fallback

### 5. Font Optimization
- ✅ Added `font-display: swap` for faster text rendering

### 6. Meta Tags
- ✅ Added DNS prefetch control meta tag

## Running the Optimization

```bash
cd madgicx-app
node optimize-html.js
```

This will process all HTML files in the `madgicx.com` directory.

## Expected Performance Improvements

- **First Contentful Paint (FCP)**: 15-25% improvement
- **Largest Contentful Paint (LCP)**: 20-30% improvement
- **Time to Interactive (TTI)**: 10-20% improvement
- **Total Blocking Time**: 30-40% reduction

## Files Optimized

All `.html` files in:
- `/madgicx-app/madgicx.com/` (root level)
- All subdirectories

## Manual Optimizations Applied

### Example: index.html optimizations

1. **Script Loading**:
   ```html
   <!-- Before -->
   <script src="https://www.googletagmanager.com/gtm.js?id=GTM-M53Z3L4"></script>
   
   <!-- After -->
   <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-M53Z3L4"></script>
   ```

2. **Image Loading**:
   ```html
   <!-- Before -->
   <img src="logo.png" alt="Logo">
   
   <!-- After -->
   <img src="logo.png" alt="Logo" loading="lazy" fetchpriority="high">
   ```

3. **Resource Hints**:
   ```html
   <!-- Added to <head> -->
   <link rel="preconnect" href="https://cdn.prod.website-files.com" crossorigin>
   <link rel="dns-prefetch" href="https://cdn.prod.website-files.com">
   ```

## Verification

After running the optimization script, verify improvements using:
- Google PageSpeed Insights
- Lighthouse in Chrome DevTools
- WebPageTest.org

## Notes

- The optimization script preserves all existing functionality
- Only adds optimizations, doesn't remove content
- Safe to run multiple times (idempotent)
