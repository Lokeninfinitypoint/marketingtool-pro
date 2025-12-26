#!/bin/bash

# Deployment Script with Password Authentication
# Uses sshpass for password-based deployment

set -e

VPS_IP="31.220.107.19"
VPS_USER="root"
VPS_PASSWORD="Cloth-vastr@123#"
DOMAIN="marketingtool.pro"
APP_DIR="/var/www/marketingtool"

# Check if sshpass is installed
if ! command -v sshpass &> /dev/null; then
    echo "📦 Installing sshpass..."
    sudo apt-get update -qq
    sudo apt-get install -y sshpass
fi

echo "🚀 Starting Deployment with Password Auth"
echo "=========================================="
echo "Server: $VPS_IP"
echo "Domain: $DOMAIN"
echo ""

# Test connection
echo "📡 Testing SSH connection..."
sshpass -p "$VPS_PASSWORD" ssh -o StrictHostKeyChecking=no "$VPS_USER@$VPS_IP" "echo '✅ Connected!'" || {
    echo "❌ Cannot connect to server"
    exit 1
}

# Setup server
echo ""
echo "🔧 Setting up server..."
sshpass -p "$VPS_PASSWORD" ssh "$VPS_USER@$VPS_IP" << 'ENDSSH'
set -e

echo "📦 Updating system..."
export DEBIAN_FRONTEND=noninteractive
apt update -qq

echo "🔧 Installing Node.js..."
if ! command -v node &> /dev/null || [ "$(node -v | cut -d'v' -f2 | cut -d'.' -f1)" -lt "20" ]; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt install -y nodejs
fi
echo "✅ Node.js $(node -v)"

echo "📦 Installing PM2..."
npm install -g pm2 || true

echo "🌐 Installing Nginx..."
apt install -y nginx || true

echo "🔒 Installing Certbot..."
apt install -y certbot python3-certbot-nginx || true

echo "📁 Creating directories..."
mkdir -p /var/www/marketingtool/aitool-app
mkdir -p /var/www/marketingtool/dashboard-app
mkdir -p /var/log/pm2

echo "✅ Server setup complete!"
ENDSSH

# Copy files
echo ""
echo "📤 Copying application files..."
sshpass -p "$VPS_PASSWORD" rsync -avz --delete \
    --exclude 'node_modules' \
    --exclude '.next' \
    --exclude '.git' \
    --exclude '.env.local' \
    -e "ssh -o StrictHostKeyChecking=no" \
    ./aitool-app/ "$VPS_USER@$VPS_IP:/var/www/marketingtool/aitool-app/"

sshpass -p "$VPS_PASSWORD" rsync -avz --delete \
    --exclude 'node_modules' \
    --exclude '.next' \
    --exclude '.git' \
    --exclude '.env.local' \
    -e "ssh -o StrictHostKeyChecking=no" \
    ./dashboard-app/ "$VPS_USER@$VPS_IP:/var/www/marketingtool/dashboard-app/"

# Copy configs
echo ""
echo "📋 Copying configuration files..."
sshpass -p "$VPS_PASSWORD" scp -o StrictHostKeyChecking=no \
    ./pm2-ecosystem.config.js \
    "$VPS_USER@$VPS_IP:/var/www/marketingtool/ecosystem.config.js"

sshpass -p "$VPS_PASSWORD" scp -o StrictHostKeyChecking=no \
    ./nginx-marketingtool.conf \
    "$VPS_USER@$VPS_IP:/tmp/nginx-marketingtool.conf"

# Build apps
echo ""
echo "🔨 Building applications..."
sshpass -p "$VPS_PASSWORD" ssh "$VPS_USER@$VPS_IP" << 'ENDSSH'
set -e

cd /var/www/marketingtool/aitool-app
echo "📦 Installing aitool-app dependencies..."
npm install --production=false

echo "🔨 Building aitool-app..."
npm run build

cd /var/www/marketingtool/dashboard-app
echo "📦 Installing dashboard-app dependencies..."
npm install --production=false

echo "🔨 Building dashboard-app..."
npm run build

echo "✅ Builds complete!"
ENDSSH

# Setup PM2
echo ""
echo "⚙️ Configuring PM2..."
sshpass -p "$VPS_PASSWORD" ssh "$VPS_USER@$VPS_IP" << 'ENDSSH'
set -e

cd /var/www/marketingtool

# Stop existing
pm2 stop all || true
pm2 delete all || true

# Start apps
pm2 start ecosystem.config.js

# Save and setup startup
pm2 save
pm2 startup | grep -v PM2_HOME | bash || true

echo "✅ PM2 configured!"
ENDSSH

# Setup Nginx
echo ""
echo "🌐 Configuring Nginx..."
sshpass -p "$VPS_PASSWORD" ssh "$VPS_USER@$VPS_IP" << 'ENDSSH'
set -e

cp /tmp/nginx-marketingtool.conf /etc/nginx/sites-available/marketingtool.pro
ln -sf /etc/nginx/sites-available/marketingtool.pro /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx

echo "✅ Nginx configured!"
ENDSSH

# Setup Firewall
echo ""
echo "🔥 Configuring firewall..."
sshpass -p "$VPS_PASSWORD" ssh "$VPS_USER@$VPS_IP" << 'ENDSSH'
set -e
ufw allow 22/tcp || true
ufw allow 80/tcp || true
ufw allow 443/tcp || true
ufw --force enable || true
echo "✅ Firewall configured!"
ENDSSH

# Setup SSL
echo ""
echo "🔒 Setting up SSL..."
sshpass -p "$VPS_PASSWORD" ssh "$VPS_USER@$VPS_IP" << 'ENDSSH'
set -e

if [ ! -f "/etc/letsencrypt/live/marketingtool.pro/fullchain.pem" ]; then
    echo "📜 Requesting SSL certificate..."
    certbot --nginx -d marketingtool.pro -d www.marketingtool.pro -d app.marketingtool.pro \
        --non-interactive --agree-tos --email admin@marketingtool.pro || {
        echo "⚠️ SSL setup failed - run manually later"
    }
else
    echo "✅ SSL certificate exists"
fi
ENDSSH

echo ""
echo "✅ Deployment Complete!"
echo ""
echo "🌐 Applications live at:"
echo "   - http://marketingtool.pro (aitool-app)"
echo "   - http://app.marketingtool.pro (dashboard-app)"
echo ""
echo "Check status: ssh root@$VPS_IP 'pm2 status'"
