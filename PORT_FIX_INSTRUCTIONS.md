# 🔧 Port 80 Conflict - Quick Fix

**Problem**: Port 80 is already in use on your VPS by another service (likely Apache/Nginx).

---

## ✅ Solution: Use Alternative Ports

### Updated Configuration

Your `docker-compose.production.yml` now uses:
- **Port 3000** → Astro app (instead of 80)
- **Port 8080** → Nginx (unchanged)

---

## 🚀 Quick Fix Options

### **Option 1: Via SSH (Manual)**

```bash
# Connect to VPS
ssh root@31.220.107.19

# Go to deployment directory
cd /opt/marketingtool-pro

# Stop existing containers
docker-compose -f docker-compose.production.yml down

# Copy updated docker-compose.yml from local machine
# (On your local machine, run this):
scp docker-compose.production.yml root@31.220.107.19:/opt/marketingtool-pro/

# Back on VPS, restart containers
docker-compose -f docker-compose.production.yml up -d

# Check status
docker-compose -f docker-compose.production.yml ps
```

### **Option 2: Via Docker Manager UI**

1. Stop the current deployment in Docker Manager
2. Delete the existing project
3. Re-deploy using the updated `docker-compose.production.yml`

### **Option 3: Stop Service on Port 80 (Advanced)**

If you want to use port 80:

```bash
ssh root@31.220.107.19

# Find what's using port 80
netstat -tulpn | grep ':80'
# or
lsof -i :80

# If it's Apache:
systemctl stop apache2
systemctl disable apache2

# If it's Nginx:
systemctl stop nginx
systemctl disable nginx

# Then restart your containers
cd /opt/marketingtool-pro
docker-compose -f docker-compose.production.yml up -d
```

---

## 🌐 Access Your Site

After fix, your site will be at:

- **Main Site**: http://31.220.107.19:3000
- **Nginx**: http://31.220.107.19:8080

---

## 📝 For Docker Manager UI

Copy this content and paste in Docker Manager:

```yaml
services:
  marketingtool:
    image: node:20-alpine
    container_name: marketingtool-pro
    working_dir: /app
    volumes:
      - ./src:/app/src:ro
      - ./public:/app/public:ro
      - ./package.json:/app/package.json:ro
      - ./package-lock.json:/app/package-lock.json:ro
      - ./astro.config.mjs:/app/astro.config.mjs:ro
      - ./tsconfig.json:/app/tsconfig.json:ro
      - node_modules:/app/node_modules
      - dist:/app/dist
    ports:
      - "3000:4321"
    environment:
      - NODE_ENV=production
      - HOST=0.0.0.0
      - PORT=4321
    command: >
      sh -c "
      npm ci --production &&
      npm run build &&
      npm run preview -- --host 0.0.0.0 --port 4321
      "
    restart: unless-stopped

  nginx:
    image: nginx:alpine
    container_name: marketingtool-nginx
    volumes:
      - ./dist:/usr/share/nginx/html:ro
      - ./nginx.conf:/etc/nginx/nginx.conf:ro
    ports:
      - "8080:80"
    depends_on:
      - marketingtool
    restart: unless-stopped

volumes:
  node_modules:
  dist:
```

**Project Name**: `marketingtool-pro`

---

## 🔍 Troubleshooting

### Check if containers are running:
```bash
ssh root@31.220.107.19 'docker ps'
```

### View logs:
```bash
ssh root@31.220.107.19 'cd /opt/marketingtool-pro && docker-compose -f docker-compose.production.yml logs -f'
```

### Restart containers:
```bash
ssh root@31.220.107.19 'cd /opt/marketingtool-pro && docker-compose -f docker-compose.production.yml restart'
```

---

**Status**: Port conflict identified  
**Solution**: Use port 3000 instead of 80  
**Access**: http://31.220.107.19:3000 🚀
