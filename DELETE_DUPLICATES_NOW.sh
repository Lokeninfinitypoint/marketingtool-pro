#!/bin/bash

echo "🗑️  Deleting duplicate repos..."
echo ""
echo "Keeping:"
echo "  ✅ /Users/loken/Projects/marketingtool-pro"
echo "  ✅ antiviruspoint (untouched)"
echo ""
echo "Deleting duplicates in 5 seconds... (Ctrl+C to cancel)"
sleep 5

echo ""
echo "Deleting..."

# Desktop
[ -d ~/Desktop/aitool-software ] && rm -rf ~/Desktop/aitool-software && echo "✅ Deleted ~/Desktop/aitool-software"
[ -d ~/Desktop/marketingtool-pro ] && rm -rf ~/Desktop/marketingtool-pro && echo "✅ Deleted ~/Desktop/marketingtool-pro"

# Downloads
[ -d ~/Downloads/marketingtool_website_complete ] && rm -rf ~/Downloads/marketingtool_website_complete && echo "✅ Deleted ~/Downloads/marketingtool_website_complete"
[ -d ~/Downloads/marketingtool-website-v2 ] && rm -rf ~/Downloads/marketingtool-website-v2 && echo "✅ Deleted ~/Downloads/marketingtool-website-v2"

# Root
[ -d ~/addswebstorm ] && rm -rf ~/addswebstorm && echo "✅ Deleted ~/addswebstorm"
[ -d ~/adswebstrom ] && rm -rf ~/adswebstrom && echo "✅ Deleted ~/adswebstrom"

# GitHub collected
[ -d ~/github-collected/addswebstorm ] && rm -rf ~/github-collected/addswebstorm && echo "✅ Deleted ~/github-collected/addswebstorm"
[ -d ~/github-collected/marketingtool-pro ] && rm -rf ~/github-collected/marketingtool-pro && echo "✅ Deleted ~/github-collected/marketingtool-pro"

# Projects
[ -d ~/Projects/marketingtool-dashboard ] && rm -rf ~/Projects/marketingtool-dashboard && echo "✅ Deleted ~/Projects/marketingtool-dashboard"
[ -d ~/Projects/marketingtool-deploy ] && rm -rf ~/Projects/marketingtool-deploy && echo "✅ Deleted ~/Projects/marketingtool-deploy"
[ -d ~/Projects/marketingtool-pro-backup ] && rm -rf ~/Projects/marketingtool-pro-backup && echo "✅ Deleted ~/Projects/marketingtool-pro-backup"

echo ""
echo "✅ Cleanup complete!"
echo ""
echo "Final structure:"
echo "  ✅ /Users/loken/Projects/marketingtool-pro (KEPT)"
echo "  ✅ antiviruspoint (KEPT)"
echo "  ❌ All duplicates deleted"
echo ""
echo "You now have 2 clean repos! 🎉"
