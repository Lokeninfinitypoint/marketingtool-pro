#!/bin/bash
# Fix HTTP Access - Remove HTTPS Redirects

echo "🔧 Fixing HTTP Access"
echo "===================="
echo ""

# Check current config
echo "1️⃣ Checking current Nginx config..."
grep -A 2 "return 301" /etc/nginx/sites-available/marketingtool.pro || echo "No redirects found"

# Create HTTP-only config (no HTTPS redirects)
echo ""
echo "2️⃣ Creating HTTP-only config..."
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
echo "3️⃣ Testing and restarting Nginx..."
nginx -t
systemctl restart nginx

echo ""
echo "✅ HTTP-only config applied!"
echo ""
echo "🌐 Your sites (use HTTP, not HTTPS):"
echo "   http://marketingtool.pro"
echo "   http://app.marketingtool.pro"
echo ""
echo "⚠️ Make sure to use http:// not https://"
