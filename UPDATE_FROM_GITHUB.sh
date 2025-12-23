#!/bin/bash
set -e

echo "=== Updating from GitHub Repository ==="

# Backup current tools-collection
echo "1. Backing up current tools-collection..."
if [ -d "tools-collection-old" ]; then
  rm -rf tools-collection-old
fi
mv tools-collection tools-collection-old

# Copy fresh from source
echo "2. Copying latest from GitHub source..."
cp -r /Users/loken/addswebstorm/Lokeninfinitypoint tools-collection

# Copy any new files that might be in the GitHub repo
echo "3. Checking for new documentation..."
cp /Users/loken/addswebstorm/Lokeninfinitypoint/CONSOLIDATION_COMPLETE.md . 2>/dev/null || true
cp /Users/loken/addswebstorm/Lokeninfinitypoint/.nojekyll . 2>/dev/null || true

# Count files
FILE_COUNT=$(find tools-collection -type f | wc -l | tr -d ' ')
DIR_COUNT=$(find tools-collection -maxdepth 1 -type d | wc -l | tr -d ' ')

echo ""
echo "=== Update Complete ==="
echo "Files: $FILE_COUNT"
echo "Directories: $DIR_COUNT"
echo ""
echo "✅ All GitHub repository files now in tools-collection/"

