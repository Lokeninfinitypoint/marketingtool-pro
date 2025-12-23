# 🚀 MarketingTool.Pro - Deployment Guide

## Quick Deploy Options

### Option 1: 🌐 Cloudflare Pages (Recommended)

**Best for**: Astro sites, Free hosting, Global CDN

**Steps:**
1. Go to https://dash.cloudflare.com/
2. Click **Workers & Pages** → **Create Application** → **Pages**
3. Click **Connect to Git**
4. Select your GitHub account: **Lokeninfinitypoint**
5. Choose repository: **marketingtool-pro**
6. Configure build:
   - **Framework preset**: Astro
   - **Build command**: `npm run build`
   - **Build output directory**: `dist`
7. Click **Save and Deploy**

✅ **Live at**: `https://marketingtool-pro.pages.dev`

---

### Option 2: ▲ Vercel

**Best for**: Next.js apps, Serverless functions

**Steps:**
1. Go to https://vercel.com/new
2. Click **Import Git Repository**
3. Select **Lokeninfinitypoint/marketingtool-pro**
4. Vercel auto-detects Astro
5. Click **Deploy**

✅ **Live at**: `https://marketingtool-pro.vercel.app`

**For Dashboard & AI Tool Apps:**
```bash
# Deploy dashboard
cd dashboard-app
vercel --prod

# Deploy AI tool
cd aitool-app
vercel --prod
```

---

### Option 3: 🔷 Netlify

**Best for**: Static sites, Forms

**Steps:**
1. Go to https://app.netlify.com/start
2. Click **New site from Git**
3. Choose **GitHub**
4. Select **marketingtool-pro**
5. Build settings:
   - **Build command**: `npm run build`
   - **Publish directory**: `dist`
6. Click **Deploy site**

✅ **Live at**: `https://marketingtool-pro.netlify.app`

---

### Option 4: 🖥️ Your VPS (31.220.107.19)

**Best for**: Full control, Custom configuration

**Method A: Deploy via Git Pull**

SSH into your server:
```bash
ssh root@31.220.107.19
```

Then run:
```bash
# Navigate to web directory
cd /var/www/marketingtool.pro

# Pull latest from GitHub
git clone https://github.com/Lokeninfinitypoint/marketingtool-pro.git .
# OR if already exists:
git pull origin main

# Install dependencies
npm install

# Build the project
npm run build

# Setup PM2 (if not already)
npm install -g pm2
pm2 start npm --name "marketingtool" -- start
pm2 save
pm2 startup

# Configure Nginx
nano /etc/nginx/sites-available/marketingtool.pro
```

**Nginx Configuration:**
```nginx
server {
    listen 80;
    server_name marketingtool.pro www.marketingtool.pro;

    root /var/www/marketingtool.pro/dist;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }

    # Gzip compression
    gzip on;
    gzip_types text/css text/javascript application/javascript;
}
```

Then:
```bash
# Enable site
ln -s /etc/nginx/sites-available/marketingtool.pro /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx

# Setup SSL with Let's Encrypt
apt install certbot python3-certbot-nginx -y
certbot --nginx -d marketingtool.pro -d www.marketingtool.pro
```

**Method B: Deploy via SCP**

From your local machine:
```bash
# Build locally first
cd /Users/loken/Projects/marketingtool-pro
npm run build

# Upload to server
scp -r dist/* root@31.220.107.19:/var/www/marketingtool.pro/html/
```

---

## Deploy All Apps

### Main Site (Astro)
```bash
npm run build
# Deploy dist/ folder
```

### Dashboard (Next.js)
```bash
cd dashboard-app
npm run build
# Deploy .next/ folder or use Vercel
```

### AI Tool (Next.js)
```bash
cd aitool-app
npm run build
# Deploy .next/ folder or use Vercel
```

### Static Tools (880+ pages)
```bash
# Already built, just upload tools-collection/ folder
# Can serve directly with nginx or any static host
```

---

## Custom Domain Setup

### Point Domain to Cloudflare/Vercel/Netlify:

1. **Go to your domain registrar** (GoDaddy, Namecheap, etc.)
2. **Add DNS records:**

For Cloudflare Pages:
```
A     @     192.0.2.1  (Cloudflare IP)
CNAME www   marketingtool-pro.pages.dev
```

For Vercel:
```
A     @     76.76.21.21
CNAME www   cname.vercel-dns.com
```

For Netlify:
```
A     @     75.2.60.5
CNAME www   marketingtool-pro.netlify.app
```

3. **Add custom domain** in hosting dashboard
4. **Enable SSL** (automatic on most platforms)

---

## Environment Variables

Create `.env` file for production:
```bash
# API Keys (if needed)
OPENAI_API_KEY=your_key
ANTHROPIC_API_KEY=your_key
GOOGLE_ADS_API_KEY=your_key
META_ADS_API_KEY=your_key

# Database (if using)
DATABASE_URL=your_database_url

# Analytics
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
```

Add these to your hosting platform's environment variables section.

---

## GitHub Actions Auto-Deploy (Optional)

Already configured in `.github/workflows/ci.yml`

Every push to `main` branch will:
1. Build the project
2. Run tests
3. Auto-deploy to configured hosting

---

## Quick Commands Reference

```bash
# Local development
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Deploy to VPS
./DEPLOY.sh

# Check build output
ls -la dist/
```

---

## Monitoring & Maintenance

### After Deployment:

1. **Test the site**: Visit your deployed URL
2. **Check SSL**: Ensure HTTPS is working
3. **Test forms**: If any contact forms exist
4. **Monitor performance**: Use Google PageSpeed Insights
5. **Setup monitoring**: Use Cloudflare Analytics or Google Analytics

### Update Process:

```bash
# Make changes locally
git add .
git commit -m "Update site"
git push origin main

# Auto-deploys if GitHub Actions configured
# OR manually pull on VPS and rebuild
```

---

## 🎉 Recommended: Cloudflare Pages

**Why Cloudflare Pages?**
- ✅ Free unlimited bandwidth
- ✅ Global CDN (fast worldwide)
- ✅ Auto-deploy from GitHub
- ✅ Perfect for Astro
- ✅ Built-in SSL
- ✅ Great performance

**Deploy Now**: https://dash.cloudflare.com/

---

## Need Help?

Run the interactive deployment script:
```bash
cd /Users/loken/Projects/marketingtool-pro
./DEPLOY.sh
```

This will guide you through the deployment process step by step!
