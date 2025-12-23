# ✅ MarketingTool.Pro - VPS Deployment Configuration

**VPS**: 31.220.107.19  
**Date**: December 23, 2025  
**Status**: Ready to Deploy

---

## 🌐 Access URLs

After deployment, your site will be accessible at:

| Service | URL | Description |
|---------|-----|-------------|
| **Main Site (HTTP)** | http://31.220.107.19:3000 | Astro application |
| **Main Site (HTTPS)** | https://31.220.107.19:443 | Astro application (SSL) |
| **Nginx** | http://31.220.107.19:8080 | Static file server |

---

## 📦 Docker Configuration

### Ports Mapping

```
marketingtool-pro container:
  - 3000:4321  (HTTP)
  - 443:4321   (HTTPS)

marketingtool-nginx container:
  - 8080:80    (HTTP)
```

### Why These Ports?

- ✅ **Port 80 avoided** - Already in use by existing service
- ✅ **Port 3000** - Standard alternative HTTP port
- ✅ **Port 443** - Standard HTTPS port
- ✅ **Port 8080** - Standard alternative for web servers

---

## 🚀 Deployment Options

### Option 1: Automated Script
```bash
cd /Users/loken/Projects/marketingtool-pro
./DEPLOY_VPS.sh
```

### Option 2: Docker Manager UI

1. Go to VPS → MarketingTool.pro → Docker Manager
2. Click "Compose" tab
3. Paste your `docker-compose.production.yml` content
4. Project name: `marketingtool-pro`
5. Click Deploy

### Option 3: Manual SSH

```bash
# Connect to VPS
ssh root@31.220.107.19

# Navigate to deployment directory
cd /opt/marketingtool-pro

# Start containers
docker-compose -f docker-compose.production.yml up -d

# Check status
docker-compose -f docker-compose.production.yml ps
```

---

## 🔧 Container Details

### marketingtool-pro (Node.js Container)
- **Image**: node:20-alpine
- **Purpose**: Runs Astro build and preview server
- **Build Process**:
  1. Install dependencies (`npm ci --production`)
  2. Build Astro app (`npm run build`)
  3. Start preview server on port 4321
- **Restart Policy**: unless-stopped

### marketingtool-nginx (Nginx Container)
- **Image**: nginx:alpine
- **Purpose**: Serves static built files from `dist/`
- **Configuration**: Custom nginx.conf
- **Restart Policy**: unless-stopped

---

## 📊 Resource Requirements

| Resource | Requirement |
|----------|-------------|
| CPU | 2+ cores recommended |
| RAM | 4GB minimum, 8GB recommended |
| Disk Space | 30GB (for 22GB repo + Docker) |
| Network | Unlimited bandwidth recommended |

---

## 🔍 Management Commands

### View Container Status
```bash
ssh root@31.220.107.19 'docker ps'
```

### View Logs (Real-time)
```bash
ssh root@31.220.107.19 'cd /opt/marketingtool-pro && docker-compose -f docker-compose.production.yml logs -f'
```

### View Logs (Specific Service)
```bash
# Astro app logs
ssh root@31.220.107.19 'docker logs marketingtool-pro'

# Nginx logs
ssh root@31.220.107.19 'docker logs marketingtool-nginx'
```

### Restart Services
```bash
ssh root@31.220.107.19 'cd /opt/marketingtool-pro && docker-compose -f docker-compose.production.yml restart'
```

### Stop Services
```bash
ssh root@31.220.107.19 'cd /opt/marketingtool-pro && docker-compose -f docker-compose.production.yml down'
```

### Rebuild Containers
```bash
ssh root@31.220.107.19 'cd /opt/marketingtool-pro && docker-compose -f docker-compose.production.yml up -d --build'
```

---

## 🐛 Troubleshooting

### Container Won't Start
```bash
# Check logs for errors
ssh root@31.220.107.19 'docker logs marketingtool-pro'

# Check if ports are available
ssh root@31.220.107.19 'netstat -tulpn | grep -E "3000|443|8080"'
```

### Site Not Accessible
```bash
# 1. Check if containers are running
ssh root@31.220.107.19 'docker ps'

# 2. Check firewall
ssh root@31.220.107.19 'ufw status'

# 3. Test from VPS itself
ssh root@31.220.107.19 'curl http://localhost:3000'
```

### Port Still Conflicts
```bash
# Find what's using the port
ssh root@31.220.107.19 'lsof -i :3000'

# Change to different port in docker-compose.production.yml
# Example: Change "3000:4321" to "3500:4321"
```

