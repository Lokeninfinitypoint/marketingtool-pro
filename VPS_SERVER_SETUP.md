# 🖥️ VPS Server Setup Guide

## Server Information

- **IP Address:** 31.220.107.19
- **Username:** root
- **SSH Key:** ~/.ssh/id_rsa
- **Password:** Cloth-vastr@123#
- **Domain:** marketingtool.pro

---

## 🔐 SSH Connection

### Connect to Server:
```bash
ssh -i ~/.ssh/id_rsa root@31.220.107.19
```

### First Time Connection:
```bash
ssh -i ~/.ssh/id_rsa -o StrictHostKeyChecking=no root@31.220.107.19
```

---

## 📋 Initial Server Setup

### 1. Update System
```bash
apt update && apt upgrade -y
```

### 2. Install Required Software
```bash
# Node.js 20.x
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Nginx
apt install -y nginx

# PM2 (Process Manager)
npm install -g pm2

# Certbot (SSL)
apt install -y certbot python3-certbot-nginx

# Git
apt install -y git

# Build tools
apt install -y build-essential
```

### 3. Create App Directory
```bash
mkdir -p /var/www/marketingtool
cd /var/www/marketingtool
```

### 4. Clone/Upload Your Code
```bash
# Option 1: Clone from Git
git clone <your-repo-url> .

# Option 2: Upload via SCP
# Run from local machine:
# scp -i ~/.ssh/id_rsa -r ./aitool-app root@31.220.107.19:/var/www/marketingtool/
```

---

## 🔧 Application Setup

### 1. Install Dependencies
```bash
cd /var/www/marketingtool/aitool-app
npm install

cd /var/www/marketingtool/dashboard-app
npm install
```

### 2. Create Environment Files

**aitool-app/.env.local:**
```env
NEXT_PUBLIC_APPWRITE_ENDPOINT=https://cloud.appwrite.io/v1
NEXT_PUBLIC_APPWRITE_PROJECT_ID=your-project-id
NEXT_PUBLIC_APPWRITE_DATABASE_ID=main
NEXT_PUBLIC_APPWRITE_STORAGE_ID=files

NEXT_PUBLIC_WINDMILL_ENDPOINT=https://app.windmill.dev
NEXT_PUBLIC_WINDMILL_TOKEN=your-token

NODE_ENV=production
PORT=3000
```

**dashboard-app/.env.local:**
```env
NEXT_PUBLIC_APPWRITE_ENDPOINT=https://cloud.appwrite.io/v1
NEXT_PUBLIC_APPWRITE_PROJECT_ID=your-project-id
NEXT_PUBLIC_APPWRITE_DATABASE_ID=main
NEXT_PUBLIC_APPWRITE_STORAGE_ID=files

NEXT_PUBLIC_WINDMILL_ENDPOINT=https://app.windmill.dev
NEXT_PUBLIC_WINDMILL_TOKEN=your-token

NODE_ENV=production
PORT=3001
```

### 3. Build Applications
```bash
cd /var/www/marketingtool/aitool-app
npm run build

cd /var/www/marketingtool/dashboard-app
npm run build
```

---

## 🚀 PM2 Process Management

### Start Applications with PM2

**Create PM2 ecosystem file:** `/var/www/marketingtool/ecosystem.config.js`
```javascript
module.exports = {
  apps: [
    {
      name: 'aitool-app',
      cwd: '/var/www/marketingtool/aitool-app',
      script: 'npm',
      args: 'start',
      env: {
        NODE_ENV: 'production',
        PORT: 3000,
      },
      error_file: '/var/log/pm2/aitool-error.log',
      out_file: '/var/log/pm2/aitool-out.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss Z',
    },
    {
      name: 'dashboard-app',
      cwd: '/var/www/marketingtool/dashboard-app',
      script: 'npm',
      args: 'start',
      env: {
        NODE_ENV: 'production',
        PORT: 3001,
      },
      error_file: '/var/log/pm2/dashboard-error.log',
      out_file: '/var/log/pm2/dashboard-out.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss Z',
    },
  ],
};
```

### PM2 Commands
```bash
# Start apps
pm2 start ecosystem.config.js

# Stop apps
pm2 stop all

# Restart apps
pm2 restart all

# View status
pm2 status

# View logs
pm2 logs

# Save PM2 configuration
pm2 save

# Setup PM2 to start on boot
pm2 startup
```

---

## 🌐 Nginx Configuration

### Create Nginx Config: `/etc/nginx/sites-available/marketingtool.pro`

```nginx
# Main Domain - aitool-app
server {
    listen 80;
    server_name marketingtool.pro www.marketingtool.pro;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}

# App Subdomain - dashboard-app
server {
    listen 80;
    server_name app.marketingtool.pro;

    location / {
        proxy_pass http://localhost:3001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}
```

### Enable Site
```bash
ln -s /etc/nginx/sites-available/marketingtool.pro /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx
```

---

## 🔒 SSL Certificate Setup

### Get SSL Certificate
```bash
certbot --nginx -d marketingtool.pro -d www.marketingtool.pro -d app.marketingtool.pro
```

### Auto-renewal
```bash
certbot renew --dry-run
```

---

## 🔥 Firewall Configuration

```bash
# UFW Firewall
ufw allow 22/tcp    # SSH
ufw allow 80/tcp    # HTTP
ufw allow 443/tcp   # HTTPS
ufw enable
```

---

## 📊 Monitoring & Logs

### View Application Logs
```bash
# PM2 logs
pm2 logs

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# Application logs
tail -f /var/log/pm2/aitool-out.log
tail -f /var/log/pm2/dashboard-out.log
```

---

## 🔄 Deployment Script

Use the provided `DEPLOY_TO_VPS.sh` script:

```bash
chmod +x DEPLOY_TO_VPS.sh
./DEPLOY_TO_VPS.sh
```

---

## ✅ Quick Setup Checklist

- [ ] Connect to server via SSH
- [ ] Update system packages
- [ ] Install Node.js, Nginx, PM2
- [ ] Create app directory
- [ ] Upload/clone application code
- [ ] Install dependencies
- [ ] Create environment files
- [ ] Build applications
- [ ] Configure PM2
- [ ] Configure Nginx
- [ ] Setup SSL certificates
- [ ] Configure firewall
- [ ] Test deployment

---

## 🆘 Troubleshooting

### Application not starting?
```bash
pm2 logs
pm2 restart all
```

### Nginx not working?
```bash
nginx -t
systemctl status nginx
systemctl restart nginx
```

### Port already in use?
```bash
netstat -tulpn | grep :3000
kill -9 <PID>
```

### Permission issues?
```bash
chown -R www-data:www-data /var/www/marketingtool
chmod -R 755 /var/www/marketingtool
```

---

## 🔗 Useful Commands

```bash
# Check server resources
htop
df -h
free -h

# Check running processes
ps aux | grep node

# Check ports
netstat -tulpn

# Restart services
systemctl restart nginx
pm2 restart all
```

---

*Server Setup Complete!*
*Domain: marketingtool.pro*
*IP: 31.220.107.19*
