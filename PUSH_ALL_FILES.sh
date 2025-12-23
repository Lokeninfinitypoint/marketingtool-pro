#!/bin/bash

echo "⚠️  WARNING: This will push ALL 22GB to GitHub!"
echo ""
echo "This includes:"
echo "  - tools-collection/ (20GB)"
echo "  - production-html/ (11MB)"
echo "  - wp-content/ (13MB)"
echo ""
echo "This will take 2-4 hours and may hit GitHub limits."
echo ""
read -p "Are you SURE you want to do this? (type YES): " answer

if [ "$answer" = "YES" ]; then
    echo ""
    echo "🔧 Removing items from .gitignore..."
    
    # Backup .gitignore
    cp .gitignore .gitignore.backup
    
    # Remove exclusions
    sed -i '' '/tools-collection/d' .gitignore
    sed -i '' '/production-html/d' .gitignore
    sed -i '' '/wp-content/d' .gitignore
    
    echo "✅ Updated .gitignore"
    echo ""
    echo "📤 Adding large files..."
    git add tools-collection/ production-html/ wp-content/
    
    echo ""
    echo "💾 Committing..."
    git commit -m "Add all files including large directories"
    
    echo ""
    echo "🚀 Pushing to GitHub (this will take a while)..."
    git push
    
    echo ""
    echo "✅ Done! All files are now on GitHub."
else
    echo "Cancelled. Keeping current setup."
fi
