#!/bin/bash

# Deploy to VPS Script
# Server: 31.220.107.19
# Domain: marketingtool.pro
# SSH Key: ~/.ssh/id_rsa

set -e

VPS_IP="31.220.107.19"
VPS_USER="root"
SSH_KEY="$HOME/.ssh/id_rsa"
DOMAIN="marketingtool.pro"
APP_DIR="/var/www/marketingtool"

echo "🚀 Starting deployment to VPS..."
echo "Server: $VPS_IP"
echo "Domain: $DOMAIN"
echo ""

# Check SSH key exists
if [ ! -f "$SSH_KEY" ]; then
    echo "❌ SSH key not found at $SSH_KEY"
    exit 1
fi

# Test SSH connection
echo "📡 Testing SSH connection..."
ssh -i "$SSH_KEY" -o StrictHostKeyChecking=no "$VPS_USER@$VPS_IP" "echo 'SSH connection successful!'" || {
    echo "❌ SSH connection failed"
    exit 1
}

echo "✅ SSH connection successful!"
echo ""

# Create app directory on server
echo "📁 Creating app directory..."
ssh -i "$SSH_KEY" "$VPS_USER@$VPS_IP" "mkdir -p $APP_DIR" || true

# Copy files to server
echo "📤 Copying files to server..."
rsync -avz --exclude 'node_modules' --exclude '.next' --exclude '.git' \
    -e "ssh -i $SSH_KEY" \
    ./aitool-app/ "$VPS_USER@$VPS_IP:$APP_DIR/aitool-app/"

rsync -avz --exclude 'node_modules' --exclude '.next' --exclude '.git' \
    -e "ssh -i $SSH_KEY" \
    ./dashboard-app/ "$VPS_USER@$VPS_IP:$APP_DIR/dashboard-app/"

# Install dependencies and build on server
echo "🔨 Installing dependencies and building..."
ssh -i "$SSH_KEY" "$VPS_USER@$VPS_IP" << 'ENDSSH'
cd /var/www/marketingtool/aitool-app
npm install
npm run build

cd /var/www/marketingtool/dashboard-app
npm install
npm run build
ENDSSH

echo "✅ Deployment complete!"
echo ""
echo "🌐 Your apps should be available at:"
echo "   - https://$DOMAIN (aitool-app)"
echo "   - https://app.$DOMAIN (dashboard-app)"
