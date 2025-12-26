#!/bin/bash
# Complete Status Check - Run on Server

echo "🔍 Complete System Status Check"
echo "=============================="
echo ""

# PM2 Status
echo "📊 PM2 Applications:"
echo "-------------------"
pm2 status
echo ""

# Nginx Status
echo "🌐 Nginx Status:"
echo "---------------"
systemctl status nginx --no-pager | head -10
echo ""

# Port Check
echo "🔌 Port Status:"
echo "---------------"
echo "Port 80:"
netstat -tulpn | grep :80 || echo "  Not listening"
echo ""
echo "Port 443:"
netstat -tulpn | grep :443 || echo "  Not listening"
echo ""
echo "Port 3000:"
netstat -tulpn | grep :3000 || echo "  Not listening"
echo ""
echo "Port 3001:"
netstat -tulpn | grep :3001 || echo "  Not listening"
echo ""

# Test Local Apps
echo "🧪 Testing Local Applications:"
echo "------------------------------"
echo "Port 3000 (aitool-app):"
curl -s -o /dev/null -w "Status: %{http_code}\n" http://localhost:3000 || echo "  ❌ Not responding"
echo ""
echo "Port 3001 (dashboard-app):"
curl -s -o /dev/null -w "Status: %{http_code}\n" http://localhost:3001 || echo "  ❌ Not responding"
echo ""

# Test Domains
echo "🌍 Testing Domains:"
echo "------------------"
echo "http://marketingtool.pro:"
curl -s -o /dev/null -w "Status: %{http_code} | Redirect: %{redirect_url}\n" http://marketingtool.pro || echo "  ❌ Not accessible"
echo ""
echo "http://app.marketingtool.pro:"
curl -s -o /dev/null -w "Status: %{http_code} | Redirect: %{redirect_url}\n" http://app.marketingtool.pro || echo "  ❌ Not accessible"
echo ""

# SSL Check
echo "🔒 SSL Certificate Status:"
echo "---------------------------"
if [ -f "/etc/letsencrypt/live/marketingtool.pro/fullchain.pem" ]; then
    echo "  ✅ SSL certificate exists"
    echo "  Expires: $(openssl x509 -in /etc/letsencrypt/live/marketingtool.pro/fullchain.pem -noout -enddate 2>/dev/null | cut -d= -f2 || echo 'Unknown')"
else
    echo "  ⚠️  SSL certificate not found"
fi
echo ""

# Disk Space
echo "💾 Disk Usage:"
echo "-------------"
df -h / | tail -1
echo ""

# Summary
echo "📋 Summary:"
echo "-----------"
if pm2 list | grep -q "online"; then
    echo "  ✅ Apps: Running"
else
    echo "  ❌ Apps: Not running"
fi

if systemctl is-active --quiet nginx; then
    echo "  ✅ Nginx: Running"
else
    echo "  ❌ Nginx: Not running"
fi

if curl -s -o /dev/null -w "%{http_code}" http://marketingtool.pro | grep -q "200\|301\|302"; then
    echo "  ✅ Domain: Accessible"
else
    echo "  ❌ Domain: Not accessible"
fi

echo ""
echo "✅ Status check complete!"
