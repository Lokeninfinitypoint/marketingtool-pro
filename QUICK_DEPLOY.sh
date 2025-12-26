#!/bin/bash

# Quick Deploy Script
# Connects to VPS and sets up everything

VPS_IP="31.220.107.19"
VPS_USER="root"
SSH_KEY="$HOME/.ssh/id_rsa"
DOMAIN="marketingtool.pro"

echo "🚀 Quick Deploy to $DOMAIN"
echo "================================"
echo ""

# Step 1: Test connection
echo "1️⃣ Testing SSH connection..."
ssh -i "$SSH_KEY" -o ConnectTimeout=10 "$VPS_USER@$VPS_IP" "echo '✅ Connected!'" || {
    echo "❌ Cannot connect to server"
    echo "Try: ssh -i $SSH_KEY $VPS_USER@$VPS_IP"
    exit 1
}

# Step 2: Run setup commands on server
echo ""
echo "2️⃣ Setting up server..."
ssh -i "$SSH_KEY" "$VPS_USER@$VPS_IP" << 'ENDSSH'
set -e

echo "📦 Updating system..."
apt update -qq

echo "🔧 Installing Node.js..."
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt install -y nodejs
fi

echo "📦 Installing PM2..."
npm install -g pm2 || true

echo "🌐 Installing Nginx..."
apt install -y nginx || true

echo "🔒 Installing Certbot..."
apt install -y certbot python3-certbot-nginx || true

echo "📁 Creating app directory..."
mkdir -p /var/www/marketingtool
mkdir -p /var/log/pm2

echo "✅ Server setup complete!"
ENDSSH

echo ""
echo "3️⃣ Ready to deploy!"
echo ""
echo "Next steps:"
echo "1. Run: ./DEPLOY_TO_VPS.sh"
echo "2. Or manually upload files and configure"
echo ""
echo "📖 See VPS_SERVER_SETUP.md for detailed instructions"
