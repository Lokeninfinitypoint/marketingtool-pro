# Copilot Instructions for Adabaduit.com

This is a static HTML export of the Adabaduit.com WordPress website - a PPC (Pay-Per-Click) optimization and auditing tool for Google Ads professionals.

## Project Structure

### Site Architecture
- **Static Site**: Pre-generated HTML files from WordPress export, no dynamic PHP processing
- **Content Organization**: Topic-based directory structure (`blog/`, `features/`, `tool-comparisons/`, etc.)
- **Page IDs**: WordPress-style parametric URLs (`index.html?p=1234.html`) for legacy content
- **Theme Assets**: Custom "adabaduit" theme in `wp-content/themes/adabaduit/assets/`

### Directory Patterns
```
/
├── wp-content/
│   ├── themes/adabaduit/assets/   # Custom fonts, images, CSS
│   ├── uploads/                  # Content images by year/month
│   └── cache/                    # WP Rocket optimization cache
├── blog/                         # Blog posts and categories
├── features/                     # Product feature pages
├── tool-comparisons/             # Competitor comparison pages
└── [topic-directories]/          # PPC-focused landing pages
```

## Technical Patterns

### Performance Optimization (WP Rocket)
- **Lazy Loading**: All images use `data-lazy-src` with SVG placeholders
- **Script Deferral**: `RocketLazyLoadScripts` class manages script loading
- **Font Preloading**: Critical fonts (NeutrifPro family) preloaded in `<head>`
- **CSS Inlining**: Critical styles embedded, non-critical deferred

### Content Conventions
- **Image Handling**: Consistent pattern `data:image/svg+xml` placeholders → `data-lazy-src` for real images
- **Responsive Images**: `srcset` and `sizes` attributes for different viewport widths
- **WordPress Classes**: Maintains WP CSS classes (`wp-image-[ID]`, `size-large`, etc.)

### URL Structure
- **Clean URLs**: Topic-based paths (`/ppc-automation-tools/`, `/google-ads-quality-score/`)
- **Legacy Support**: Parametric URLs for imported content (`index.html?p=1234.html`)
- **Internal Linking**: Relative paths, preserves WordPress `?p=` parameters in links

## Development Guidelines

### When Working with Images
```html
<!-- Standard pattern for all images -->
<img src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%20WIDTH%20HEIGHT'%3E%3C/svg%3E" 
     alt="descriptive-alt-text" 
     data-lazy-src="wp-content/uploads/YYYY/MM/image-name.jpg"
     data-lazy-srcset="..." 
     data-lazy-sizes="..." />
```

### Font Loading
- Use NeutrifPro font family (Light, Regular, Medium, SemiBold, Bold)
- Preload critical weights in document head
- Font files in `wp-content/themes/adabaduit/assets/fonts/`

### Content Updates
- **Blog Posts**: Follow existing structure in `/blog/post-title/index.html`
- **Feature Pages**: Maintain comparison tables and feature lists format
- **Images**: Place in appropriate `wp-content/uploads/YYYY/MM/` directory
- **Internal Links**: Use relative paths, maintain existing URL patterns

### Performance Best Practices
- Preserve WP Rocket optimization patterns
- Maintain lazy loading for all non-critical images
- Keep critical CSS inline, defer non-critical styles
- Use appropriate `fetchpriority` attributes for above-fold content

## Content Domain Knowledge

**Business Focus**: Google Ads optimization, PPC auditing, campaign management tools
**Target Audience**: PPC specialists, digital marketing agencies, Google Ads professionals
**Key Topics**: Campaign structure, quality score optimization, ad testing, automation tools

When creating content, emphasize technical PPC best practices, data-driven optimization strategies, and practical Google Ads management workflows.