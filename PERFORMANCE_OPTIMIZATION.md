# Performance Optimization Guide

This document outlines all the performance optimizations implemented across the codebase.

## Table of Contents

1. [Overview](#overview)
2. [Next.js Applications](#nextjs-applications)
3. [Astro Application](#astro-application)
4. [Asset Optimization](#asset-optimization)
5. [Code Splitting](#code-splitting)
6. [Font Optimization](#font-optimization)
7. [Bundle Analysis](#bundle-analysis)
8. [Performance Monitoring](#performance-monitoring)
9. [Best Practices](#best-practices)

---

## Overview

### Key Metrics Targeted

- **First Contentful Paint (FCP)**: < 1.8s
- **Largest Contentful Paint (LCP)**: < 2.5s
- **Time to Interactive (TTI)**: < 3.8s
- **Cumulative Layout Shift (CLS)**: < 0.1
- **First Input Delay (FID)**: < 100ms
- **Bundle Size**: Reduced by ~30-40%

---

## Next.js Applications

### aitool-app & dashboard-app

#### Build Configuration

**Location**: `next.config.js`

**Optimizations Applied**:

1. **Compression**: Enabled automatic gzip/brotli compression
   ```javascript
   compress: true
   ```

2. **Image Optimization**:
   - AVIF and WebP formats enabled
   - Optimized device sizes and image sizes
   - 1-year cache TTL for static images
   
3. **Code Splitting**:
   - Automatic vendor chunking
   - Separate chunks for lucide-react icons
   - Common code extraction
   
4. **Tree Shaking**:
   - `usedExports: true` - Removes unused exports
   - `sideEffects: false` - Enables aggressive tree shaking
   
5. **Console Removal**:
   - Removes console.log in production (keeps errors/warnings)

#### Font Optimization

**Location**: `src/app/layout.tsx`

**Strategy**:
- Using Next.js `next/font/google` for automatic optimization
- Font display strategy: `swap` (prevents invisible text)
- Preconnect to Google Fonts
- Variable fonts with CSS variables
- System font fallbacks

```typescript
const inter = Inter({ 
  subsets: ['latin'],
  display: 'swap',
  variable: '--font-inter',
  preload: true,
  fallback: ['system-ui', 'arial'],
});
```

#### Icon Optimization

**Location**: `src/components/icons.ts`

**Strategy**:
- Centralized icon imports in barrel file
- Tree-shaking enabled for lucide-react
- Only imports icons actually used in the app
- Separate chunk for icons in build

**Usage**:
```typescript
// Before (imports entire library):
import { Icon } from 'lucide-react';

// After (tree-shakeable):
import { Icon } from '@/components/icons';
```

#### Headers for Caching

Static assets cached for 1 year:
- SVG, JPG, PNG, WebP, AVIF
- CSS and JS files
- `_next/static` files

---

## Astro Application

### Main Site

**Location**: `astro.config.mjs`

#### Optimizations Applied:

1. **Minification**:
   - Using Terser for optimal compression
   - Removes console logs and debugger statements
   
2. **Code Splitting**:
   - Manual chunks for React, icons, and vendor code
   - Reduces initial bundle size
   
3. **CSS Optimization**:
   - Automatic inlining of critical CSS
   - External stylesheets for non-critical CSS
   
4. **HTML Compression**:
   - Enabled with `compressHTML: true`

5. **Dependency Optimization**:
   - Pre-bundled React and React-DOM
   - Faster cold starts

---

## Asset Optimization

### Images

**Guidelines**:

1. **Next.js Apps**: Use `next/image` component
   ```tsx
   import Image from 'next/image';
   
   <Image 
     src="/image.jpg"
     alt="Description"
     width={800}
     height={600}
     priority // For above-the-fold images
   />
   ```

2. **Astro**: Use `<picture>` with multiple formats
   ```html
   <picture>
     <source srcset="image.avif" type="image/avif" />
     <source srcset="image.webp" type="image/webp" />
     <img src="image.jpg" alt="Description" />
   </picture>
   ```

### CSS

**Best Practices**:

1. Use CSS variables for theming (reduces duplication)
2. Leverage Tailwind's purge feature
3. Critical CSS inlined in `<head>`
4. Non-critical CSS loaded asynchronously

---

## Code Splitting

### Automatic Splitting

Next.js automatically splits code at:
- Route boundaries (each page is a separate chunk)
- Dynamic imports

### Manual Splitting

For large components, use dynamic imports:

```typescript
import dynamic from 'next/dynamic';

const HeavyComponent = dynamic(() => import('./HeavyComponent'), {
  loading: () => <p>Loading...</p>,
  ssr: false // If component doesn't need SSR
});
```

### Current Split Points

1. **vendor.js**: Core dependencies (React, etc.)
2. **icons.js**: Lucide React icons
3. **common.js**: Shared code across routes
4. **[page].js**: Individual page code

---

## Font Optimization

### Strategy

1. **Self-hosted fonts** via Next.js font optimization
2. **Variable fonts** reduce number of font files
3. **Font subsetting** - Only Latin characters loaded
4. **Preconnect** to font sources
5. **Font display: swap** prevents FOIT (Flash of Invisible Text)

### Implementation

```typescript
// next/font automatically:
// - Downloads fonts at build time
// - Self-hosts fonts
// - Removes external network requests
// - Adds optimal font-face CSS
import { Inter } from 'next/font/google';
```

---

## Bundle Analysis

### Running Analysis

#### Next.js Apps:

```bash
# AITool App
cd aitool-app
npm run analyze

# Dashboard App  
cd dashboard-app
npm run analyze
```

This generates:
- `.next/analyze/client.html` - Client bundle visualization
- `.next/analyze/server.html` - Server bundle visualization

#### Astro App:

```bash
npm run analyze
```

### Reading the Results

Look for:
- **Large Dependencies**: Consider alternatives or lazy loading
- **Duplicate Code**: May need better code splitting
- **Unused Code**: Remove or enable tree shaking

---

## Performance Monitoring

### Web Vitals Tracking

**Location**: `src/lib/performance.ts`

**Usage**:

```typescript
import { reportWebVitals } from '@/lib/performance';

export function onCLS(metric) {
  reportWebVitals(metric);
}

export function onFID(metric) {
  reportWebVitals(metric);
}

export function onLCP(metric) {
  reportWebVitals(metric);
}
```

### Utility Functions

```typescript
// Debounce expensive operations
import { debounce } from '@/lib/performance';

const handleSearch = debounce((query) => {
  // Expensive search operation
}, 300);

// Throttle scroll handlers
import { throttle } from '@/lib/performance';

const handleScroll = throttle(() => {
  // Scroll handler
}, 100);
```

### Middleware

**Location**: `src/app/middleware.ts`

Adds performance and security headers:
- DNS prefetch control
- Frame options
- Content type options
- Referrer policy

---

## Best Practices

### 1. Images

✅ **DO**:
- Use `next/image` for automatic optimization
- Specify width and height
- Use `priority` for above-the-fold images
- Use WebP/AVIF formats

❌ **DON'T**:
- Use `<img>` tags directly
- Load large images without optimization
- Skip alt text

### 2. JavaScript

✅ **DO**:
- Use dynamic imports for heavy components
- Implement code splitting at route level
- Use tree-shakeable imports
- Debounce/throttle expensive operations

❌ **DON'T**:
- Import entire libraries
- Load unnecessary polyfills
- Render heavy components unconditionally

### 3. CSS

✅ **DO**:
- Use Tailwind's purge feature
- Inline critical CSS
- Use CSS-in-JS sparingly
- Leverage CSS variables

❌ **DON'T**:
- Include unused CSS frameworks
- Use inline styles excessively
- Load non-critical CSS synchronously

### 4. Fonts

✅ **DO**:
- Use `next/font` for optimization
- Self-host fonts when possible
- Use font-display: swap
- Subset fonts to required characters

❌ **DON'T**:
- Load fonts from external CDNs
- Include multiple font weights unnecessarily
- Use system fonts if custom fonts aren't critical

### 5. Third-Party Scripts

✅ **DO**:
- Load scripts asynchronously
- Use `next/script` with strategy
- Defer non-critical scripts
- Lazy load analytics

❌ **DON'T**:
- Block rendering with scripts
- Load scripts in `<head>` without async/defer
- Include unused tracking scripts

---

## Monitoring Checklist

Run these checks regularly:

### Development

- [ ] Check bundle size after adding dependencies
- [ ] Test with slow 3G throttling
- [ ] Verify images are optimized
- [ ] Check for console errors
- [ ] Test on low-end devices

### Before Deployment

- [ ] Run `npm run analyze` for bundle analysis
- [ ] Run Lighthouse audit (aim for 90+ score)
- [ ] Check WebPageTest results
- [ ] Verify all images use next/image
- [ ] Test on production build locally
- [ ] Verify caching headers work
- [ ] Check font loading strategy

### After Deployment

- [ ] Monitor Core Web Vitals in production
- [ ] Check CDN cache hit rates
- [ ] Monitor Time to First Byte (TTFB)
- [ ] Track JavaScript error rates
- [ ] Review performance budgets

---

## Performance Budgets

Target budgets per page:

| Metric | Target | Maximum |
|--------|--------|---------|
| JavaScript | < 200 KB | 300 KB |
| CSS | < 50 KB | 75 KB |
| Images | < 500 KB | 1 MB |
| Total Page Size | < 1 MB | 1.5 MB |
| Requests | < 50 | 75 |

---

## Tools & Resources

### Analysis Tools

- **Lighthouse**: Built into Chrome DevTools
- **WebPageTest**: https://webpagetest.org
- **Bundle Analyzer**: `npm run analyze`
- **Chrome DevTools Performance Tab**
- **Next.js Build Output**: Shows bundle sizes

### Monitoring Services

- Google PageSpeed Insights
- Vercel Analytics (if deployed on Vercel)
- Google Analytics (Web Vitals reporting)
- Sentry (Performance monitoring)

### Further Reading

- [Next.js Performance](https://nextjs.org/docs/advanced-features/measuring-performance)
- [Web.dev Performance](https://web.dev/performance/)
- [Astro Performance](https://docs.astro.build/en/guides/performance/)
- [Core Web Vitals](https://web.dev/vitals/)

---

## Continuous Improvement

Performance optimization is an ongoing process:

1. **Measure**: Use tools to establish baseline metrics
2. **Analyze**: Identify bottlenecks and opportunities
3. **Optimize**: Implement improvements
4. **Test**: Verify improvements with real-world testing
5. **Monitor**: Track metrics in production
6. **Repeat**: Regular performance audits

---

## Support

For questions or suggestions regarding performance optimization:

1. Review this documentation
2. Check Next.js/Astro documentation
3. Run bundle analysis to identify issues
4. Test with Lighthouse for recommendations

---

Last Updated: December 2025
