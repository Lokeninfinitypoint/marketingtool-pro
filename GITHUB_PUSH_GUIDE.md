# 📤 Push to GitHub - Exclude Large Files

Your repository is 22GB, but we'll only push the important source code (~200MB).

---

## ✅ What Gets Pushed to GitHub

**Source Code** (~200MB):
- ✅ `src/` - Astro source files
- ✅ `public/` - Static assets
- ✅ `package.json` - Dependencies
- ✅ `astro.config.mjs` - Configuration
- ✅ `Dockerfile` - Docker configuration
- ✅ `.github/workflows/` - CI/CD
- ✅ Documentation files

---

## ❌ What Stays on VPS Only

**Large Files** (22GB - excluded via .gitignore):
- ❌ `tools-collection/` (20GB)
- ❌ `production-html/` (11MB)
- ❌ `wp-content/` (13MB)
- ❌ `node_modules/` (auto-installed)
- ❌ `dist/` (auto-built)

---

## 🚀 Push to GitHub

### Step 1: Check .gitignore
```bash
cd /Users/loken/Projects/marketingtool-pro
cat .gitignore
```

The `.gitignore` file has been updated to exclude large directories.

### Step 2: Initialize Git (if not done)
```bash
git init
```

### Step 3: Add Files
```bash
git add .
```

This will add only the files NOT in `.gitignore` (~200MB).

### Step 4: Check What Will Be Committed
```bash
git status
```

You should see:
- ✅ Source files
- ✅ Config files
- ✅ Documentation
- ❌ NOT tools-collection/
- ❌ NOT node_modules/

### Step 5: Commit
```bash
git commit -m "Initial commit - MarketingTool.Pro deployment ready"
```

### Step 6: Add Remote (if not done)
```bash
git remote add origin https://github.com/YOUR_USERNAME/marketingtool-pro.git
```

Replace `YOUR_USERNAME` with your GitHub username.

### Step 7: Push
```bash
git push -u origin main
```

Or if your default branch is `master`:
```bash
git push -u origin master
```

---

## �� Expected Size

After excluding large files:
- **Before**: 22 GB
- **After**: ~200 MB
- **Time to push**: 2-5 minutes

---

## 🔄 GitHub Actions Auto-Deploy

Once pushed, GitHub Actions will automatically:
1. Build Docker image using Docker Build Cloud
2. Push image to Docker Hub
3. Deploy to VPS (31.220.107.19)

**Note**: You need to set GitHub Secrets first:
- `DOCKER_PAT` - Docker Hub token
- `VPS_PASSWORD` - `Lokendra@9078`

---

## 💡 Alternative: Keep Everything Local

If you don't want to push to GitHub:
- ✅ Your site is already live on VPS
- ✅ Docker image has everything
- ✅ You can rebuild anytime with `docker build`

---

## 🐛 Troubleshooting

### "Repository too large"
```bash
# Check what's being added
git status
du -sh * | sort -h
```

### "File too large"
Add it to `.gitignore`:
```bash
echo "large-file.zip" >> .gitignore
git rm --cached large-file.zip
git commit -m "Remove large file"
```

### Remove accidentally committed large files
```bash
# Remove from git but keep locally
git rm --cached -r tools-collection/
git commit -m "Remove large directory"
```

---

## ✅ Summary

Run these commands:
```bash
cd /Users/loken/Projects/marketingtool-pro
git add .
git commit -m "Production deployment ready"
git remote add origin https://github.com/YOUR_USERNAME/marketingtool-pro.git
git push -u origin main
```

Large files stay on VPS, source code goes to GitHub! 🚀
