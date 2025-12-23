# 🎯 Docker Manager UI - Step-by-Step Deployment

**Easy visual deployment using Docker Manager interface**

---

## 📋 Step-by-Step Instructions

### Step 1: Open Docker Manager
1. Open your browser
2. Go to your VPS management panel
3. Navigate to: **VPS** → **MarketingTool.pro** → **Docker Manager**

### Step 2: Go to Compose Tab
1. Click on the **"Compose"** tab at the top
2. You'll see a text area for pasting Docker Compose content

### Step 3: Copy Docker Compose Content

**Copy this ENTIRE content** (click the copy button or select all):

```yaml
services:
  marketingtool:
    image: node:20-alpine
    container_name: marketingtool-pro
    working_dir: /app
    volumes:
      - ./src:/app/src:ro
      - ./public:/app/public:ro
      - ./tools-collection:/app/tools-collection:ro
      - ./wp-content:/app/wp-content:ro
      - ./production-html:/app/production-html:ro
      - ./package.json:/app/package.json:ro
      - ./package-lock.json:/app/package-lock.json:ro
      - ./astro.config.mjs:/app/astro.config.mjs:ro
      - ./tsconfig.json:/app/tsconfig.json:ro
      - node_modules:/app/node_modules
      - dist:/app/dist
    ports:
      - "3000:4321"
      - "443:4321"
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
    networks:
      - marketingtool-network

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
    networks:
      - marketingtool-network

volumes:
  node_modules:
  dist:

networks:
  marketingtool-network:
    driver: bridge
```

### Step 4: Paste in Docker Manager
1. **Paste** the copied content into the text area in Docker Manager
2. Make sure all content is pasted correctly

### Step 5: Set Project Name
1. In the **"Project name"** field, enter: `marketingtool-pro`
2. Make sure there are no spaces or special characters

### Step 6: Deploy
1. Click the **"Deploy"** button
2. Wait for the deployment to start

### Step 7: Monitor Progress
Docker Manager will show:
- Container creation progress
- Image pulling status
- Build logs
- Deployment status

---

## ⚠️ IMPORTANT: File Upload Required

**Docker Manager can deploy the containers, BUT you need to upload your project files first!**

### Why?
The docker-compose.yml references local files:
- `./src` - Your source code
- `./public` - Static assets
- `./package.json` - Dependencies
- etc.

### How to Upload Files

#### Option A: Via SSH (Recommended)
```bash
# From your local machine
cd /Users/loken/Projects/marketingtool-pro

# Upload files via rsync
rsync -avz --progress \
  --exclude='node_modules' \
  --exclude='.git' \
  --exclude='dist' \
  ./src \
  ./public \
  ./package.json \
  ./package-lock.json \
  ./astro.config.mjs \
  ./tsconfig.json \
  root@31.220.107.19:/opt/marketingtool-pro/
```

#### Option B: Via SFTP Client
1. Use FileZilla, Cyberduck, or similar
2. Connect to: `31.220.107.19`
3. Username: `root`
4. Password: `Lokendra@9078`
5. Upload project files to: `/opt/marketingtool-pro/`

---

## 🎬 Complete Workflow

### The RIGHT Order:

1. **Upload Files First** (via SSH/SFTP)
   ```bash
   ssh root@31.220.107.19
   mkdir -p /opt/marketingtool-pro
   # Upload files here
   ```

2. **Then Deploy via Docker Manager**
   - Paste docker-compose.yml
   - Set project name: `marketingtool-pro`
   - Click Deploy

---

## 🖼️ What You'll See

### Docker Manager Interface:

```
┌─────────────────────────────────────────┐
│  Docker Manager                          │
├─────────────────────────────────────────┤
│  [Terminal] [Compose] [URL]             │
│                                          │
│  Paste your Docker Compose file:        │
│  ┌─────────────────────────────────┐   │
│  │ services:                        │   │
│  │   marketingtool:                 │   │
│  │     image: node:20-alpine        │   │
│  │     ...                          │   │
│  └─────────────────────────────────┘   │
│                                          │
│  Project name: [marketingtool-pro]      │
│                                          │
│  [Cancel]            [Deploy]           │
└─────────────────────────────────────────┘
```

---

## ✅ After Deployment

### Check Container Status
In Docker Manager Terminal:
```bash
docker ps
```

You should see:
- `marketingtool-pro` container (running)
- `marketingtool-nginx` container (running)

### Access Your Site
- **Main Site**: http://31.220.107.19:3000
- **Nginx**: http://31.220.107.19:8080

---

## 🐛 Common Issues

### Issue 1: "No such file or directory"
**Problem**: Files not uploaded to VPS
**Solution**: Upload files first via SSH/SFTP to `/opt/marketingtool-pro/`

### Issue 2: "Port already in use"
**Problem**: Another service using the port
**Solution**: 
- Change port in docker-compose.yml (e.g., 3000 → 3500)
- Or stop the conflicting service

### Issue 3: "Cannot connect to Docker daemon"
**Problem**: Docker not installed or not running
**Solution**:
```bash
ssh root@31.220.107.19
systemctl start docker
systemctl enable docker
```

---

## 📝 Alternative: Use URL Instead

If your docker-compose.yml is on GitHub:

1. Push to GitHub first
2. In Docker Manager, go to **"URL"** tab
3. Paste: `https://raw.githubusercontent.com/yourusername/marketingtool-pro/main/docker-compose.production.yml`
4. Project name: `marketingtool-pro`
5. Click Deploy

---

## 🎯 Quick Summary

**What to do:**

1. ✅ **Upload files** to VPS at `/opt/marketingtool-pro/`
2. ✅ **Open Docker Manager UI** in browser
3. ✅ **Click "Compose" tab**
4. ✅ **Copy and paste** docker-compose.yml content
5. ✅ **Enter project name**: `marketingtool-pro`
6. ✅ **Click Deploy button**
7. ✅ **Wait for containers to start**
8. ✅ **Access**: http://31.220.107.19:3000

---

**Need Help?** The automated script is easier:
```bash
cd /Users/loken/Projects/marketingtool-pro
./DEPLOY_VPS.sh
```

This handles file upload + Docker deployment automatically! 🚀
