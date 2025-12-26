# 🚀 Deployment Instructions - READY TO DEPLOY

## ✅ DNS Already Configured - Ready to Deploy!

### Server Info:
- **IP:** 31.220.107.19
- **User:** root  
- **Password:** Cloth-vastr@123#
- **Domain:** marketingtool.pro

---

## 🎯 Quick Deploy Options

### Option 1: Automated (if sshpass installed)

```bash
# Install sshpass first
sudo apt-get install -y sshpass

# Run deployment
./DEPLOY_WITH_PASSWORD.sh
```

### Option 2: Manual Deployment (Recommended)

**Step 1: Connect to server**
```bash
ssh root@31.220.107.19
# Password: Cloth-vastr@123#
```

**Step 2: Run setup script on server**
```bash
# Copy and paste this entire block:

# Update & Install
apt update && apt upgrade -y
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs nginx certbot python3-certbot-nginx
npm install -g pm2

# Create directories
mkdir -p /var/www/marketingtool/{aitool-app,dashboard-app} /var/log/pm2
```

**Step 3: Upload files from your local machine**

Open a NEW terminal (keep SSH session open) and run:

```bash
# Upload aitool-app
scp -r ./aitool-app root@31.220.107.19:/var/www/marketingtool/

# Upload dashboard-app  
scp -r ./dashboard-app root@31.220.107.19:/var/www/marketingtool/

# Upload configs
scp ./pm2-ecosystem.config.js root@31.220.107.19:/var/www/marketingtool/
scp ./nginx-marketingtool.conf root@31.220.107.19:/tmp/
```

**Step 4: Back on server - Build & Configure**

```bash
# Build aitool-app
cd /var/www/marketingtool/aitool-app
npm install
npm run build

# Build dashboard-app
cd /var/www/marketingtool/dashboard-app
npm install
npm run build

# Setup PM2
cd /var/www/marketingtool
pm2 start ecosystem.config.js
pm2 save
pm2 startup
# Copy and run the command PM2 outputs

# Setup Nginx
cp /tmp/nginx-marketingtool.conf /etc/nginx/sites-available/marketingtool.pro
ln -s /etc/nginx/sites-available/marketingtool.pro /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx

# Setup SSL
certbot --nginx -d marketingtool.pro -d www.marketingtool.pro -d app.marketingtool.pro --non-interactive --agree-tos --email admin@marketingtool.pro

# Firewall
ufw allow 22/tcp
ufw allow 80/tcp
ufw allow 443/tcp
ufw --force enable
```

**Step 5: Verify**

```bash
pm2 status
curl http://localhost:3000
curl http://localhost:3001
```

---

## ✅ After Deployment

Your apps will be live at:
- **https://marketingtool.pro** (aitool-app)
- **https://app.marketingtool.pro** (dashboard-app)

---

## 🔧 Useful Commands

```bash
# View logs
pm2 logs

# Restart apps
pm2 restart all

# Check Nginx
systemctl status nginx
nginx -t

# View app status
pm2 status
```

---

## 📋 All Files Ready

✅ Application code ready
✅ PM2 config ready  
✅ Nginx config ready
✅ Deployment scripts ready
✅ DNS configured

**Just follow the steps above to deploy!** 🚀
