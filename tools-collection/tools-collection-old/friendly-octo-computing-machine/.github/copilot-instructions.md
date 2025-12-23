# Copilot Instructions for PPC Marketing Website

## Project Overview

This is a multi-site WordPress project centered around PPC (Pay-Per-Click) marketing tools and documentation. The main sites are:
- **Waduit** (main site) - PPC management and auditing platform
- **Adalysis.com** (subdirectory) - Legacy Adalysis marketing content
- **Documentation site** - Tool guides and onboarding materials

## Architecture & Key Components

### WordPress Structure
- **Main WP Site**: Root level WordPress installation with custom theme at `wp-content/themes/adalysis/`
- **Custom Theme**: Minimal theme with assets in `adalysis/assets/` (fonts, images)
- **Plugins**: Standard WP plugins including Akismet, Formidable, WP Rocket for caching
- **Content Strategy**: Landing pages for PPC tools, documentation, and marketing content

### Static Sites & Documentation
- **`/public/`**: Static site files with Vercel deployment config
- **`/adalysis.com/`**: Large collection (298 dirs) of legacy Adalysis marketing content
- **Tool Documentation**: Structured in `/tools/` with markdown files for audit, performance, bidding tools
- **Onboarding**: `/onboarding/` directory with getting-started guides

### Deployment & Infrastructure
- **Vercel**: Primary deployment platform (`vercel.json` with redirects and security headers)
- **External Help Site**: `/help/*` redirects to `help.waduit.sbs`
- **Security Headers**: CSP, HSTS, X-Frame-Options configured in Vercel config
- **URL Strategy**: Clean URLs with trailing slash removal

## Development Patterns & Conventions

### Content Organization
```
tools/                     # Tool-specific documentation
├── audit.md              # Main audit tool docs
├── performance/           # Performance monitoring tools
├── ad-testing.md          # A/B testing functionality
└── bidding.md             # Bid management tools

manage/                    # User management guides
├── campaigns.md           # Campaign management
├── keywords.md            # Keyword tools
└── search-terms.md        # Search term analysis
```

### WordPress Customizations
- **Minimal Theme Approach**: Custom theme with basic asset structure rather than heavy customization
- **Plugin Strategy**: Relies on established plugins (WP Rocket for performance, Formidable for forms)
- **Content Types**: Primarily pages and posts for marketing content and tool documentation

### Security & Performance
- **Security Headers**: Comprehensive CSP policy allowing Google services for reCAPTCHA
- **Performance**: WP Rocket caching + Vercel edge deployment
- **Redirects**: Legacy URL management via Vercel redirects (pricing, testimonials, awards → home)

## Critical Workflows

### Content Updates
1. **Documentation**: Edit markdown files in `/tools/`, `/manage/`, or `/onboarding/`
2. **WordPress Content**: Standard WP admin for marketing pages and blog content
3. **Static Pages**: Update HTML in `/public/` for standalone pages

### Deployment
- **Primary**: Vercel automatic deployment from main branch
- **WordPress**: Hosted separately, files in root directory
- **Assets**: Theme assets served from `wp-content/themes/adalysis/assets/`

### Tool Integration
- **External APIs**: Likely integrates with Google Ads API (evidenced by CSP policy)
- **Help Documentation**: Separate subdomain for comprehensive help content
- **Cross-Site Navigation**: Managed via redirects and header configuration

## Development Commands

```bash
# WordPress development (if wp-cli available)
wp plugin list                    # Check active plugins
wp theme list                     # Check active themes

# Static site testing
python -m http.server 8000        # Serve public/ directory locally
vercel dev                        # Local Vercel environment

# Content validation
find . -name "*.md" -exec grep -l "TODO\|FIXME" {} \;  # Find incomplete docs
```

## Key Files for AI Context

- **`SUMMARY.md`**: Table of contents for all documentation
- **`vercel.json`**: Deployment config with redirects and security
- **`wp-content/themes/adalysis/`**: Custom theme structure
- **`tools/`**: Core product documentation that drives user understanding
- **`/public/index.html`**: Main landing page structure
- **`.github/workflows/codeql.yml`**: Security scanning configuration

## Integration Points

- **Google Services**: reCAPTCHA, Google Ads API integration
- **External Documentation**: Help subdomain (help.waduit.sbs)
- **Legacy Content**: Adalysis.com content migration and management
- **Form Processing**: Formidable plugin for user interactions

## Notes for AI Agents

- **Content Structure**: Follow existing markdown patterns in `/tools/` for consistency
- **WordPress Changes**: Test locally before deployment, minimal theme customization preferred
- **Security**: Maintain CSP policy when adding new external services
- **Performance**: Consider WP Rocket caching when making backend changes
- **URL Changes**: Update Vercel redirects when restructuring content
