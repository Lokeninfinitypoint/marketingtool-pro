#!/bin/bash
# Quick Fix - Copy and Paste This ENTIRE Block

set -e

echo "🔧 Quick Fix Starting..."

# Stop everything using port 80
systemctl stop nginx 2>/dev/null || true
pkill -9 nginx 2>/dev/null || true
sleep 2

# Create HTTP-only config
cat > /etc/nginx/sites-available/marketingtool.pro << 'NGINXEOF'
server {
    listen 80;
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
    }
}
server {
    listen 80;
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
    }
}
NGINXEOF

ln -sf /etc/nginx/sites-available/marketingtool.pro /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl start nginx

echo "✅ HTTP Working! Now getting SSL..."

sleep 3

certbot --nginx -d marketingtool.pro -d www.marketingtool.pro -d app.marketingtool.pro --non-interactive --agree-tos --email admin@marketingtool.pro --redirect || echo "SSL will be configured later"

echo "✅ Done! Check: pm2 status"
