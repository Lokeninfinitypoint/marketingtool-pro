#!/usr/bin/env node

/**
 * HTML Optimization Script for Madgicx.com
 * Optimizes all HTML files for performance:
 * - Defer/async scripts
 * - Lazy load images
 * - Add resource hints
 * - Optimize CSS loading
 * - Minify inline scripts/styles
 */

const fs = require('fs');
const path = require('path');

const MADGICX_DIR = path.join(__dirname, 'madgicx.com');

// Optimization functions
function optimizeHTML(html) {
  let optimized = html;

  // 1. Optimize script tags - add defer/async where appropriate
  optimized = optimized.replace(
    /<script(?![^>]*(?:defer|async))([^>]*src=["']([^"']+)["'][^>]*)>/gi,
    (match, attrs, src) => {
      // Skip if already has defer/async
      if (attrs.includes('defer') || attrs.includes('async')) {
        return match;
      }
      
      // Analytics and tracking scripts should be async
      if (src.includes('gtm.js') || 
          src.includes('googletagmanager') || 
          src.includes('datafa.st') ||
          src.includes('analytics') ||
          src.includes('tracking')) {
        return match.replace('>', ' async>');
      }
      
      // Other scripts should be deferred
      return match.replace('>', ' defer>');
    }
  );

  // 2. Add lazy loading to images (except above-fold images)
  optimized = optimized.replace(
    /<img([^>]*)(?<!loading=["'](?:eager|lazy)["'])([^>]*)>/gi,
    (match, before, after) => {
      // Skip if already has loading attribute
      if (match.includes('loading=')) {
        return match;
      }
      
      // Keep eager for hero images (check for common hero classes)
      if (match.includes('hero') || match.includes('screen-tabs_img') || match.includes('nav2_image')) {
        return match.replace('>', ' loading="eager">');
      }
      
      // Add lazy loading to all other images
      return match.replace('>', ' loading="lazy">');
    }
  );

  // 3. Add fetchpriority="high" to critical images
  optimized = optimized.replace(
    /<img([^>]*)(srcset|src)=["']([^"']+)["']([^>]*)(?<!fetchpriority=["']high["'])([^>]*)>/gi,
    (match) => {
      if (match.includes('hero') || match.includes('nav2_image') || match.includes('logo')) {
        if (!match.includes('fetchpriority=')) {
          return match.replace('>', ' fetchpriority="high">');
        }
      }
      return match;
    }
  );

  // 4. Optimize CSS loading - add preload for critical CSS
  if (!optimized.includes('rel="preload"') && optimized.includes('rel="stylesheet"')) {
    const cssMatch = optimized.match(/<link([^>]*href=["']([^"']*\.css[^"']*)["'][^>]*)>/i);
    if (cssMatch && cssMatch[2]) {
      const cssHref = cssMatch[2];
      const preloadLink = `<link rel="preload" href="${cssHref}" as="style" onload="this.onload=null;this.rel='stylesheet'">\n<noscript><link rel="stylesheet" href="${cssHref}"></noscript>`;
      optimized = optimized.replace(cssMatch[0], preloadLink);
    }
  }

  // 5. Add resource hints for external domains
  const externalDomains = [
    'cdn.prod.website-files.com',
    'global-uploads.webflow.com',
    'uploads-ssl.webflow.com',
    'www.googletagmanager.com',
    'cdn.jsdelivr.net',
    'datafa.st',
    'app.madgicx.com'
  ];

  let preconnectLinks = '';
  externalDomains.forEach(domain => {
    if (optimized.includes(domain) && !optimized.includes(`preconnect.*${domain}`)) {
      preconnectLinks += `<link rel="preconnect" href="https://${domain}" crossorigin>\n`;
      preconnectLinks += `<link rel="dns-prefetch" href="https://${domain}">\n`;
    }
  });

  if (preconnectLinks && !optimized.includes('<!-- Resource Hints -->')) {
    optimized = optimized.replace('</head>', `<!-- Resource Hints -->\n${preconnectLinks}</head>`);
  }

  // 6. Add preload for critical resources
  const criticalResources = [
    { pattern: /madgicx-web-logo\.png/i, as: 'image' },
    { pattern: /\.avif/i, as: 'image' }
  ];

  // 7. Optimize inline scripts - minify basic cases
  optimized = optimized.replace(
    /<script[^>]*>([\s\S]*?)<\/script>/gi,
    (match, content) => {
      // Skip if it's JSON-LD or already minified
      if (match.includes('application/ld+json') || content.trim().length < 50) {
        return match;
      }
      
      // Basic minification: remove comments and extra whitespace
      const minified = content
        .replace(/\/\*[\s\S]*?\*\//g, '') // Remove block comments
        .replace(/\/\/.*$/gm, '') // Remove line comments
        .replace(/\s+/g, ' ') // Collapse whitespace
        .trim();
      
      return match.replace(content, minified);
    }
  );

  // 8. Add async to Google Tag Manager
  optimized = optimized.replace(
    /<script[^>]*gtm\.js[^>]*>/gi,
    (match) => {
      if (!match.includes('async') && !match.includes('defer')) {
        return match.replace('>', ' async>');
      }
      return match;
    }
  );

  // 9. Optimize font loading - add font-display: swap
  optimized = optimized.replace(
    /@font-face\s*\{([^}]*)\}/gi,
    (match, content) => {
      if (!content.includes('font-display')) {
        return match.replace('{', '{font-display:swap;');
      }
      return match;
    }
  );

  // 10. Add meta tags for performance
  if (!optimized.includes('http-equiv="x-dns-prefetch-control"')) {
    optimized = optimized.replace(
      '<head>',
      '<head>\n<meta http-equiv="x-dns-prefetch-control" content="on">'
    );
  }

  return optimized;
}

// Process all HTML files
function processDirectory(dir) {
  const files = fs.readdirSync(dir);
  let processed = 0;
  let errors = 0;

  files.forEach(file => {
    const filePath = path.join(dir, file);
    const stat = fs.statSync(filePath);

    if (stat.isDirectory()) {
      const result = processDirectory(filePath);
      processed += result.processed;
      errors += result.errors;
    } else if (file.endsWith('.html')) {
      try {
        const content = fs.readFileSync(filePath, 'utf8');
        const optimized = optimizeHTML(content);
        
        // Only write if content changed
        if (content !== optimized) {
          fs.writeFileSync(filePath, optimized, 'utf8');
          processed++;
          if (processed % 100 === 0) {
            console.log(`Processed ${processed} files...`);
          }
        }
      } catch (error) {
        console.error(`Error processing ${filePath}:`, error.message);
        errors++;
      }
    }
  });

  return { processed, errors };
}

// Main execution
console.log('Starting HTML optimization for madgicx.com...');
console.log(`Processing directory: ${MADGICX_DIR}`);

if (!fs.existsSync(MADGICX_DIR)) {
  console.error(`Directory not found: ${MADGICX_DIR}`);
  process.exit(1);
}

const startTime = Date.now();
const result = processDirectory(MADGICX_DIR);
const endTime = Date.now();

console.log('\n=== Optimization Complete ===');
console.log(`Files processed: ${result.processed}`);
console.log(`Errors: ${result.errors}`);
console.log(`Time taken: ${((endTime - startTime) / 1000).toFixed(2)}s`);
