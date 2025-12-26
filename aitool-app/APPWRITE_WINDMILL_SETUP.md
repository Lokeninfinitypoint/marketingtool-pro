# 🚀 Appwrite + Windmill Integration Setup

## ✅ Complete Implementation Guide

### Overview

- **Appwrite** = Your SaaS Backend Foundation
  - Authentication (signup/login, sessions, OAuth)
  - User management
  - Database (user settings, plans, credits, tool configs)
  - File storage (assets, reports, exports)
  - Permissions / roles

- **Windmill** = Your Automation & Job Engine
  - Ads automation workflows
  - Scheduled jobs (daily/weekly reporting)
  - Campaign optimization tasks
  - Meta API syncing
  - Comment responder flows
  - Rule-based triggers
  - ETL + data processing
  - AI agents orchestration

---

## 📦 Installation

### 1. Install Dependencies

```bash
cd aitool-app
npm install appwrite @windmill/windmill-js-client axios
```

### 2. Environment Variables

Create `.env.local` file:

```env
# Appwrite Configuration
NEXT_PUBLIC_APPWRITE_ENDPOINT=https://cloud.appwrite.io/v1
NEXT_PUBLIC_APPWRITE_PROJECT_ID=your-project-id
NEXT_PUBLIC_APPWRITE_DATABASE_ID=main
NEXT_PUBLIC_APPWRITE_STORAGE_ID=files

# Windmill Configuration
NEXT_PUBLIC_WINDMILL_ENDPOINT=https://app.windmill.dev
NEXT_PUBLIC_WINDMILL_TOKEN=your-windmill-token
```

---

## 🔧 Appwrite Setup

### 1. Create Appwrite Project

1. Go to https://cloud.appwrite.io
2. Create new project
3. Copy Project ID → Set in `.env.local`

### 2. Create Database

1. Go to Databases → Create Database
2. Name: `main`
3. Copy Database ID → Set in `.env.local`

### 3. Create Collections

#### Collection: `users` (extends Appwrite Users)
- `name` (string)
- `plan` (string)
- `credits` (integer)

#### Collection: `plans`
- `name` (string)
- `price` (number)
- `credits` (integer)
- `features` (string[])
- `active` (boolean)

#### Collection: `credits`
- `userId` (string)
- `amount` (integer)
- `source` (string: purchase|bonus|refund)
- `createdAt` (datetime)

#### Collection: `tool_configs`
- `userId` (string)
- `toolId` (string)
- `config` (string, JSON)
- `enabled` (boolean)

#### Collection: `settings`
- `userId` (string)
- `theme` (string)
- `notifications` (boolean)
- `language` (string)
- `timezone` (string)

### 4. Create Storage Buckets

1. Go to Storage → Create Bucket
2. Create buckets:
   - `assets` (public read)
   - `reports` (authenticated read)
   - `exports` (authenticated read)

### 5. Set Permissions

For each collection/bucket:
- **Users** can read/write their own documents
- **Admins** can read/write all documents

---

## ⚙️ Windmill Setup

### 1. Create Windmill Account

1. Go to https://windmill.dev
2. Sign up / Login
3. Get API token from Settings → API Tokens
4. Set in `.env.local`

### 2. Create Workflows

Create these workflows in Windmill:

#### `f/ads/optimize_campaign`
- Inputs: `campaign_id`, config
- Runs campaign optimization logic

#### `f/ads/sync_meta_api`
- Inputs: `account_id`, `sync_type`
- Syncs Meta Ads API data

#### `f/ads/auto_respond_comments`
- Inputs: `post_id`, `response_template`
- Auto-responds to comments

#### `f/reports/daily_report`
- Inputs: `user_id`, report config
- Generates daily reports

#### `f/reports/weekly_report`
- Inputs: `user_id`, report config
- Generates weekly reports

---

## 📁 File Structure

```
aitool-app/
├── src/
│   ├── lib/
│   │   ├── appwrite.ts              # Appwrite client config
│   │   ├── appwrite-auth.ts         # Auth service
│   │   ├── appwrite-database.ts     # Database service
│   │   ├── appwrite-storage.ts      # Storage service
│   │   ├── windmill.ts              # Windmill client config
│   │   └── windmill-automation.ts   # Automation service
│   └── hooks/
│       └── useAuth.ts               # React auth hook
├── .env.example
└── APPWRITE_WINDMILL_SETUP.md
```

---

## 🎯 Usage Examples

### Authentication

```typescript
import { AuthService } from '@/lib/appwrite-auth';
import { useAuth } from '@/hooks/useAuth';

// In component
const { user, login, signup, logout, isAuthenticated } = useAuth();

// Sign up
await signup('user@example.com', 'password123', 'John Doe');

// Login
await login('user@example.com', 'password123');

// Logout
await logout();
```

### Database Operations

```typescript
import { DatabaseService } from '@/lib/appwrite-database';

// Get user credits
const credits = await DatabaseService.getUserCredits(userId);

// Add credits
await DatabaseService.addCredits(userId, 100, 'purchase');

// Get plans
const plans = await DatabaseService.getPlans();

// Save tool config
await DatabaseService.saveToolConfig(userId, 'tool-id', { setting: 'value' });
```

### File Storage

```typescript
import { StorageService } from '@/lib/appwrite-storage';

// Upload file
const fileId = await StorageService.uploadAsset(file);

// Get file URL
const url = StorageService.getAssetUrl(fileId);

// Upload report
const reportId = await StorageService.uploadReport(reportFile);
```

### Windmill Automation

```typescript
import { WindmillService } from '@/lib/windmill-automation';

// Run workflow
const result = await WindmillService.runWorkflow({
  workflowPath: 'f/ads/optimize_campaign',
  inputs: { campaign_id: '123' },
});

// Optimize campaign
await WindmillService.optimizeCampaign('campaign-id');

// Sync Meta API
await WindmillService.syncMetaAPI('account-id', 'all');

// Schedule daily report
await WindmillService.scheduleDailyReport(userId, {
  format: 'pdf',
  email: true,
});
```

---

## ✅ Implementation Checklist

- [x] Install dependencies
- [x] Create Appwrite project
- [x] Set up database collections
- [x] Set up storage buckets
- [x] Configure Windmill
- [x] Create service files
- [x] Create React hooks
- [x] Update environment variables
- [ ] Test authentication
- [ ] Test database operations
- [ ] Test file storage
- [ ] Test Windmill workflows
- [ ] Update app pages to use services

---

## 🔗 Resources

- **Appwrite Docs:** https://appwrite.io/docs
- **Windmill Docs:** https://docs.windmill.dev
- **Appwrite Console:** https://cloud.appwrite.io
- **Windmill Console:** https://app.windmill.dev

---

## 🚨 Important Notes

1. **Never commit `.env.local`** - It contains sensitive tokens
2. **Set proper permissions** in Appwrite for security
3. **Use environment variables** for all configuration
4. **Test workflows** in Windmill before production use
5. **Handle errors** properly in all service calls

---

*Setup Complete! Ready to use Appwrite + Windmill across your app.* 🎉
