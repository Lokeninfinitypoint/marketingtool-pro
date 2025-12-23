# 🚀 VPS Docker Deployment Guide - MarketingTool.Pro

**VPS Details**:
- **IP**: 31.220.107.19
- **User**: root
- **Password**: Lokendra@9078

---

## 📋 Quick Deploy (Automated)

### Method 1: One Command Deploy
```bash
cd /Users/loken/Projects/marketingtool-pro
./DEPLOY_VPS.sh
```

This script will:
1. ✅ Test SSH connection
2. ✅ Install Docker & Docker Compose on VPS
3. ✅ Create deployment directory
4. ✅ Copy all files (22GB)
5. ✅ Build and start containers
6. ✅ Your site will be live!

**Time**: ~30-60 minutes (depending on upload speed)

---

## 📋 Manual Deploy (Docker Manager UI)

### Step 1: Push to GitHub (Optional)

```bash
cd /Users/loken/Projects/marketingtool-pro

# Initialize git if needed
git init

# Add all files
git add .

# Commit
git commit -m "Production deployment ready"

# Add remote (replace with your repo)
git remote add origin https://github.com/yourusername/marketingtool-pro.git

# Push
git push -u origin main
```

### Step 2: Use Docker Manager UI

**In Docker Manager**:
1. Go to: VPS → MarketingTool.pro → Docker Manager
2. Click **"Compose"** tab
3. Paste GitHub URL or docker-compose.yml URL
4. Set **Project name**: `marketingtool-pro`
5. Click **Deploy**

**Docker Compose URL Format**:
```
https://raw.githubusercontent.com/yourusername/marketingtool-pro/main/docker-compose.production.yml
```

---

## 📋 Direct SSH Deploy

### Step 1: SSH to VPS
```bash
ssh root@31.220.107.19
# Password: Lokendra@9078
```

### Step 2: Install Docker
```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
systemctl enable docker
systemctl start docker

# Install Docker Compose
curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose

# Verify
docker --version
docker-compose --version
```

### Step 3: Create Deployment Directory
```bash
mkdir -p /opt/marketingtool-pro
cd /opt/marketingtool-pro
```

### Step 4: Upload Files

**From your local machine**:
```bash
cd /Users/loken/Projects/marketingtool-pro

# Build first
npm run build

# Copy files via rsync
rsync -avz --progress \
    --exclude='node_modules' \
    --exclude='.git' \
    --exclude='.astro' \
    --exclude='*.log' \
    ./src \
    ./public \
    ./dist \
    ./package.json \
    ./package-lock.json \
    ./astro.config.mjs \
    ./tsconfig.json \
    ./docker-compose.production.yml \
    ./nginx.conf \
    root@31.220.107.19:/opt/marketingtool-pro/
```

### Step 5: Start Docker Containers

**On VPS**:
```bash
cd /opt/marketingtool-pro

# Start containers
docker-compose -f docker-compose.production.yml up -d

# Check status
docker-compose -f docker-compose.production.yml ps

# View logs
docker-compose -f docker-compose.production.yml logs -f
```

---

## 🌐 Access Your Site

After deployment:

- **Main Site**: http://31.220.107.19
- **Nginx**: http://31.220.107.19:8080
- **Tools**: http://31.220.107.19/tools/

---

## 🔧 Docker Commands

### View Logs
```bash
ssh root@31.220.107.19
cd /opt/marketingtool-pro
docker-compose -f docker-compose.production.yml logs -f
```

### Restart Services
```bash
ssh root@31.220.107.19
cd /opt/marketingtool-pro
docker-compose -f docker-compose.production.yml restart
```

### Stop Services
```bash
ssh root@31.220.107.19
cd /opt/marketingtool-pro
docker-compose -f docker-compose.production.yml down
```

### Rebuild Containers
```bash
ssh root@31.220.107.19
cd /opt/marketingtool-pro
docker-compose -f docker-compose.production.yml up -d --build
```

### Check Container Status
```bash
ssh root@31.220.107.19
docker ps
docker stats
```

---

## 📦 What Gets Deployed

