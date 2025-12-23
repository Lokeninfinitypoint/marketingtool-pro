#!/bin/bash

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║                                                                ║"
echo "║  🗑️  DELETE ALL DUPLICATE REPOSITORIES                        ║"
echo "║                                                                ║"
echo "║  This will DELETE 10 duplicate repositories.                  ║"
echo "║  KEEP ONLY: /Users/loken/Projects/marketingtool-pro/         ║"
echo "║                                                                ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

REPOS_TO_DELETE=(
  "/Users/loken/Projects/marketingtool-dashboard"
  "/Users/loken/Projects/marketingtool-deploy"
  "/Users/loken/Projects/marketingtool-pro-backup"
  "/Users/loken/Desktop/aitool-software"
  "/Users/loken/Desktop/marketingtool-pro"
  "/Users/loken/addswebstorm"
  "/Users/loken/adswebstrom"
  "/Users/loken/github-collected/addswebstorm"
  "/Users/loken/github-collected/marketingtool-pro"
  "/Users/loken/Downloads/marketingtool_website_complete"
  "/Users/loken/Downloads/marketingtool-website-v2"
)

echo "The following directories will be PERMANENTLY DELETED:"
echo ""
for repo in "${REPOS_TO_DELETE[@]}"; do
  if [ -d "$repo" ]; then
    SIZE=$(du -sh "$repo" 2>/dev/null | cut -f1)
    echo "  ❌ $repo ($SIZE)"
  fi
done

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "⚠️  WARNING: This action CANNOT be undone!"
echo ""
echo "✅ SAFE: /Users/loken/Projects/marketingtool-pro/ (WILL BE KEPT)"
echo ""
read -p "Type 'DELETE' to confirm deletion: " confirmation

if [ "$confirmation" = "DELETE" ]; then
  echo ""
  echo "Deleting duplicate repositories..."
  for repo in "${REPOS_TO_DELETE[@]}"; do
    if [ -d "$repo" ]; then
      echo "  🗑️  Deleting: $repo"
      rm -rf "$repo"
      echo "     ✓ Deleted"
    fi
  done
  echo ""
  echo "╔════════════════════════════════════════════════════════════════╗"
  echo "║  ✅ ALL DUPLICATE REPOSITORIES DELETED                        ║"
  echo "║  ✅ ONLY ONE REPOSITORY REMAINS: marketingtool-pro            ║"
  echo "╚════════════════════════════════════════════════════════════════╝"
else
  echo ""
  echo "❌ Deletion cancelled. No files were deleted."
fi
