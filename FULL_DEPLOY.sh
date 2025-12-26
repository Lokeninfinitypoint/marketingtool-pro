#!/bin/bash

# Full Deployment Script
# Deploys both apps to VPS with all configurations

set -e

VPS_IP="31.220.107.19"
VPS_USER="root"
SSH_KEY="$HOME/.ssh/id_rsa"
DOMAIN="marketingtool.pro"
APP_DIR="/var/www/marketingtool"

echo "🚀 Starting Full Deployment"
echo "================================"
echo "Server: $VPS_IP"
echo "Domain: $DOMAIN"
echo ""

# Test SSH connection
echo "📡 Testing SSH connection..."
ssh -i "$SSH_KEY" -o ConnectTimeout=10 -o StrictHostKeyChecking=no "$VPS_USER@$VPS_IP" "echo '✅ Connected!'" || {
    echo "❌ Cannot connect to server"
    exit 1
}

# Deploy everything
echo ""
echo "🔧 Deploying to server..."
ssh -i "$SSH_KEY" "$VPS_USER@$VPS_IP" << ENDSSH
set -e

echo "📦 Updating system..."
export DEBIAN_FRONTEND=noninteractive
apt update -qq

echo "🔧 Installing Node.js..."
if ! command -v node &> /dev/null || [ "\$(node -v | cut -d'v' -f2 | cut -d'.' -f1)" -lt "20" ]; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt install -y nodejs
fi
echo "✅ Node.js \$(node -v)"

echo "📦 Installing PM2..."
npm install -g pm2 || true

echo "🌐 Installing Nginx..."
apt install -y nginx || true

echo "🔒 Installing Certbot..."
apt install -y certbot python3-certbot-nginx || true

echo "📁 Creating directories..."
mkdir -p $APP_DIR/aitool-app
mkdir -p $APP_DIR/dashboard-app
mkdir -p /var/log/pm2

echo "✅ Server setup complete!"
ENDSSH

# Copy application files
echo ""
echo "📤 Copying application files..."
rsync -avz --delete \
    --exclude 'node_modules' \
    --exclude '.next' \
    --exclude '.git' \
    --exclude '.env.local' \
    -e "ssh -i $SSH_KEY -o StrictHostKeyChecking=no" \
    ./aitool-app/ "$VPS_USER@$VPS_IP:$APP_DIR/aitool-app/"

rsync -avz --delete \
    --exclude 'node_modules' \
    --exclude '.next' \
    --exclude '.git' \
    --exclude '.env.local' \
    -e "ssh -i $SSH_KEY -o StrictHostKeyChecking=no" \
    ./dashboard-app/ "$VPS_USER@$VPS_IP:$APP_DIR/dashboard-app/"

# Copy configuration files
echo ""
echo "📋 Copying configuration files..."
scp -i "$SSH_KEY" -o StrictHostKeyChecking=no \
    ./pm2-ecosystem.config.js \
    "$VPS_USER@$VPS_IP:$APP_DIR/ecosystem.config.js"

scp -i "$SSH_KEY" -o StrictHostKeyChecking=no \
    ./nginx-marketingtool.conf \
    "$VPS_USER@$VPS_IP:/tmp/nginx-marketingtool.conf"

# Install, build, and configure on server
echo ""
echo "🔨 Installing dependencies and building..."
ssh -i "$SSH_KEY" "$VPS_USER@$VPS_IP" << ENDSSH
set -e

cd $APP_DIR/aitool-app
echo "📦 Installing aitool-app dependencies..."
npm install --production=false

echo "🔨 Building aitool-app..."
npm run build

cd $APP_DIR/dashboard-app
echo "📦 Installing dashboard-app dependencies..."
npm install --production=false

echo "🔨 Building dashboard-app..."
npm run build

echo "✅ Builds complete!"
ENDSSH

# Setup PM2
echo ""
echo "⚙️ Configuring PM2..."
ssh -i "$SSH_KEY" "$VPS_USER@$VPS_IP" << ENDSSH
set -e

cd $APP_DIR

# Stop existing processes
pm2 stop all || true
pm2 delete all || true

# Start applications
pm2 start ecosystem.config.js

# Save PM2 configuration
pm2 save

# Setup PM2 startup
pm2 startup | grep -v PM2_HOME | bash || true

echo "✅ PM2 configured!"
ENDSSH

# Setup Nginx
echo ""
echo "🌐 Configuring Nginx..."
ssh -i "$SSH_KEY" "$VPS_USER@$VPS_IP" << ENDSSH
set -e

# Copy nginx config
cp /tmp/nginx-marketingtool.conf /etc/nginx/sites-available/$DOMAIN

# Enable site
ln -sf /etc/nginx/sites-available/$DOMAIN /etc/nginx/sites-enabled/

# Remove default site
rm -f /etc/nginx/sites-enabled/default

# Test nginx config
nginx -t

# Restart nginx
systemctl restart nginx

echo "✅ Nginx configured!"
ENDSSH

# Setup Firewall
echo ""
echo "🔥 Configuring firewall..."
ssh -i "$SSH_KEY" "$VPS_USER@$VPS_IP" << ENDSSH
set -e

# Allow ports
ufw allow 22/tcp || true
ufw allow 80/tcp || true
ufw allow 443/tcp || true
ufw --force enable || true

echo "✅ Firewall configured!"
ENDSSH

# Setup SSL (optional - uncomment if DNS is ready)
echo ""
echo "🔒 Setting up SSL certificate..."
ssh -i "$SSH_KEY" "$VPS_USER@$VPS_IP" << ENDSSH
set -e

# Check if certificate already exists
if [ ! -f "/etc/letsencrypt/live/$DOMAIN/fullchain.pem" ]; then
    echo "📜 Requesting SSL certificate..."
    certbot --nginx -d $DOMAIN -d www.$DOMAIN -d app.$DOMAIN --non-interactive --agree-tos --email admin@$DOMAIN || {
        echo "⚠️ SSL setup failed (DNS might need time to propagate)"
    }
else
    echo "✅ SSL certificate already exists"
fi
ENDSSH

echo ""
echo "✅ Deployment Complete!"
echo ""
echo "🌐 Your applications are now live:"
echo "   - https://$DOMAIN (aitool-app)"
echo "   - https://app.$DOMAIN (dashboard-app)"
echo ""
echo "📊 Check status:"
echo "   ssh -i $SSH_KEY $VPS_USER@$VPS_IP 'pm2 status'"
echo ""
echo "📝 View logs:"
echo "   ssh -i $SSH_KEY $VPS_USER@$VPS_IP 'pm2 logs'"
