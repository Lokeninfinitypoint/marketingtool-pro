#!/bin/bash

echo "🔧 Disabling GitHub Actions..."
echo ""

# Remove workflow file
if [ -f .github/workflows/docker-build-cloud.yml ]; then
    rm .github/workflows/docker-build-cloud.yml
    echo "✅ Removed workflow file"
fi

# Commit and push
git add .github/workflows/
git commit -m "Disable GitHub Actions - deploy manually"
git push

echo ""
echo "✅ GitHub Actions disabled!"
echo ""
echo "Your site is still live at: http://31.220.107.19:3000"
echo ""
echo "To update your site, just run:"
echo "  ssh root@31.220.107.19"
echo "  cd /opt/marketingtool-pro"
echo "  docker build -t marketingtool-pro:latest ."
echo "  docker restart marketingtool-pro"
