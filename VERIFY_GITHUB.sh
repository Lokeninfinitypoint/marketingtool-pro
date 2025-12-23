#!/bin/bash

echo "🔍 Verifying files are on GitHub..."
echo ""

# Create a test directory
TEST_DIR="/tmp/verify-github-$$"
mkdir -p "$TEST_DIR"

echo "📥 Cloning from GitHub (to verify)..."
git clone --depth 1 https://github.com/Lokeninfinitypoint/marketingtool-pro.git "$TEST_DIR" 2>&1 | grep -E "(Cloning|Receiving|Updating)"

echo ""
echo "Checking cloned repository..."
cd "$TEST_DIR"

echo ""
echo "✅ VERIFICATION RESULTS:"
echo "─────────────────────────────────────────────────────────"

# Check frontend
ASTRO=$(find src -name "*.astro" 2>/dev/null | wc -l | xargs)
TS=$(find src -name "*.ts" -o -name "*.tsx" 2>/dev/null | wc -l | xargs)
echo "Frontend files: $((ASTRO + TS)) files"
echo "  - Astro: $ASTRO"
echo "  - TypeScript: $TS"

# Check tools
if [ -d tools-collection ]; then
    TOOLS=$(find tools-collection -name "*.html" 2>/dev/null | wc -l | xargs)
    echo ""
    echo "Tools collection: $TOOLS HTML files"
else
    echo ""
    echo "❌ tools-collection/ NOT FOUND"
fi

# Check WordPress
if [ -d wp-content ]; then
    WP=$(find wp-content -name "*.php" 2>/dev/null | wc -l | xargs)
    THEMES=$(find wp-content/themes -maxdepth 1 -type d 2>/dev/null | tail -n +2 | wc -l | xargs)
    echo ""
    echo "WordPress: $THEMES themes ($WP PHP files)"
else
    echo ""
    echo "❌ wp-content/ NOT FOUND"
fi

# Total files
TOTAL=$(find . -type f | grep -v .git | wc -l | xargs)
echo ""
echo "─────────────────────────────────────────────────────────"
echo "Total files on GitHub: $TOTAL"

# Size
echo ""
echo "Repository size:"
du -sh . | awk '{print $1}'

# Cleanup
cd /
rm -rf "$TEST_DIR"

echo ""
echo "✅ Verification complete!"
