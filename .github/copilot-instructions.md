# MarketingTool.Pro - AI Coding Agent Instructions

## 🏗️ Architecture Overview

**MarketingTool.Pro** is a SaaS platform providing 226+ AI-powered marketing tools across 10 categories (Meta/Facebook, AI Agents, PPC, SEO, Content, E-commerce, Analytics, Email, Social Media, Video).

### Tech Stack
- **Frontend**: Astro 5 + React 19 + TypeScript + Tailwind CSS 4 + shadcn/ui
- **Backend**: Cloudflare Workers (serverless API endpoints)
- **Database**: Webflow CMS (headless content management)
- **AI**: OpenAI GPT-4 + Anthropic Claude (content generation, automation)
- **Payments**: Stripe integration
- **Ads APIs**: Google Ads API, Meta Ads API

### Key Metrics
- 418 React components, 37 Astro pages
- 50,000+ lines of code
- Build time: ~2 min, Bundle: ~500KB, Lighthouse: 95+

### Component Structure
- Use `src/pages/` for Astro routes (tool categories, individual tools)
- Use `src/components/` for reusable React components (shadcn/ui based)
- Use `src/lib/` for utilities, API clients, types

## 🚀 Development Workflows

### Local Development
```bash
npm run dev          # Start Astro dev server (port 4321)
npm run build        # Production build
npm run preview      # Local preview with Cloudflare Workers
```

### Docker Development
```bash
docker-compose up -d  # Start dev container
docker exec -it marketingtool-dev sh
npm run dev
```

### Deployment
- Automatic via GitHub Actions on `main` branch push
- Deploys to Cloudflare Workers
- CI: Node 20, npm ci, npm run build

## 📋 Code Conventions

### TypeScript
- Strict typing required for all components and APIs
- Use interfaces for API responses (e.g., `GoogleAdsCampaign`, `MetaAdSet`)
- Export types from `src/lib/types.ts`

### React Components
- Use shadcn/ui components as base (Button, Card, Form, etc.)
- Functional components with hooks
- Props interfaces for type safety

### API Integration
- Cloudflare Workers endpoints in `src/pages/api/`
- Authentication: Bearer tokens
- Rate limits: Free (100/h), Pro (1000/h), Enterprise (unlimited)

### Example Patterns
```typescript
// API client pattern
export async function createGoogleAdsCampaign(data: CampaignData) {
  const response = await fetch('/api/google-ads/campaigns', {
    method: 'POST',
    headers: { 'Authorization': `Bearer ${token}` },
    body: JSON.stringify(data)
  });
  return response.json();
}

// Component pattern
interface ToolCardProps {
  title: string;
  description: string;
  category: ToolCategory;
}

export function ToolCard({ title, description, category }: ToolCardProps) {
  return (
    <Card className="p-6">
      <h3>{title}</h3>
      <p>{description}</p>
      <Badge>{category}</Badge>
    </Card>
  );
}
```

## 🔧 Tool Categories & APIs

### Primary Integrations
- **Google Ads**: Campaign creation, budget management, performance tracking
- **Meta Ads**: Ad sets, creative optimization, audience targeting
- **AI Content**: Blog posts, social media copy, email campaigns
- **Analytics**: Real-time dashboard, conversion tracking

### API Endpoints
```typescript
POST /api/google-ads/campaigns
POST /api/meta-ads/campaigns  
POST /api/openai/generate
GET  /api/analytics/dashboard
```

## 📝 Contribution Guidelines

### Pull Requests
- Use PR template with change type and checklist
- Test locally before submitting
- Update documentation for API changes
- Follow conventional commits

### Code Quality
- Prettier formatting
- TypeScript strict mode
- Component testing required for new features
- Lighthouse performance targets: 95+

### File Organization
- `src/pages/tools/` - Individual tool pages
- `src/components/tools/` - Tool-specific components  
- `src/lib/apis/` - API client functions
- `src/lib/types/` - TypeScript definitions</content>
<parameter name="filePath">/workspaces/marketingtool-pro/.github/copilot-instructions.md