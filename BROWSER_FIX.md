# 🔧 Fix Browser SSL Error

## Problem: ERR_SSL_PROTOCOL_ERROR

Your browser is trying to access HTTPS, but SSL isn't configured yet.

---

## ✅ Quick Fix

### Option 1: Use HTTP (Not HTTPS)

**Make sure you're using HTTP:**
- ✅ http://marketingtool.pro
- ✅ http://app.marketingtool.pro
- ❌ https://marketingtool.pro (don't use this yet)

### Option 2: Clear Browser Cache

Your browser might have cached HTTPS redirect. Clear it:

1. **Chrome/Edge:**
   - Press `Ctrl+Shift+Delete` (Windows) or `Cmd+Shift+Delete` (Mac)
   - Clear cached images and files
   - Or use Incognito/Private mode

2. **Firefox:**
   - Press `Ctrl+Shift+Delete`
   - Clear cache
   - Or use Private window

### Option 3: Fix Nginx Config (Run on Server)

```bash
# Remove any HTTPS redirects
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

nginx -t
systemctl restart nginx
```

---

## 🎯 Solution

**Use HTTP URLs:**
- http://marketingtool.pro ✅
- http://app.marketingtool.pro ✅

**Don't use HTTPS yet:**
- https://marketingtool.pro ❌ (SSL not configured)

---

## ✅ After Fix

Your sites will work on:
- http://marketingtool.pro
- http://app.marketingtool.pro

SSL can be configured later when ready!

---

*Use HTTP URLs - Sites are working!*
