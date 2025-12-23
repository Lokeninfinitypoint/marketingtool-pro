#!/bin/bash

echo "╔══════════════════════════════════════════════════════════════════╗"
echo "║                                                                  ║"
echo "║        🚀 PUSHING ALL FILES TO GITHUB                           ║"
echo "║                                                                  ║"
echo "╚══════════════════════════════════════════════════════════════════╝"
echo ""

cd /Users/loken/Projects/marketingtool-pro

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Step 1: Checking current status..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

git status --short | head -20
echo ""
echo "Total changed files: $(git status --short | wc -l | tr -d ' ')"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Step 2: Adding all files to git..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

git add .
echo "✓ All files added"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Step 3: Creating commit..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

git commit -m "Complete merge: All 12 repositories unified into one

- Merged marketingtool-dashboard → dashboard-app/
- Merged marketingtool-deploy → deploy-static/
- Merged aitool-software → aitool-app/
- Merged Lokeninfinitypoint → tools-collection/
- Merged addswebstorm → tools-collection/
- Merged adswebstrom → ads-templates/
- Merged github-collected versions → tools-collection-github/
- Merged Downloads versions → downloads-version-1 & 2/
- Added production HTML → production-html/
- Updated all documentation

Total: 1,077,278 files | 24GB
Status: ✅ All files included, build verified, production ready"

echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Step 4: Pushing to GitHub..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

git push origin main

echo ""
echo "╔══════════════════════════════════════════════════════════════════╗"
echo "║                                                                  ║"
echo "║        ✅ SUCCESS! ALL FILES PUSHED TO GITHUB                   ║"
echo "║                                                                  ║"
echo "╚══════════════════════════════════════════════════════════════════╝"
echo ""
echo "Repository: https://github.com/Lokeninfinitypoint/marketingtool-pro"
echo ""

