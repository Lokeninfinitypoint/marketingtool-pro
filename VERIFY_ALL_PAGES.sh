#!/bin/bash

echo "╔══════════════════════════════════════════════════════════════════╗"
echo "║                                                                  ║"
echo "║      ✅ VERIFYING ALL PAGES ARE ON GITHUB                       ║"
echo "║                                                                  ║"
echo "╚══════════════════════════════════════════════════════════════════╝"
echo ""

cd /Users/loken/Projects/marketingtool-pro

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "STEP 1: Counting all HTML pages locally"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "Counting HTML files in each directory..."
echo ""

echo "tools-collection/:"
TOOLS=$(find tools-collection -name "*.html" 2>/dev/null | wc -l | tr -d ' ')
echo "  $TOOLS HTML pages"

echo ""
echo "tools-collection-github/:"
TOOLS_GITHUB=$(find tools-collection-github -name "*.html" 2>/dev/null | wc -l | tr -d ' ')
echo "  $TOOLS_GITHUB HTML pages"

echo ""
echo "madgicx-app/:"
MADGICX=$(find madgicx-app -name "*.html" 2>/dev/null | wc -l | tr -d ' ')
echo "  $MADGICX HTML pages"

echo ""
echo "production-html/:"
PRODUCTION=$(find production-html -name "*.html" 2>/dev/null | wc -l | tr -d ' ')
echo "  $PRODUCTION HTML pages"

echo ""
echo "ads-templates/:"
ADS=$(find ads-templates -name "*.html" 2>/dev/null | wc -l | tr -d ' ')
echo "  $ADS HTML pages"

echo ""
TOTAL=$((TOOLS + TOOLS_GITHUB + MADGICX + PRODUCTION + ADS))
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "TOTAL HTML PAGES: $TOTAL"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "STEP 2: Checking what's tracked by Git"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

GIT_HTML=$(git ls-files | grep "\.html$" | wc -l | tr -d ' ')
echo "HTML files tracked by Git: $GIT_HTML"

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "STEP 3: Checking for untracked files"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

UNTRACKED=$(git status --porcelain | grep "^??" | wc -l | tr -d ' ')
if [ "$UNTRACKED" -gt 0 ]; then
    echo "⚠️  Found $UNTRACKED untracked files"
    echo ""
    echo "Sample untracked files:"
    git status --porcelain | grep "^??" | head -10
else
    echo "✓ No untracked files"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "STEP 4: Checking for uncommitted changes"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

MODIFIED=$(git status --porcelain | grep "^ M" | wc -l | tr -d ' ')
if [ "$MODIFIED" -gt 0 ]; then
    echo "⚠️  Found $MODIFIED modified files not committed"
else
    echo "✓ No uncommitted changes"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "SUMMARY"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "Local HTML pages:     $TOTAL"
echo "Git tracked HTML:     $GIT_HTML"
echo "Untracked files:      $UNTRACKED"
echo "Uncommitted changes:  $MODIFIED"
echo ""

if [ "$UNTRACKED" -eq 0 ] && [ "$MODIFIED" -eq 0 ]; then
    echo "✅ STATUS: ALL FILES ARE TRACKED AND COMMITTED!"
    echo "✅ READY TO PUSH TO GITHUB"
else
    echo "⚠️  STATUS: SOME FILES NEED TO BE ADDED/COMMITTED"
    echo "⚠️  RUN: git add . && git commit && git push"
fi

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

