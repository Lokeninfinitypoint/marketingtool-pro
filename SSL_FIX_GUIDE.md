# 🔒 Fix SSL Error - Quick Guide

## Problem: SSL_ERROR_INTERNAL_ERROR_ALERT

This means SSL certificate is not properly configured or expired.

---

## 🚀 Quick Fix - Run on Server

### Option 1: Automatic Fix (Recommended)

Copy and paste this entire script into your SSH session:

```bash
# Stop Nginx
systemctl stop nginx

# Request SSL certificate
certbot certonly --standalone \
    -d marketingtool.pro \
    -d www.marketingtool.pro \
    -d app.marketingtool.pro \
    --non-interactive \
    --agree-tos \
    --email admin@marketingtool.pro

# Update Nginx config with SSL
cat > /etc/nginx/sites-available/marketingtool.pro << 'NGINXEOF'
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

# Test and restart
nginx -t
systemctl start nginx
systemctl status nginx
```

---

### Option 2: Use Certbot with Nginx (Easier)

```bash
# This will automatically configure Nginx
certbot --nginx \
    -d marketingtool.pro \
    -d www.marketingtool.pro \
    -d app.marketingtool.pro \
    --non-interactive \
    --agree-tos \
    --email admin@marketingtool.pro \
    --redirect
```

This automatically:
- Gets SSL certificate
- Updates Nginx config
- Sets up HTTPS redirects

---

## 🔍 Troubleshooting

### If certbot fails:

1. **Check DNS:**
   ```bash
   nslookup marketingtool.pro
   # Should show: 31.220.107.19
   ```

2. **Check ports 80/443 are open:**
   ```bash
   netstat -tulpn | grep -E '80|443'
   ufw status
   ```

3. **Check if domain is accessible:**
   ```bash
   curl -I http://marketingtool.pro
   ```

4. **Try manual certificate:**
   ```bash
   certbot certonly --standalone -d marketingtool.pro -d www.marketingtool.pro -d app.marketingtool.pro
   ```

### If Nginx fails to start:

```bash
# Check config
nginx -t

# Check logs
tail -f /var/log/nginx/error.log

# Check if ports are in use
lsof -i :80
lsof -i :443
```

---

## ✅ After Fix

Test your domains:

```bash
# Test HTTPS
curl -I https://marketingtool.pro
curl -I https://app.marketingtool.pro

# Check certificate
openssl s_client -connect marketingtool.pro:443 -servername marketingtool.pro | grep -A 5 "Certificate chain"
```

---

## 🎯 Expected Result

After fixing:
- ✅ HTTPS works: https://marketingtool.pro
- ✅ HTTPS works: https://app.marketingtool.pro
- ✅ HTTP redirects to HTTPS
- ✅ No SSL errors in browser

---

*Run the fix script and your SSL will be working!*
