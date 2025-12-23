#!/bin/bash
# Quick push script - use when you make changes

echo "🚀 Quick Push to GitHub"
echo ""

# Show what changed
echo "Files changed:"
git status --short

echo ""
read -p "Enter commit message (or press Enter for 'Quick update'): " message

if [ -z "$message" ]; then
    message="Quick update"
fi

echo ""
echo "Pushing to GitHub..."
git add .
git commit -m "$message"
git push

echo ""
echo "✅ Done! Changes are on GitHub."
echo "View: https://github.com/Lokeninfinitypoint/marketingtool-pro"
