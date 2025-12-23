#!/bin/bash

echo "🔧 Fixing GitHub Pages build error..."
echo ""

# Create .nojekyll to disable Jekyll
touch .nojekyll
echo "✅ Created .nojekyll"

# Disable GitHub Pages if enabled
echo ""
echo "Now do this manually:"
echo "1. Go to: https://github.com/Lokeninfinitypoint/marketingtool-pro/settings/pages"
echo "2. Under 'Build and deployment' → Source"
echo "3. Select: 'None' (disable GitHub Pages)"
echo "4. Save"
echo ""
echo "OR keep GitHub Pages but it won't affect your site."
echo "Your site is live on VPS: http://31.220.107.19:3000"
echo ""

# Commit and push
git add .nojekyll
git commit -m "Disable Jekyll - this is an Astro site"
git push

echo ""
echo "✅ Done! GitHub will stop trying to build with Jekyll."
