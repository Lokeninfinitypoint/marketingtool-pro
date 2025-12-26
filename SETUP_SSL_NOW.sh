#!/bin/bash
# Setup SSL - Handle Docker Proxy on Port 443

set -e

echo "🔒 Setting up SSL Certificate"
echo "============================"
echo ""

# Check what's using port 443
echo "1️⃣ Checking port 443..."
lsof -i :443 | head -5
echo ""

# Check if it's Docker
if docker ps | grep -q "443"; then
    echo "⚠️ Docker container using port 443"
    echo "Checking Docker containers..."
    docker ps
    echo ""
    echo "You may need to stop Docker containers using port 443"
    echo "Or configure them to use different ports"
fi

# Stop Nginx temporarily for standalone certbot
echo "2️⃣ Stopping Nginx temporarily..."
systemctl stop nginx

# Try to get certificate with standalone (needs port 80 free)
echo "3️⃣ Getting SSL certificate..."
certbot certonly --standalone \
    -d marketingtool.pro \
    -d www.marketingtool.pro \
    -d app.marketingtool.pro \
    --non-interactive \
    --agree-tos \
    --email admin@marketingtool.pro \
    --preferred-challenges http || {
    echo "⚠️ Standalone method failed, trying webroot..."
    # Alternative: webroot method
    mkdir -p /var/www/certbot
    certbot certonly --webroot \
        -w /var/www/certbot \
        -d marketingtool.pro \
        -d www.marketingtool.pro \
        -d app.marketingtool.pro \
        --non-interactive \
        --agree-tos \
        --email admin@marketingtool.pro || {
        echo "❌ SSL certificate request failed"
        echo "Port 443 might be blocked by Docker"
        systemctl start nginx
        exit 1
    }
}

# Update Nginx config with SSL
echo ""
echo "4️⃣ Updating Nginx config with SSL..."

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

# Test config
echo ""
echo "5️⃣ Testing Nginx config..."
nginx -t

# Start Nginx
echo ""
echo "6️⃣ Starting Nginx..."
systemctl start nginx

# Check status
echo ""
echo "7️⃣ Checking status..."
systemctl status nginx --no-pager | head -10

echo ""
echo "✅ SSL Setup Complete!"
echo ""
echo "🌐 Test your sites:"
echo "   https://marketingtool.pro"
echo "   https://app.marketingtool.pro"
