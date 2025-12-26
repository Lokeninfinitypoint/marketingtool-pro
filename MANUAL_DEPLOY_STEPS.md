# 📋 Manual Deployment Steps

Since automated deployment needs SSH key setup, here are manual steps:

## 🔐 Connect to Server

```bash
ssh root@31.220.107.19
# Password: Cloth-vastr@123#
```

## 📦 Step 1: Install Required Software

```bash
# Update system
apt update && apt upgrade -y

# Install Node.js 20.x
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

# Install PM2
npm install -g pm2

# Install Nginx
apt install -y nginx

# Install Certbot
apt install -y certbot python3-certbot-nginx

# Create directories
mkdir -p /var/www/marketingtool/aitool-app
mkdir -p /var/www/marketingtool/dashboard-app
mkdir -p /var/log/pm2
```

## 📤 Step 2: Upload Files

From your local machine:

```bash
# Upload aitool-app
scp -r ./aitool-app root@31.220.107.19:/var/www/marketingtool/

# Upload dashboard-app
scp -r ./dashboard-app root@31.220.107.19:/var/www/marketingtool/

# Upload configs
scp ./pm2-ecosystem.config.js root@31.220.107.19:/var/www/marketingtool/
scp ./nginx-marketingtool.conf root@31.220.107.19:/tmp/
```

## 🔨 Step 3: Build Applications

On server:

```bash
cd /var/www/marketingtool/aitool-app
npm install
npm run build

cd /var/www/marketingtool/dashboard-app
npm install
npm run build
```

## ⚙️ Step 4: Setup PM2

```bash
cd /var/www/marketingtool
pm2 start ecosystem.config.js
pm2 save
pm2 startup
# Run the command it outputs
```

## 🌐 Step 5: Setup Nginx

```bash
cp /tmp/nginx-marketingtool.conf /etc/nginx/sites-available/marketingtool.pro
ln -s /etc/nginx/sites-available/marketingtool.pro /etc/nginx/sites-enabled/
rm /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx
```

## 🔒 Step 6: Setup SSL

```bash
certbot --nginx -d marketingtool.pro -d www.marketingtool.pro -d app.marketingtool.pro
```

## ✅ Verify

```bash
# Check PM2
pm2 status

# Check Nginx
systemctl status nginx

# Check apps
curl http://localhost:3000
curl http://localhost:3001
```

## 🎉 Done!

Your apps should now be live at:
- https://marketingtool.pro
- https://app.marketingtool.pro
