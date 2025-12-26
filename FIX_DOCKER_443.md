# 🔧 Fix Port 443 Issue - Docker Proxy

## Problem

Port 443 is being used by `docker-proxy`, which blocks SSL certificate setup.

---

## 🚀 Quick Fix Options

### Option 1: Stop Docker Containers Using Port 443

```bash
# See what Docker containers are running
docker ps

# Stop containers using port 443
docker stop $(docker ps -q --filter "publish=443")

# Or stop all Docker containers
docker stop $(docker ps -q)
```

### Option 2: Use HTTP-01 Challenge (Port 80 Only)

Since port 80 is free, we can get SSL certificate using HTTP challenge:

```bash
# Stop Nginx temporarily
systemctl stop nginx

# Get certificate using standalone (only needs port 80)
certbot certonly --standalone \
    -d marketingtool.pro \
    -d www.marketingtool.pro \
    -d app.marketingtool.pro \
    --non-interactive \
    --agree-tos \
    --email admin@marketingtool.pro \
    --preferred-challenges http

# Start Nginx
systemctl start nginx

# Update Nginx config with SSL (see below)
```

### Option 3: Configure Docker to Use Different Ports

If you need Docker containers, move them to different ports:

```bash
# Check Docker containers
docker ps

# Stop and reconfigure containers to use ports other than 443
# Example: Change port mapping from 443:443 to 8443:443
```

---

## 📋 Complete SSL Setup (After Port 443 is Free)

Once port 443 is available:

```bash
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

## ✅ Current Status

- ✅ Apps running (PM2)
- ✅ Nginx running
- ✅ HTTP working (port 80)
- ⚠️ SSL blocked (port 443 in use by Docker)

---

*Fix Docker port 443 issue first, then setup SSL!*
