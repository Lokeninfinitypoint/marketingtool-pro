# MarketingTool.Pro - AI Agent Instructions

## Project Architecture

This is a **dual-application monorepo**:
1. **Main Astro Site** (`/`) - Marketing website built with Astro 5, React 19, and Tailwind 4
2. **Webflow Extension** (`/webflow-app/`) - Standalone Designer Extension & Data Client for Webflow

Both apps are independent but share the same repository. The Webflow extension is a pure HTML/CSS/JS app that integrates with Webflow's Designer API.

## Critical Workflows

### Development Setup
- **Docker Dev Container**: Use `.devcontainer/devcontainer.json` for consistent environment (Node 20)
- **Main site**: `npm run dev` (runs on port 4321)
- **Webflow extension**: `cd webflow-app && npm run dev` (if configured, or serve static files on port 5173)
- **Multiple ports forwarded**: 3000, 4321, 5173 - check `docker-compose.yml`

### Essential Commands
```bash
# Development
npm run dev              # Start Astro dev server (port 4321)
npm run build            # Build production site
npm run preview          # Preview production build locally

# Deployment (choose one)
./DEPLOY.sh              # Interactive deployment (recommended)
npm run build && npx wrangler deploy  # Direct Cloudflare deploy
vercel deploy --prod     # Deploy to Vercel
netlify deploy --prod    # Deploy to Netlify
```

### Webflow Extension Development
The extension lives in `/webflow-app/` and consists of:
- **Designer Extension** (`src/index.html`) - 418+ AI marketing tools interface, searchable/filterable
- **Data Client** (`src/data-client.html`) - Bulk CMS content generator
- **Manifest** (`webflow.json`) - Defines extension metadata, pricing tiers, permissions

**Key constraint**: Webflow extensions require **pure HTML/CSS/JS** (no build step for production). All code in `src/` is served directly.

### Deployment Workflow

**Main Astro Site:**
- **Automated script**: `./DEPLOY.sh` - Interactive deployment to Cloudflare/Vercel/Netlify/GitHub Pages
- **Manual deploy**: `npm run build && npx wrangler deploy` (Cloudflare recommended)
- **Local preview**: `npm run preview` on port 4321
- **See**: `SIMPLE_DEPLOY_GUIDE.md`, `DEPLOY_NOW.md`, `README_DEPLOYMENT.md` for detailed instructions

**Webflow Extension:**
- Uploaded as ZIP bundle to Webflow Apps Dashboard
- Approval process takes 1-2 weeks (see `WEBFLOW_WORKFLOW_FIX.md`)
- No build step - pure HTML/CSS/JS served directly

## Project-Specific Conventions

### Webflow Extension Structure
```
webflow-app/
├── src/
│   ├── index.html          # Designer Extension entry (400px wide panel)
│   └── data-client.html    # CMS bulk content generator
├── webflow.json            # Extension manifest (pricing, permissions, metadata)
└── package.json            # Metadata only, no build scripts
```

### Astro Site Patterns
- **Minimal setup**: Base Astro template with React integration
- **Tailwind 4**: Configured via Vite plugin (`@tailwindcss/vite`)
- **TypeScript**: Strict mode enabled, React JSX configured
- **Pages**: Minimal structure, auth folder exists but is empty (placeholder for future auth)

### Documentation Organization
The project uses **guide-based documentation**:
- `DOCUMENTATION_GUIDE.md` - Complete help center structure (418 tools documented)
- `WEBFLOW_WORKFLOW_FIX.md` - Critical troubleshooting for extension deployment
- `ANALYTICS_GUIDE.md` - Analytics setup (Google Analytics, Mixpanel, Stripe)
- `DOCKER_VSCODE_SETUP.md` - Dev container setup instructions

## Integration Points

### Webflow API Integration
- **Designer Extension**: Uses Webflow Designer Extension API (client-side JS)
- **Data Client**: Requires `cms:write` permission (specified in `webflow.json`)
- **No backend**: Extensions run entirely client-side within Webflow Designer

### External Services (Planned)
- **Analytics**: Google Analytics 4, Mixpanel (tracking tool usage, content generation)
- **Payments**: Stripe (3 tiers: Free/Pro/Enterprise - see `webflow.json` pricing)
- **API**: Pro/Enterprise tiers mention API access (not yet implemented)

## Key File References

### Extension Configuration
- `webflow-app/webflow.json` - Extension manifest with all metadata, pricing, permissions
- `webflow-app/src/index.html` - Main extension UI (418 tools, search, categories)
- `webflow-app/src/data-client.html` - CMS content generator interface

### Build Configuration
- `astro.config.mjs` - React integration + Tailwind Vite plugin
- `docker-compose.yml` - Dev environment with node:20-bullseye
- `.devcontainer/devcontainer.json` - VS Code container config with Astro/Tailwind extensions

### Documentation
- `WEBFLOW_WORKFLOW_FIX.md` - **READ FIRST** when debugging extension deployment
- `DOCUMENTATION_GUIDE.md` - Reference for understanding the 418 tools structure
- `SIMPLE_DEPLOY_GUIDE.md` - Easiest deployment guide (start here for deployment)
- `DEPLOY.sh` - Automated deployment script (executable, interactive)
- `FINAL_DEPLOYMENT_VERIFICATION.md` - Full verification report

## Common Pitfalls

1. **Webflow extensions are client-only**: No build process, no npm modules in production. All JS must be vanilla or CDN-loaded.
2. **Extension approval delay**: Takes 1-2 weeks. Local testing requires serving static files.
3. **Two separate apps**: Don't confuse Astro site dependencies with Webflow extension. They share a repo but are completely independent.
4. **Port confusion**: Main site (4321), Webflow local dev (5173), generic dev (3000) - check which app you're working on.

## Current Deployment Status

- **Live Site**: https://02651b04.marketingtool-pro.pages.dev (Cloudflare Pages)
- **Pages Deployed**: Homepage, Dashboard, Pricing, Auth (login/signup), 13 Tool Categories
- **Total Pages**: 18 pages built and deployed
- **Platform**: Astro 5 + React 19 + Tailwind 4
- **Deployment**: `npm run build && npx wrangler pages deploy dist --project-name=marketingtool-pro`

## When Making Changes

- **Webflow extension**: Edit HTML directly in `webflow-app/src/`, test by serving static files
- **Main site**: Use Astro dev server, changes hot-reload on port 4321
- **Dependencies**: Check `package.json` location - root for Astro, `webflow-app/` for extension metadata
- **Documentation**: Update relevant guide files, not just code comments
- **Dynamic Routes**: Remember to add `getStaticPaths()` export for any `[param].astro` files
