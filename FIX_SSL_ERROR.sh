#!/bin/bash
# Fix SSL Error - Revert to HTTP, then setup SSL properly

set -e

echo "🔧 Fixing SSL Error"
echo "==================="
echo ""

# Step 1: Create HTTP-only config (no SSL references)
echo "1️⃣ Creating HTTP-only config..."
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

# Test and restart
echo ""
echo "2️⃣ Testing and restarting Nginx..."
nginx -t
systemctl restart nginx
systemctl status nginx --no-pager | head -5

echo ""
echo "✅ HTTP config restored!"
echo ""
echo "3️⃣ Now trying SSL with webroot method..."

# Create webroot directory
mkdir -p /var/www/certbot

# Try webroot method (doesn't need to stop Nginx)
certbot certonly --webroot \
    -w /var/www/certbot \
    -d marketingtool.pro \
    -d www.marketingtool.pro \
    -d app.marketingtool.pro \
    --non-interactive \
    --agree-tos \
    --email admin@marketingtool.pro || {
    echo ""
    echo "⚠️ Webroot method failed"
    echo ""
    echo "Trying standalone method (will stop Nginx briefly)..."
    
    systemctl stop nginx
    sleep 2
    
    certbot certonly --standalone \
        -d marketingtool.pro \
        -d www.marketingtool.pro \
        -d app.marketingtool.pro \
        --non-interactive \
        --agree-tos \
        --email admin@marketingtool.pro \
        --preferred-challenges http || {
        echo ""
        echo "❌ SSL certificate request failed"
        echo "HTTP sites are working, SSL can be set up later"
        systemctl start nginx
        exit 0
    }
    
    systemctl start nginx
}

# If certificate was obtained, update config
if [ -f "/etc/letsencrypt/live/marketingtool.pro/fullchain.pem" ]; then
    echo ""
    echo "4️⃣ Certificate obtained! Updating Nginx with SSL..."
    
    cat > /etc/nginx/sites-available/marketingtool.pro << 'NGINXEOF'
# HTTP - Redirect to HTTPS
server {
    listen 80;
    server_name marketingtool.pro www.marketingtool.pro;
    return 301 https://$server_name$request_uri;
}

server {
    listen 80;
    server_name app.marketingtool.pro;
    return 301 https://$server_name$request_uri;
}

# HTTPS - Main Domain
server {
    listen 443 ssl http2;
    server_name marketingtool.pro www.marketingtool.pro;
    
    ssl_certificate /etc/letsencrypt/live/marketingtool.pro/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/marketingtool.pro/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    
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
    }
}

# HTTPS - App Subdomain
server {
    listen 443 ssl http2;
    server_name app.marketingtool.pro;
    
    ssl_certificate /etc/letsencrypt/live/marketingtool.pro/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/marketingtool.pro/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    
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
    }
}
NGINXEOF

    nginx -t
    systemctl restart nginx
    
    echo ""
    echo "✅ SSL Configured!"
    echo ""
    echo "🌐 Your sites:"
    echo "   https://marketingtool.pro"
    echo "   https://app.marketingtool.pro"
else
    echo ""
    echo "⚠️ SSL certificate not obtained"
    echo "HTTP sites are working:"
    echo "   http://marketingtool.pro"
    echo "   http://app.marketingtool.pro"
fi
