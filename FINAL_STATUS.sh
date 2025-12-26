#!/bin/bash
# Final Status Check

echo "🎉 DEPLOYMENT STATUS"
echo "==================="
echo ""

echo "📊 PM2 Applications:"
pm2 status
echo ""

echo "🌐 Nginx Status:"
systemctl status nginx --no-pager | head -3
echo ""

echo "🧪 Testing Sites:"
echo "marketingtool.pro:"
curl -s -o /dev/null -w "  HTTP Status: %{http_code}\n" http://marketingtool.pro
echo ""
echo "app.marketingtool.pro:"
curl -s -o /dev/null -w "  HTTP Status: %{http_code}\n" http://app.marketingtool.pro
echo ""

echo "✅ Deployment Complete!"
echo ""
echo "🌐 Your sites:"
echo "   http://marketingtool.pro"
echo "   http://app.marketingtool.pro"
echo ""
echo "SSL can be configured later if needed."
