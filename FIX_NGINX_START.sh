#!/bin/bash
# Fix Nginx Startup Issue - Run on Server

set -e

echo "🔧 Diagnosing Nginx Startup Issue"
echo "=================================="
echo ""

# Check what's using port 80
echo "1️⃣ Checking port 80..."
lsof -i :80 || echo "Port 80 appears free"
netstat -tulpn | grep :80 || echo "Port 80 appears free"

# Check for existing Nginx processes
echo ""
echo "2️⃣ Checking for Nginx processes..."
ps aux | grep nginx | grep -v grep || echo "No Nginx processes found"

# Kill any existing Nginx processes
echo ""
echo "3️⃣ Stopping all Nginx processes..."
systemctl stop nginx 2>/dev/null || true
pkill -9 nginx 2>/dev/null || true
pkill -9 certbot 2>/dev/null || true
sleep 3

# Check if port 80 is still in use
echo ""
echo "4️⃣ Checking port 80 again..."
if lsof -i :80 > /dev/null 2>&1; then
    echo "⚠️ Port 80 is still in use:"
    lsof -i :80
    echo ""
    echo "Killing processes on port 80..."
    fuser -k 80/tcp 2>/dev/null || true
    sleep 2
fi

# Check Nginx error log
echo ""
echo "5️⃣ Checking Nginx error log..."
tail -20 /var/log/nginx/error.log 2>/dev/null || echo "No error log found"

# Try starting Nginx in foreground to see errors
echo ""
echo "6️⃣ Testing Nginx start..."
nginx -t

# Start Nginx
echo ""
echo "7️⃣ Starting Nginx..."
systemctl start nginx || {
    echo "❌ Failed to start, trying direct start..."
    nginx -g "daemon off;" &
    sleep 2
    if ps aux | grep nginx | grep -v grep; then
        echo "✅ Nginx started directly"
        pkill nginx
        systemctl start nginx
    else
        echo "❌ Still failing, checking logs..."
        journalctl -xeu nginx.service --no-pager | tail -20
    }
}

# Check status
echo ""
echo "8️⃣ Checking Nginx status..."
systemctl status nginx --no-pager | head -15

# Final check
echo ""
if systemctl is-active --quiet nginx; then
    echo "✅ Nginx is running!"
    echo ""
    echo "Test: curl http://localhost"
else
    echo "❌ Nginx failed to start"
    echo ""
    echo "Check logs: journalctl -xeu nginx.service"
fi
