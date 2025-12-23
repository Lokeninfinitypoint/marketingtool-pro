#!/bin/bash

echo "╔══════════════════════════════════════════════════════════════════╗"
echo "║                                                                  ║"
echo "║         🚀 DEPLOY TO VPS (31.220.107.19)                        ║"
echo "║                                                                  ║"
echo "╚══════════════════════════════════════════════════════════════════╝"
echo ""

echo "This will deploy to your VPS server"
echo ""
read -p "Continue? (y/n): " confirm

if [ "$confirm" != "y" ]; then
    echo "Deployment cancelled."
    exit 0
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Deploying to VPS..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# SSH and deploy
ssh root@31.220.107.19 << 'ENDSSH'
cd /var/www/marketingtool.pro || mkdir -p /var/www/marketingtool.pro && cd /var/www/marketingtool.pro

# Clone or pull
if [ -d ".git" ]; then
    echo "✓ Pulling latest from GitHub..."
    git pull origin main
else
    echo "✓ Cloning repository..."
    git clone https://github.com/Lokeninfinitypoint/marketingtool-pro.git .
fi

echo "✓ Installing dependencies..."
npm install

echo "✓ Building project..."
npm run build

echo "✓ Setting permissions..."
chown -R www-data:www-data /var/www/marketingtool.pro

echo ""
echo "╔══════════════════════════════════════════════════════════════════╗"
echo "║                                                                  ║"
echo "║         ✅ DEPLOYED SUCCESSFULLY!                               ║"
echo "║                                                                  ║"
echo "╚══════════════════════════════════════════════════════════════════╝"
echo ""
echo "Your site is now live at: http://31.220.107.19"
echo "Configure domain at: http://marketingtool.pro"
echo ""

ENDSSH

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Deployment complete!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
