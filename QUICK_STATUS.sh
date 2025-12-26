#!/bin/bash
# Quick Status Check - Run on Server

echo "🔍 Checking marketingtool.pro Status"
echo "===================================="
echo ""

echo "📊 PM2 Status:"
pm2 status
echo ""

echo "🌐 Nginx Status:"
systemctl status nginx --no-pager -l | head -10
echo ""

echo "🔌 Port Check:"
netstat -tulpn | grep -E '3000|3001|80|443' || echo "No matching ports found"
echo ""

echo "🧪 Local App Test:"
echo "Port 3000 (aitool-app):"
curl -s http://localhost:3000 | head -3 || echo "❌ Not responding"
echo ""
echo "Port 3001 (dashboard-app):"
curl -s http://localhost:3001 | head -3 || echo "❌ Not responding"
echo ""

echo "🌍 Domain Test:"
echo "marketingtool.pro:"
curl -I http://marketingtool.pro 2>&1 | head -3 || echo "❌ Not accessible"
echo ""
echo "app.marketingtool.pro:"
curl -I http://app.marketingtool.pro 2>&1 | head -3 || echo "❌ Not accessible"
echo ""

echo "✅ Status check complete!"
