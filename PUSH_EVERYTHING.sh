#!/bin/bash

echo "🚀 Pushing ALL files to GitHub (including 20GB)..."
echo ""
echo "This will:"
echo "  ✅ Push tools-collection/ (20GB)"
echo "  ✅ Push wp-content/ (13MB)"
echo "  ✅ Push production-html/ (11MB)"
echo "  ✅ Push all other files"
echo ""
echo "Time: 30-60 minutes"
echo ""
read -p "Continue? (yes/no): " answer

if [ "$answer" != "yes" ]; then
    echo "Cancelled."
    exit 1
fi

echo ""
echo "Step 1: Adding all files..."
git add .

echo ""
echo "Step 2: Committing..."
git commit -m "Add all files - complete repository with all 128,906 files"

echo ""
echo "Step 3: Pushing to GitHub (this will take 30-60 minutes)..."
git push

echo ""
echo "✅ Done! All files are now on GitHub!"
echo ""
echo "You can now clone this repo anywhere:"
echo "  git clone https://github.com/Lokeninfinitypoint/marketingtool-pro.git"
