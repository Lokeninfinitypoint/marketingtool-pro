#!/bin/bash

echo "╔══════════════════════════════════════════════════════════════════╗"
echo "║                                                                  ║"
echo "║          📊 WHERE IS YOUR 24GB DATA?                            ║"
echo "║                                                                  ║"
echo "╚══════════════════════════════════════════════════════════════════╝"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "LOCAL PROJECT SIZE"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

TOTAL_SIZE=$(du -sh /Users/loken/Projects/marketingtool-pro 2>/dev/null | cut -f1)
echo "Total Project: $TOTAL_SIZE"
echo ""

echo "Top directories by size:"
du -sh /Users/loken/Projects/marketingtool-pro/*/ 2>/dev/null | sort -hr | head -15

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "WHAT'S ON GITHUB?"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Check .gitignore
echo "Checking what's excluded from GitHub (.gitignore):"
if [ -f ".gitignore" ]; then
    echo ""
    grep -v "^#" .gitignore | grep -v "^$" | head -20
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "GITHUB REPOSITORY SIZE"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Check what's actually tracked by git
echo "Git tracked files size:"
git ls-files | xargs -I {} du -ch {} 2>/dev/null | tail -1

echo ""
echo "Large files in git:"
git ls-files | xargs -I {} du -h {} 2>/dev/null | sort -hr | head -20

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "SUMMARY"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Check if node_modules is tracked
if git ls-files | grep -q "node_modules"; then
    echo "⚠️  WARNING: node_modules is being tracked by git!"
    echo "   This should be in .gitignore"
else
    echo "✓ node_modules is NOT tracked (correct)"
fi

echo ""

# Check .git folder size
if [ -d ".git" ]; then
    GIT_SIZE=$(du -sh .git 2>/dev/null | cut -f1)
    echo "Git repository metadata (.git folder): $GIT_SIZE"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

