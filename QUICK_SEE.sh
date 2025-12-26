#!/bin/bash
# Quick Status - Copy & Paste This

echo "=== PM2 ===" && pm2 status && \
echo "" && \
echo "=== Nginx ===" && systemctl status nginx --no-pager | head -5 && \
echo "" && \
echo "=== Ports ===" && netstat -tulpn | grep -E '80|443|3000|3001' && \
echo "" && \
echo "=== Test Apps ===" && \
curl -s -o /dev/null -w "Port 3000: %{http_code}\n" http://localhost:3000 && \
curl -s -o /dev/null -w "Port 3001: %{http_code}\n" http://localhost:3001 && \
echo "" && \
echo "=== Test Domains ===" && \
curl -s -o /dev/null -w "marketingtool.pro: %{http_code}\n" http://marketingtool.pro && \
curl -s -o /dev/null -w "app.marketingtool.pro: %{http_code}\n" http://app.marketingtool.pro
