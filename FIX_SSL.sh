#!/bin/bash
# Fix SSL Certificate Issues - Run on Server

set -e

echo "🔒 Fixing SSL Certificate Issues"
echo "================================="
echo ""

# Check current SSL status
echo "1️⃣ Checking current SSL certificates..."
certbot certificates || echo "No certificates found"

echo ""
echo "2️⃣ Checking Nginx SSL configuration..."
if [ -f "/etc/nginx/sites-available/marketingtool.pro" ]; then
    grep -A 5 "ssl_certificate" /etc/nginx/sites-available/marketingtool.pro || echo "No SSL config found"
else
    echo "❌ Nginx config file not found"
fi

echo ""
echo "3️⃣ Stopping Nginx temporarily..."
systemctl stop nginx || true

echo ""
echo "4️⃣ Requesting new SSL certificates..."
certbot certonly --standalone \
    -d marketingtool.pro \
    -d www.marketingtool.pro \
    -d app.marketingtool.pro \
    --non-interactive \
    --agree-tos \
    --email admin@marketingtool.pro \
    --preferred-challenges http || {
    echo "⚠️ Certificate request failed, trying alternative method..."
    certbot certonly --nginx \
        -d marketingtool.pro \
        -d www.marketingtool.pro \
        -d app.marketingtool.pro \
        --non-interactive \
        --agree-tos \
        --email admin@marketingtool.pro || {
        echo "❌ SSL setup failed"
        exit 1
    }
}

echo ""
echo "5️⃣ Updating Nginx configuration with SSL..."

cat > /etc/nginx/sites-available/marketingtool.pro << 'NGINXEOF'
# HTTP - Redirect to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name marketingtool.pro www.marketingtool.pro;
    return 301 https://$server_name$request_uri;
}

server {
    listen 80;
    listen [::]:80;
    server_name app.marketingtool.pro;
    return 301 https://$server_name$request_uri;
}

# HTTPS - Main Domain
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name marketingtool.pro www.marketingtool.pro;

    ssl_certificate /etc/letsencrypt/live/marketingtool.pro/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/marketingtool.pro/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

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

# HTTPS - App Subdomain
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name app.marketingtool.pro;

    ssl_certificate /etc/letsencrypt/live/marketingtool.pro/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/marketingtool.pro/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

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

echo ""
echo "6️⃣ Testing Nginx configuration..."
nginx -t

echo ""
echo "7️⃣ Starting Nginx..."
systemctl start nginx
systemctl status nginx --no-pager | head -10

echo ""
echo "✅ SSL Configuration Complete!"
echo ""
echo "🌐 Your sites should now work with HTTPS:"
echo "   - https://marketingtool.pro"
echo "   - https://app.marketingtool.pro"
echo ""
echo "Test: curl -I https://marketingtool.pro"
