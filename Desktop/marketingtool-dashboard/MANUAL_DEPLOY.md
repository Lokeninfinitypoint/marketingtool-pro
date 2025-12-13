# 🚀 Manual Deployment Instructions

## Quick Deploy - Copy & Paste These Commands

### Step 1: Connect to Your Server
```bash
ssh root@31.220.107.19
# Password: Cloth-vastr@123#
```

### Step 2: Deploy Dashboard (Copy ALL commands below)
```bash
# Navigate to web directory
cd /var/www

# Clone repository (if not exists)
if [ ! -d "marketingtool-dashboard" ]; then
    git clone https://github.com/Lokeninfinitypoint/marketingtool-pro.git marketingtool-dashboard
fi

# Enter directory
cd marketingtool-dashboard

# Pull latest changes
git pull origin main

# Install dependencies
npm install

# Create environment file
cat > .env.production << 'EOF'
DATABASE_URL="file:./prisma/dev.db"
NEXTAUTH_SECRET="marketingtool-pro-super-secret-key-2025"
NEXTAUTH_URL="https://app.marketingtool.pro"
OPENAI_API_KEY="your-openai-key"
ANTHROPIC_API_KEY="your-anthropic-key"
NODE_ENV="production"
PORT=3000
EOF

# Generate Prisma Client
npx prisma generate

# Build application
npm run build

# Install PM2 globally (if not installed)
npm list -g pm2 || npm install -g pm2

# Stop old instance if running
pm2 stop marketingtool-dashboard 2>/dev/null || true
pm2 delete marketingtool-dashboard 2>/dev/null || true

# Start application
pm2 start npm --name "marketingtool-dashboard" -- start

# Save PM2 configuration
pm2 save

# Setup PM2 startup
pm2 startup

# Show status
pm2 status
pm2 logs marketingtool-dashboard --lines 50
```

### Step 3: Configure Nginx
```bash
# Create Nginx configuration
cat > /etc/nginx/sites-available/app.marketingtool.pro << 'EOF'
server {
    listen 80;
    server_name app.marketingtool.pro;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }

    client_max_body_size 10M;
    access_log /var/log/nginx/app.marketingtool.pro.access.log;
    error_log /var/log/nginx/app.marketingtool.pro.error.log;
}
EOF

# Enable site
ln -sf /etc/nginx/sites-available/app.marketingtool.pro /etc/nginx/sites-enabled/

# Test and reload Nginx
nginx -t && systemctl reload nginx
```

### Step 4: Setup SSL
```bash
# Install Certbot (if not installed)
apt-get update
apt-get install -y certbot python3-certbot-nginx

# Get SSL certificate
certbot --nginx -d app.marketingtool.pro --non-interactive --agree-tos --email your-email@example.com
```

### Step 5: Verify Deployment
```bash
# Check PM2 status
pm2 status

# Check logs
pm2 logs marketingtool-dashboard --lines 20

# Test locally
curl http://localhost:3000

# Test public URL
curl https://app.marketingtool.pro
```

---

## ✅ Verification

After deployment, check:

1. **Dashboard**: https://app.marketingtool.pro
2. **Tools**: https://app.marketingtool.pro/tools
3. **Help Center**: https://app.marketingtool.pro/help
4. **AI Features**: https://app.marketingtool.pro/ai/content-generator

---

## 🔄 Quick Update Commands

To update in the future:
```bash
cd /var/www/marketingtool-dashboard
git pull origin main
npm install
npm run build
pm2 restart marketingtool-dashboard
```

---

## 📊 Monitoring

```bash
# View logs
pm2 logs marketingtool-dashboard

# Monitor resources
pm2 monit

# Check status
pm2 status

# Restart if needed
pm2 restart marketingtool-dashboard
```

---

## 🚨 Troubleshooting

### If port 3000 is in use:
```bash
lsof -ti:3000 | xargs kill -9
pm2 restart marketingtool-dashboard
```

### If build fails:
```bash
rm -rf .next node_modules
npm install
npm run build
```

### If Prisma issues:
```bash
npx prisma generate
npx prisma migrate deploy
```

---

**Server**: 31.220.107.19
**Password**: Cloth-vastr@123#
**Dashboard**: https://app.marketingtool.pro

**🚀 Ready to deploy!**