### Out of Memory
```bash
# Check resource usage
ssh root@31.220.107.19 'docker stats --no-stream'

# Restart containers to free memory
ssh root@31.220.107.19 'cd /opt/marketingtool-pro && docker-compose restart'
```

---

## 🔒 Security Recommendations

### 1. Setup Firewall
```bash
ssh root@31.220.107.19

# Install UFW
apt-get update && apt-get install -y ufw

# Allow necessary ports
ufw allow 22/tcp     # SSH
ufw allow 3000/tcp   # Astro HTTP
ufw allow 443/tcp    # HTTPS
ufw allow 8080/tcp   # Nginx

# Enable firewall
ufw enable

# Check status
ufw status
```

### 2. Add SSL Certificate (Optional)
```bash
# Install Certbot
apt-get install -y certbot

# Get SSL certificate (replace with your domain)
certbot certonly --standalone -d yourdomain.com

# Certificate will be at:
# /etc/letsencrypt/live/yourdomain.com/fullchain.pem
# /etc/letsencrypt/live/yourdomain.com/privkey.pem
```

### 3. Change Default Password
```bash
ssh root@31.220.107.19
passwd
# Enter new password
```

---

## 📁 Files Deployed to VPS

Located at: `/opt/marketingtool-pro/`

```
/opt/marketingtool-pro/
├── src/                              # Astro source files
├── public/                           # Static assets
├── dist/                             # Built files (generated)
├── package.json                      # Dependencies
├── package-lock.json                 # Lock file
├── astro.config.mjs                 # Astro configuration
├── tsconfig.json                    # TypeScript config
├── docker-compose.production.yml    # Docker config
├── nginx.conf                       # Nginx config
└── node_modules/                    # Dependencies (generated)
```

---

## 🔄 Update Workflow

When you make changes locally:

```bash
# 1. Build locally
cd /Users/loken/Projects/marketingtool-pro
npm run build

# 2. Copy updated files to VPS
rsync -avz --progress ./dist root@31.220.107.19:/opt/marketingtool-pro/
rsync -avz --progress ./src root@31.220.107.19:/opt/marketingtool-pro/

# 3. Restart containers
ssh root@31.220.107.19 'cd /opt/marketingtool-pro && docker-compose -f docker-compose.production.yml restart'
```

---

## ✅ Pre-Deployment Checklist

Before deploying:
- [ ] `npm run build` runs successfully locally
- [ ] `npm run preview` works locally
- [ ] SSH access to VPS configured
- [ ] VPS has Docker and Docker Compose installed
- [ ] Ports 3000, 443, 8080 are available
- [ ] Firewall configured (if needed)
- [ ] At least 30GB free disk space on VPS

---

## 📊 Expected Build Time

| Phase | Time |
|-------|------|
| File upload to VPS | 30-60 min (22GB) |
| npm install | 5-10 min |
| Astro build | 1-2 min |
| Container startup | 30 sec |
| **Total** | **~40-75 minutes** |

---

## 🎉 Post-Deployment Verification

After deployment, verify:

1. ✅ Containers are running:
   ```bash
   ssh root@31.220.107.19 'docker ps'
   ```

2. ✅ Site is accessible:
   - Open http://31.220.107.19:3000 in browser
   - Should see MarketingTool.Pro homepage

3. ✅ No errors in logs:
   ```bash
   ssh root@31.220.107.19 'docker logs marketingtool-pro --tail 50'
   ```

4. ✅ All pages load correctly:
   - Test: http://31.220.107.19:3000/dashboard
   - Test: http://31.220.107.19:3000/pricing
   - Test: http://31.220.107.19:3000/tools

---

## 📞 Quick Reference

| Action | Command |
|--------|---------|
| **Deploy** | `./DEPLOY_VPS.sh` |
| **SSH to VPS** | `ssh root@31.220.107.19` |
| **Check Status** | `docker ps` |
| **View Logs** | `docker logs marketingtool-pro -f` |
| **Restart** | `docker-compose restart` |
| **Stop** | `docker-compose down` |
| **Access Site** | http://31.220.107.19:3000 |

---

**Ready to Deploy!** 🚀

Choose your deployment method and run the commands above.

Your site will be live at:
- **http://31.220.107.19:3000**
- **https://31.220.107.19:443**
- **http://31.220.107.19:8080** (nginx)
