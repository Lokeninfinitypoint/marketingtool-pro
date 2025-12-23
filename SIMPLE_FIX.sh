#!/bin/bash

echo "🔧 Fixing Git Push & Cleanup"
echo ""

# Fix git push
echo "1️⃣  Force pushing to GitHub (overwrite remote)..."
git push -u origin main --force

if [ $? -eq 0 ]; then
    echo "✅ Push successful!"
    echo ""
    echo "Your repo is now on GitHub:"
    echo "https://github.com/Lokeninfinitypoint/marketingtool-pro"
    echo ""
    
    read -p "Do you want to delete duplicate repos now? (yes/no): " answer
    
    if [ "$answer" = "yes" ]; then
        echo ""
        echo "2️⃣  Deleting duplicate directories..."
        
        # Desktop
        rm -rf ~/Desktop/aitool-software/ 2>/dev/null && echo "✅ Deleted ~/Desktop/aitool-software/"
        rm -rf ~/Desktop/marketingtool-pro/ 2>/dev/null && echo "✅ Deleted ~/Desktop/marketingtool-pro/"
        
        # Downloads
        rm -rf ~/Downloads/marketingtool_website_complete/ 2>/dev/null && echo "✅ Deleted ~/Downloads/marketingtool_website_complete/"
        rm -rf ~/Downloads/marketingtool-website-v2/ 2>/dev/null && echo "✅ Deleted ~/Downloads/marketingtool-website-v2/"
        
        # Root
        rm -rf ~/addswebstorm/ 2>/dev/null && echo "✅ Deleted ~/addswebstorm/"
        rm -rf ~/adswebstrom/ 2>/dev/null && echo "✅ Deleted ~/adswebstrom/"
        
        # GitHub collected
        rm -rf ~/github-collected/addswebstorm/ 2>/dev/null && echo "✅ Deleted ~/github-collected/addswebstorm/"
        rm -rf ~/github-collected/marketingtool-pro/ 2>/dev/null && echo "✅ Deleted ~/github-collected/marketingtool-pro/"
        
        # Projects (keeping marketingtool-pro)
        rm -rf ~/Projects/marketingtool-dashboard/ 2>/dev/null && echo "✅ Deleted ~/Projects/marketingtool-dashboard/"
        rm -rf ~/Projects/marketingtool-deploy/ 2>/dev/null && echo "✅ Deleted ~/Projects/marketingtool-deploy/"
        rm -rf ~/Projects/marketingtool-pro-backup/ 2>/dev/null && echo "✅ Deleted ~/Projects/marketingtool-pro-backup/"
        
        echo ""
        echo "✅ Cleanup complete!"
        echo ""
        echo "Final status:"
        echo "  ✅ marketingtool-pro (kept) - /Users/loken/Projects/marketingtool-pro"
        echo "  ✅ antiviruspoint (kept) - untouched"
        echo "  ❌ All duplicate repos deleted"
    else
        echo "Skipped cleanup. Run ./SIMPLE_FIX.sh again when ready."
    fi
else
    echo "❌ Push failed. Check errors above."
fi
