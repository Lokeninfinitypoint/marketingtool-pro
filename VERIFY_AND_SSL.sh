#!/bin/bash
# Verify Everything Works & Setup SSL

echo "✅ Nginx is Running!"
echo "==================="
echo ""

# Check PM2 apps
echo "1️⃣ Checking PM2 apps..."
pm2 status
echo ""

# Test local apps
echo "2️⃣ Testing local apps..."
echo "Port 3000 (aitool-app):"
curl -s http://localhost:3000 | head -5 || echo "❌ Not responding"
echo ""
echo "Port 3001 (dashboard-app):"
curl -s http://localhost:3001 | head -5 || echo "❌ Not responding"
echo ""

# Test domains via HTTP
echo "3️⃣ Testing domains..."
echo "marketingtool.pro:"
curl -I http://marketingtool.pro 2>&1 | head -3 || echo "❌ Not accessible"
echo ""
echo "app.marketingtool.pro:"
curl -I http://app.marketingtool.pro 2>&1 | head -3 || echo "❌ Not accessible"
echo ""

# Setup SSL
echo "4️⃣ Setting up SSL certificate..."
certbot --nginx \
    -d marketingtool.pro \
    -d www.marketingtool.pro \
    -d app.marketingtool.pro \
    --non-interactive \
    --agree-tos \
    --email admin@marketingtool.pro \
    --redirect || {
    echo "⚠️ SSL setup had issues, but HTTP should work"
}

echo ""
echo "5️⃣ Testing HTTPS..."
curl -I https://marketingtool.pro 2>&1 | head -3 || echo "HTTPS not ready yet"
echo ""

echo "✅ Verification Complete!"
echo ""
echo "🌐 Your sites:"
echo "   HTTP:  http://marketingtool.pro"
echo "   HTTP:  http://app.marketingtool.pro"
echo "   HTTPS: https://marketingtool.pro (if SSL worked)"
echo "   HTTPS: https://app.marketingtool.pro (if SSL worked)"
