# 🚀 Docker Build Cloud Setup - MarketingTool.Pro

**Faster builds with Docker Build Cloud!**

---

## 📋 What is Docker Build Cloud?

Docker Build Cloud speeds up your Docker builds by:
- ⚡ Running builds in the cloud (faster than local)
- 💾 Better caching across builds
- 🔄 Parallel building
- 🌐 No local resources needed

---

## 🛠️ Setup Steps

### 1. Create Docker Buildx Builder

You already have the command:
```bash
docker buildx create --driver cloud lokendra334/loken
```

Set it as default:
```bash
docker buildx use cloud-lokendra334-loken
```

### 2. Build Your Image

```bash
cd /Users/loken/Projects/marketingtool-pro
docker buildx build --builder cloud-lokendra334-loken -t lokendra334/marketingtool-pro:latest --push .
```

This will:
- Build in Docker Build Cloud (fast!)
- Push to Docker Hub: `lokendra334/marketingtool-pro:latest`

---

## 🔧 Files Created

### Dockerfile
Located at: `/Users/loken/Projects/marketingtool-pro/Dockerfile`

Optimized for MarketingTool.Pro:
- Node 20 Alpine (small image)
- Installs ALL dependencies (including Tailwind)
- Builds Astro app
- Runs preview server on port 4321

### GitHub Actions Workflow
Located at: `.github/workflows/docker-build-cloud.yml`

Automated CI/CD:
- **On push to main**: Build → Push to Docker Hub → Deploy to VPS
- **On pull request**: Build only (cache)
- Uses Docker Build Cloud for fast builds

---

## 🚀 Deploy to VPS After Build

### Option 1: Manual Deploy (Quick Test)
```bash
# SSH to VPS
ssh root@31.220.107.19

# Pull latest image
docker pull lokendra334/marketingtool-pro:latest

# Stop old container
docker stop marketingtool-pro || true
docker rm marketingtool-pro || true

# Run new container
docker run -d \
  --name marketingtool-pro \
  --restart unless-stopped \
  -p 3000:4321 \
  lokendra334/marketingtool-pro:latest

# Check status
docker ps
docker logs marketingtool-pro -f
```

### Option 2: Automated via GitHub Actions

Just push to GitHub:
```bash
cd /Users/loken/Projects/marketingtool-pro
git add .
git commit -m "Deploy via Docker Build Cloud"
git push origin main
```

GitHub Actions will automatically:
1. Build image using Docker Build Cloud
2. Push to Docker Hub
3. Deploy to your VPS (31.220.107.19)

---

## 🔑 GitHub Secrets Required

Set these in GitHub: Settings → Secrets and variables → Actions

### Secrets:
- `DOCKER_PAT` - Docker Hub Personal Access Token
- `VPS_PASSWORD` - `Lokendra@9078`

### Variables:
- `DOCKER_USER` - `lokendra334`

---

## 📊 Build Locally with Docker Build Cloud

### First Time Setup:
```bash
# Create builder
docker buildx create --driver cloud lokendra334/loken

# Use it
docker buildx use cloud-lokendra334-loken

# List builders
docker buildx ls
```

### Build and Push:
```bash
cd /Users/loken/Projects/marketingtool-pro

# Build and push to Docker Hub
docker buildx build \
  --builder cloud-lokendra334-loken \
  -t lokendra334/marketingtool-pro:latest \
  --push \
  .
```

### Build Only (No Push):
```bash
docker buildx build \
  --builder cloud-lokendra334-loken \
  -t lokendra334/marketingtool-pro:latest \
  .
```

---

## 🎯 Complete Workflow

### 1. Build with Docker Build Cloud
```bash
docker buildx build --builder cloud-lokendra334-loken -t lokendra334/marketingtool-pro:latest --push .
```

### 2. Deploy to VPS
```bash
ssh root@31.220.107.19 'docker pull lokendra334/marketingtool-pro:latest && docker stop marketingtool-pro && docker rm marketingtool-pro && docker run -d --name marketingtool-pro --restart unless-stopped -p 3000:4321 lokendra334/marketingtool-pro:latest'
```

### 3. Access Site
```
http://31.220.107.19:3000
```

---

## 🔄 Update Workflow

When you make changes:

```bash
cd /Users/loken/Projects/marketingtool-pro

# Edit your code...

# Build with Docker Build Cloud
docker buildx build --builder cloud-lokendra334-loken -t lokendra334/marketingtool-pro:latest --push .

# Deploy to VPS
ssh root@31.220.107.19 'docker pull lokendra334/marketingtool-pro:latest && docker restart marketingtool-pro || (docker stop marketingtool-pro && docker rm marketingtool-pro && docker run -d --name marketingtool-pro --restart unless-stopped -p 3000:4321 lokendra334/marketingtool-pro:latest)'
```

---

## 💡 Benefits of This Approach

✅ **Faster Builds** - Docker Build Cloud is faster than local  
✅ **No Tailwind Issues** - Dockerfile installs ALL dependencies  
✅ **Consistent** - Same image locally and on VPS  
✅ **Easy Updates** - Build once, deploy anywhere  
✅ **Version Control** - Tag images with versions  
✅ **Automated** - GitHub Actions handles everything  

---

## 🐛 Troubleshooting

### Builder not found
```bash
docker buildx create --driver cloud lokendra334/loken
docker buildx use cloud-lokendra334-loken
```

### Build failed
```bash
# Check logs
docker buildx build --builder cloud-lokendra334-loken -t lokendra334/marketingtool-pro:latest --progress=plain .
```

### Can't connect to Docker Build Cloud
```bash
# Login to Docker
docker login

# Check builder
docker buildx ls
```

### VPS can't pull image
```bash
# SSH to VPS and login
ssh root@31.220.107.19
docker login
# Enter: lokendra334 / your_password
```

---

## 📞 Quick Commands Reference

| Task | Command |
|------|---------|
| **Create Builder** | `docker buildx create --driver cloud lokendra334/loken` |
| **Use Builder** | `docker buildx use cloud-lokendra334-loken` |
| **Build & Push** | `docker buildx build --builder cloud-lokendra334-loken -t lokendra334/marketingtool-pro:latest --push .` |
| **Deploy to VPS** | `ssh root@31.220.107.19 'docker pull lokendra334/marketingtool-pro:latest && docker restart marketingtool-pro'` |
| **View Logs** | `ssh root@31.220.107.19 'docker logs marketingtool-pro -f'` |

---

## 🎉 Ready to Build!

Run this now:
```bash
cd /Users/loken/Projects/marketingtool-pro
docker buildx build --builder cloud-lokendra334-loken -t lokendra334/marketingtool-pro:latest --push .
```

Takes 5-10 minutes first time, then 1-2 minutes with cache!

Your image will be at: `lokendra334/marketingtool-pro:latest` 🚀
