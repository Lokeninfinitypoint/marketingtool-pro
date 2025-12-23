# 🧹 CLEANUP PLAN - Keep Only 2 Repos

**Goal**: Keep only `marketingtool-pro` and `antiviruspoint`

---

## ✅ KEEP THESE (Final 2 Repos)

### 1. marketingtool-pro
**Location**: `/Users/loken/Projects/marketingtool-pro/`
**GitHub**: `https://github.com/Lokeninfinitypoint/marketingtool-pro`
**Status**: ✅ All files consolidated here (22GB)
**Contains**:
- Astro frontend
- WordPress themes
- Tools collection (18,789 pages)
- All 13 repos merged here

### 2. antiviruspoint
**Location**: TBD (wherever it is)
**Status**: ✅ Keep untouched
**Separate project**

---

## ❌ DELETE THESE (Duplicate Repos)

After confirming marketingtool-pro has everything:

### Local Directories to Delete:
```bash
# 1. Desktop duplicates
rm -rf ~/Desktop/aitool-software/
rm -rf ~/Desktop/marketingtool-pro/

# 2. Downloads duplicates
rm -rf ~/Downloads/marketingtool_website_complete/
rm -rf ~/Downloads/marketingtool-website-v2/

# 3. Root directory duplicates
rm -rf ~/addswebstorm/
rm -rf ~/adswebstrom/

# 4. GitHub collected duplicates
rm -rf ~/github-collected/addswebstorm/
rm -rf ~/github-collected/marketingtool-pro/

# 5. Projects duplicates
rm -rf ~/Projects/marketingtool-dashboard/
rm -rf ~/Projects/marketingtool-deploy/
rm -rf ~/Projects/marketingtool-pro-backup/
```

### GitHub Repos to Delete (if you have multiple):
Go to each repo on GitHub:
1. Settings → Danger Zone → Delete this repository
2. Type the repo name to confirm

**Keep only**: `Lokeninfinitypoint/marketingtool-pro`

---

## 🔧 Fix Current Git Issue

Your local has all files, but GitHub has different history.

**OPTION 1: Force Push (Recommended)**
```bash
cd /Users/loken/Projects/marketingtool-pro
git push -u origin main --force
```
This replaces GitHub with your local version (all files included).

**OPTION 2: Pull & Merge**
```bash
cd /Users/loken/Projects/marketingtool-pro
git pull origin main --allow-unrelated-histories
git add .
git commit -m "Merge and consolidate all repos"
git push -u origin main
```

---

## 📊 Verification Checklist

Before deleting duplicate repos:

### Check marketingtool-pro has everything:
```bash
cd /Users/loken/Projects/marketingtool-pro

# Check size
du -sh .
# Should be: 22GB+

# Check file count
find . -type f | grep -v node_modules | wc -l
# Should be: 49,000+

# Check key directories exist
ls -la src/ public/ tools-collection/ wp-content/

# Check Docker image exists
docker images | grep marketingtool-pro
# Should show: marketingtool-pro:latest

# Verify site is live
curl http://31.220.107.19:3000
# Should return HTML
```

### All checks pass? ✅
Then it's safe to delete duplicate repos!

---

## 🚀 Final Structure

After cleanup, you'll have:

```
/Users/loken/
├── Projects/
│   └── marketingtool-pro/          ← KEEP (22GB, all files)
│
└── antiviruspoint/                  ← KEEP (separate project)
```

Clean and simple! 🎉

---

## 📝 Step-by-Step Cleanup

### 1. Fix Git Push
```bash
cd /Users/loken/Projects/marketingtool-pro
git push -u origin main --force
```

### 2. Verify on GitHub
Go to: https://github.com/Lokeninfinitypoint/marketingtool-pro
Check that all files are there (excluding .gitignore items)

### 3. Run Cleanup Script
```bash
cd /Users/loken/Projects/marketingtool-pro
./DELETE_DUPLICATE_REPOS.sh
```

### 4. Verify Cleanup
```bash
ls ~/Desktop/          # Should not see aitool-software or marketingtool-pro
ls ~/Projects/         # Should only see marketingtool-pro
ls ~/github-collected/ # Should not see marketing repos
```

---

## ✅ Summary

**Before**: 13+ repos scattered everywhere (confusing!)
**After**: 2 clean repos (marketingtool-pro + antiviruspoint)

**Local**: 22GB in one place
**GitHub**: Clean repo with source code
**VPS**: Running live at http://31.220.107.19:3000

Everything organized! 🎯
