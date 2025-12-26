# 🚀 Quick Deployment Guide

## Server Info
- **IP:** 31.220.107.19
- **User:** root
- **SSH Key:** ~/.ssh/id_rsa
- **Domain:** marketingtool.pro

---

## ⚡ Fastest Way to Deploy

### Option 1: Automated Script (Recommended)

```bash
# 1. Quick server setup
./QUICK_DEPLOY.sh

# 2. Deploy applications
./DEPLOY_TO_VPS.sh
```

### Option 2: Manual Steps

```bash
# 1. Connect to server
ssh -i ~/.ssh/id_rsa root@31.220.107.19

# 2. Run setup commands (see VPS_SERVER_SETUP.md)

# 3. Upload files
scp -i ~/.ssh/id_rsa -r ./aitool-app root@31.220.107.19:/var/www/marketingtool/
scp -i ~/.ssh/id_rsa -r ./dashboard-app root@31.220.107.19:/var/www/marketingtool/

# 4. On server: Install, build, start
cd /var/www/marketingtool/aitool-app && npm install && npm run build
cd /var/www/marketingtool/dashboard-app && npm install && npm run build
pm2 start ecosystem.config.js
```

---

## 📋 One-Command Setup (Copy & Paste)

```bash
ssh -i ~/.ssh/id_rsa root@31.220.107.19 << 'ENDSSH'
# Update system
apt update -qq && apt upgrade -y

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt install -y nodejs

# Install PM2
npm install -g pm2

# Install Nginx
apt install -y nginx

# Create directories
mkdir -p /var/www/marketingtool /var/log/pm2

# Install Certbot
apt install -y certbot python3-certbot-nginx

echo "✅ Server ready for deployment!"
ENDSSH
```

---

## 🔧 After Deployment

### 1. Configure Nginx
```bash
# Copy nginx config
scp -i ~/.ssh/id_rsa nginx-marketingtool.conf root@31.220.107.19:/etc/nginx/sites-available/marketingtool.pro

# On server
ssh -i ~/.ssh/id_rsa root@31.220.107.19
ln -s /etc/nginx/sites-available/marketingtool.pro /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
```

### 2. Setup PM2
```bash
# Copy PM2 config
scp -i ~/.ssh/id_rsa pm2-ecosystem.config.js root@31.220.107.19:/var/www/marketingtool/ecosystem.config.js

# On server
pm2 start ecosystem.config.js
pm2 save
pm2 startup
```

### 3. Setup SSL
```bash
certbot --nginx -d marketingtool.pro -d www.marketingtool.pro -d app.marketingtool.pro
```

---

## ✅ Verify Deployment

```bash
# Check PM2
pm2 status

# Check Nginx
systemctl status nginx

# Check apps
curl http://localhost:3000
curl http://localhost:3001

# Check domain
curl http://marketingtool.pro
```

---

## 🆘 Common Issues

**Port already in use?**
```bash
pm2 stop all
lsof -ti:3000 | xargs kill -9
lsof -ti:3001 | xargs kill -9
pm2 start ecosystem.config.js
```

**Nginx not working?**
```bash
nginx -t
systemctl restart nginx
tail -f /var/log/nginx/error.log
```

**App not starting?**
```bash
pm2 logs
cd /var/www/marketingtool/aitool-app && npm run build
```

---

*Ready to deploy! 🚀*
