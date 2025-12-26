#!/bin/bash
# Fix Nginx and SSL Issues - Run on Server

set -e

echo "🔧 Fixing Nginx and SSL Issues"
echo "=============================="
echo ""

# Step 1: Stop Nginx properly
echo "1️⃣ Stopping Nginx..."
systemctl stop nginx || true
pkill -9 nginx || true
sleep 2

# Step 2: Check what's using port 80
echo ""
echo "2️⃣ Checking what's using port 80..."
lsof -i :80 || echo "Port 80 is free"
netstat -tulpn | grep :80 || echo "Port 80 is free"

# Step 3: Create HTTP-only config first (no SSL)
echo ""
echo "3️⃣ Creating HTTP-only Nginx config..."
cat > /etc/nginx/sites-available/marketingtool.pro << 'NGINXEOF'
# HTTP - Main Domain
server {
    listen 80;
    listen [::]:80;
    server_name marketingtool.pro www.marketingtool.pro;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        
        proxy_connect_timeout 60s;
        proxy_send_timeout 60s;
        proxy_read_timeout 60s;
    }

    location /_next/static {
        proxy_pass http://localhost:3000;
        proxy_cache_valid 200 60m;
        add_header Cache-Control "public, immutable";
    }
}

# HTTP - App Subdomain
server {
    listen 80;
    listen [::]:80;
    server_name app.marketingtool.pro;

    location / {
        proxy_pass http://localhost:3001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        
        proxy_connect_timeout 60s;
        proxy_send_timeout 60s;
        proxy_read_timeout 60s;
    }

    location /_next/static {
        proxy_pass http://localhost:3001;
        proxy_cache_valid 200 60m;
        add_header Cache-Control "public, immutable";
    }
}
NGINXEOF

# Step 4: Enable site
echo ""
echo "4️⃣ Enabling site..."
ln -sf /etc/nginx/sites-available/marketingtool.pro /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Step 5: Test config
echo ""
echo "5️⃣ Testing Nginx config..."
nginx -t

# Step 6: Start Nginx
echo ""
echo "6️⃣ Starting Nginx..."
systemctl start nginx
systemctl status nginx --no-pager | head -10

# Step 7: Wait a moment
sleep 3

# Step 8: Get SSL certificate
echo ""
echo "7️⃣ Getting SSL certificate..."
certbot --nginx \
    -d marketingtool.pro \
    -d www.marketingtool.pro \
    -d app.marketingtool.pro \
    --non-interactive \
    --agree-tos \
    --email admin@marketingtool.pro \
    --redirect || {
    echo "⚠️ Certbot failed, but HTTP should work"
    echo "You can try SSL setup later"
}

echo ""
echo "✅ Fix Complete!"
echo ""
echo "🌐 Your sites:"
echo "   - http://marketingtool.pro"
echo "   - http://app.marketingtool.pro"
echo ""
echo "Check: pm2 status"
echo "Test: curl http://marketingtool.pro"
