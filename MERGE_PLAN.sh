#!/bin/bash
# Merge all MarketingTool repositories into one unified project

set -e

echo "=== Starting Repository Merge ==="

# 1. Extract marketingtool_live.zip (production HTML)
echo "1. Extracting production HTML from marketingtool_live.zip..."
mkdir -p ./production-html
unzip -q /Users/loken/marketingtool_live.zip -d ./production-html 2>/dev/null || true
mv ./production-html/var/www/marketingtool.pro/* ./production-html/ 2>/dev/null || true
rm -rf ./production-html/var 2>/dev/null || true

# 2. Copy marketingtool-dashboard (Next.js)
echo "2. Merging marketingtool-dashboard..."
mkdir -p ./dashboard-app
cp -r /Users/loken/Projects/marketingtool-dashboard/src ./dashboard-app/
cp -r /Users/loken/Projects/marketingtool-dashboard/public ./dashboard-app/ 2>/dev/null || true
cp /Users/loken/Projects/marketingtool-dashboard/package.json ./dashboard-app/ 2>/dev/null || true
cp /Users/loken/Projects/marketingtool-dashboard/next.config.js ./dashboard-app/ 2>/dev/null || true
cp /Users/loken/Projects/marketingtool-dashboard/tsconfig.json ./dashboard-app/ 2>/dev/null || true
cp /Users/loken/Projects/marketingtool-dashboard/tailwind.config.js ./dashboard-app/ 2>/dev/null || true

# 3. Copy marketingtool-deploy (static HTML)
echo "3. Merging marketingtool-deploy static files..."
mkdir -p ./deploy-static
cp -r /Users/loken/Projects/marketingtool-deploy/*.html ./deploy-static/ 2>/dev/null || true
cp -r /Users/loken/Projects/marketingtool-deploy/css ./deploy-static/ 2>/dev/null || true
cp -r /Users/loken/Projects/marketingtool-deploy/js ./deploy-static/ 2>/dev/null || true
cp -r /Users/loken/Projects/marketingtool-deploy/images ./deploy-static/ 2>/dev/null || true
cp -r /Users/loken/Projects/marketingtool-deploy/fonts ./deploy-static/ 2>/dev/null || true
cp -r /Users/loken/Projects/marketingtool-deploy/integration ./deploy-static/ 2>/dev/null || true
cp -r /Users/loken/Projects/marketingtool-deploy/react-app ./deploy-static/ 2>/dev/null || true

# 4. Copy aitool-software (Next.js app)
echo "4. Merging aitool-software..."
mkdir -p ./aitool-app
cp -r /Users/loken/Desktop/aitool-software/src ./aitool-app/
cp -r /Users/loken/Desktop/aitool-software/public ./aitool-app/ 2>/dev/null || true
cp /Users/loken/Desktop/aitool-software/package.json ./aitool-app/ 2>/dev/null || true
cp /Users/loken/Desktop/aitool-software/next.config.js ./aitool-app/ 2>/dev/null || true
cp /Users/loken/Desktop/aitool-software/tsconfig.json ./aitool-app/ 2>/dev/null || true
cp /Users/loken/Desktop/aitool-software/tailwind.config.js ./aitool-app/ 2>/dev/null || true

# 5. Copy Lokeninfinitypoint tools (880+ HTML files)
echo "5. Merging Lokeninfinitypoint tools..."
mkdir -p ./tools-collection
cp -r /Users/loken/addswebstorm/Lokeninfinitypoint/* ./tools-collection/ 2>/dev/null || true

# 6. Copy adswebstrom templates
echo "6. Merging adswebstrom templates..."
mkdir -p ./ads-templates
cp -r /Users/loken/adswebstrom/ads-template ./ads-templates/ 2>/dev/null || true
cp /Users/loken/adswebstrom/package.json ./ads-templates/ 2>/dev/null || true
cp /Users/loken/adswebstrom/*.config.js ./ads-templates/ 2>/dev/null || true

echo "=== Merge Complete ==="
echo "Structure:"
echo "  ./production-html/      - Live production HTML"
echo "  ./dashboard-app/        - Next.js dashboard"
echo "  ./deploy-static/        - Static deployment files"
echo "  ./aitool-app/           - AI Tool software"
echo "  ./tools-collection/     - 880+ marketing tools"
echo "  ./ads-templates/        - Ad templates"
echo "  ./src/                  - Current Astro source"
echo "  ./public/               - Current Astro public"
