#!/bin/bash
# Test Domain Accessibility

echo "🧪 Testing Domain Accessibility"
echo "==============================="
echo ""

echo "1️⃣ Testing local apps:"
echo "Port 3000:"
curl -s -o /dev/null -w "  Status: %{http_code}\n" http://localhost:3000 --max-time 5
echo ""
echo "Port 3001:"
curl -s -o /dev/null -w "  Status: %{http_code}\n" http://localhost:3001 --max-time 5
echo ""

echo "2️⃣ Testing via Nginx (localhost):"
echo "marketingtool.pro via localhost:"
curl -s -o /dev/null -w "  Status: %{http_code}\n" -H "Host: marketingtool.pro" http://localhost --max-time 5
echo ""
echo "app.marketingtool.pro via localhost:"
curl -s -o /dev/null -w "  Status: %{http_code}\n" -H "Host: app.marketingtool.pro" http://localhost --max-time 5
echo ""

echo "3️⃣ Testing external domains:"
echo "marketingtool.pro:"
curl -s -o /dev/null -w "  Status: %{http_code} | Time: %{time_total}s\n" http://marketingtool.pro --max-time 10 || echo "  ❌ Timeout or error"
echo ""
echo "app.marketingtool.pro:"
curl -s -o /dev/null -w "  Status: %{http_code} | Time: %{time_total}s\n" http://app.marketingtool.pro --max-time 10 || echo "  ❌ Timeout or error"
echo ""

echo "4️⃣ DNS Check:"
echo "marketingtool.pro resolves to:"
nslookup marketingtool.pro | grep -A 2 "Name:" || dig +short marketingtool.pro || echo "  DNS check failed"
echo ""

echo "5️⃣ Nginx Access Log (last 5 lines):"
tail -5 /var/log/nginx/access.log 2>/dev/null || echo "  No access log yet"
echo ""

echo "✅ Test Complete!"