### Files Copied to VPS:
```
/opt/marketingtool-pro/
├── src/                              # Astro frontend
├── public/                           # Static assets
├── dist/                             # Built files
├── package.json                      # Dependencies
├── package-lock.json                 # Lock file
├── astro.config.mjs                 # Astro config
├── tsconfig.json                    # TypeScript config
├── docker-compose.production.yml    # Docker config
├── nginx.conf                       # Nginx config
└── .dockerignore                    # Docker ignore
```

### Docker Containers:
1. **marketingtool-pro** (Node 20 Alpine)
   - Runs Astro build and preview server
   - Port: 4321 → 80, 443

2. **marketingtool-nginx** (Nginx Alpine)
   - Serves static files
   - Port: 80 → 8080

---

## 🔒 Security Notes

### Change Default Password
```bash
ssh root@31.220.107.19
passwd
# Enter new password
```

### Setup Firewall
```bash
# On VPS
apt-get update
apt-get install -y ufw

# Allow SSH
ufw allow 22/tcp

# Allow HTTP/HTTPS
ufw allow 80/tcp
ufw allow 443/tcp
ufw allow 8080/tcp

# Enable firewall
ufw enable

# Check status
ufw status
```

### Setup SSL (Optional)
```bash
# On VPS
apt-get install -y certbot

# Get SSL certificate (replace with your domain)
certbot certonly --standalone -d yourdomain.com

# Update nginx.conf to use SSL
```

---

## 🐛 Troubleshooting

### Container Won't Start
```bash
# Check logs
docker-compose -f docker-compose.production.yml logs

# Check container
docker ps -a

# Restart
docker-compose -f docker-compose.production.yml restart
```

### Port Already in Use
```bash
# Find process using port
netstat -tulpn | grep :80

# Kill process
kill -9 <PID>

# Or change port in docker-compose.production.yml
```

### Out of Disk Space
```bash
# Check disk usage
df -h

# Clean Docker
docker system prune -a

# Remove old images
docker images
docker rmi <image-id>
```

### Can't Connect to VPS
```bash
# Test connection
ping 31.220.107.19

# Test SSH
ssh -v root@31.220.107.19

# Check firewall
ssh root@31.220.107.19 'ufw status'
```

---

## 🔄 Update Deployment

### Quick Update
```bash
cd /Users/loken/Projects/marketingtool-pro

# Make changes, then run
./DEPLOY_VPS.sh
```

### Manual Update
```bash
# Build locally
npm run build

# Copy updated files
rsync -avz --progress ./dist root@31.220.107.19:/opt/marketingtool-pro/

# Restart containers
ssh root@31.220.107.19 'cd /opt/marketingtool-pro && docker-compose -f docker-compose.production.yml restart'
```

---

## ✅ Deployment Checklist

Before deploying:
- [ ] Run `npm run build` locally (verify no errors)
- [ ] Test locally with `npm run preview`
- [ ] SSH access to VPS working
- [ ] Docker installed on VPS
- [ ] Sufficient disk space (25GB+ recommended)
- [ ] Firewall ports open (80, 443, 8080)

After deploying:
- [ ] Site accessible at http://31.220.107.19
- [ ] All pages loading correctly
- [ ] Check Docker logs for errors
- [ ] Test tool pages
- [ ] Monitor resource usage

---

## 📊 Resource Requirements

### Minimum VPS Specs:
- **CPU**: 2 cores
- **RAM**: 4GB
- **Disk**: 30GB (for 22GB repo + Docker)
- **Bandwidth**: Unlimited recommended

### Docker Resource Limits:
Can be added to docker-compose.production.yml:
```yaml
services:
  marketingtool:
    deploy:
      resources:
        limits:
          cpus: '2'
          memory: 2G
        reservations:
          cpus: '1'
          memory: 1G
```

---

## 📞 Quick Reference

### VPS Access
```bash
ssh root@31.220.107.19
# Password: Lokendra@9078
```

### Deploy Command
```bash
cd /Users/loken/Projects/marketingtool-pro
./DEPLOY_VPS.sh
```

### Site URL
```
http://31.220.107.19
```

### Check Status
```bash
ssh root@31.220.107.19 'docker ps'
```

### View Logs
```bash
ssh root@31.220.107.19 'cd /opt/marketingtool-pro && docker-compose -f docker-compose.production.yml logs -f'
```

---

**Ready to deploy!** 🚀

Run: `./DEPLOY_VPS.sh` to start deployment.
