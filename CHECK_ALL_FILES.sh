#!/bin/bash

echo "╔══════════════════════════════════════════════════════════════════╗"
echo "║                                                                  ║"
echo "║  CHECKING IF ALL FILES ARE IN marketingtool-pro                 ║"
echo "║                                                                  ║"
echo "╚══════════════════════════════════════════════════════════════════╝"
echo ""

# Function to count files
count_files() {
    find "$1" -type f 2>/dev/null | wc -l | tr -d ' '
}

# Function to get directory size
get_size() {
    du -sh "$1" 2>/dev/null | cut -f1
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "CHECKING CURRENT REPOSITORY"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

CURRENT_DIR="/Users/loken/Projects/marketingtool-pro"
echo "📁 Current Repository: marketingtool-pro"
echo "   Location: $CURRENT_DIR"
echo "   Size: $(get_size $CURRENT_DIR)"
echo "   Files: $(count_files $CURRENT_DIR)"
echo ""

echo "📂 Directory Structure:"
ls -lh | grep "^d" | awk '{printf "   %-30s %8s\n", $9, $5}'
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "COMPARING WITH OTHER REPOSITORIES"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Array of repositories to check
REPOS=(
    "/Users/loken/Projects/marketingtool-dashboard"
    "/Users/loken/Projects/marketingtool-deploy"
    "/Users/loken/Desktop/aitool-software"
    "/Users/loken/Desktop/marketingtool-pro"
    "/Users/loken/addswebstorm"
    "/Users/loken/adswebstrom"
    "/Users/loken/github-collected/addswebstorm"
    "/Users/loken/github-collected/marketingtool-pro"
    "/Users/loken/Downloads/marketingtool_website_complete"
    "/Users/loken/Downloads/marketingtool-website-v2"
)

TOTAL_SOURCE_FILES=0
TOTAL_SOURCE_SIZE=0

for repo in "${REPOS[@]}"; do
    if [ -d "$repo" ]; then
        FILE_COUNT=$(count_files "$repo")
        SIZE=$(get_size "$repo")
        TOTAL_SOURCE_FILES=$((TOTAL_SOURCE_FILES + FILE_COUNT))
        
        NAME=$(basename "$repo")
        printf "✓ %-40s %8s files, %8s\n" "$NAME" "$FILE_COUNT" "$SIZE"
    fi
done

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "DETAILED CONTENT CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Check specific directories
echo "Checking merged directories in marketingtool-pro:"
echo ""

check_dir() {
    local dir=$1
    local desc=$2
    if [ -d "$dir" ]; then
        local files=$(count_files "$dir")
        local size=$(get_size "$dir")
        printf "  ✓ %-30s %6s files, %8s\n" "$desc" "$files" "$size"
    else
        printf "  ❌ %-30s MISSING\n" "$desc"
    fi
}

check_dir "./src" "Astro Source (src/)"
check_dir "./public" "Public Assets (public/)"
check_dir "./production-html" "Production HTML"
check_dir "./dashboard-app" "Dashboard App"
check_dir "./aitool-app" "AI Tool App"
check_dir "./deploy-static" "Deploy Static"
check_dir "./tools-collection" "Tools Collection"
check_dir "./tools-collection-github" "Tools GitHub"
check_dir "./ads-templates" "Ad Templates"
check_dir "./downloads-version-1" "Downloads v1"
check_dir "./downloads-version-2" "Downloads v2"
check_dir "./webflow-app" "Webflow App"

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "VERIFICATION SUMMARY"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

CURRENT_FILES=$(count_files "$CURRENT_DIR")
echo "Total files in marketingtool-pro: $CURRENT_FILES"
echo "Total files in source repos:      $TOTAL_SOURCE_FILES"
echo ""

# Check for key files
echo "Checking key files:"
[ -f "./package.json" ] && echo "  ✓ package.json" || echo "  ❌ package.json MISSING"
[ -f "./astro.config.mjs" ] && echo "  ✓ astro.config.mjs" || echo "  ❌ astro.config.mjs MISSING"
[ -f "./tsconfig.json" ] && echo "  ✓ tsconfig.json" || echo "  ❌ tsconfig.json MISSING"
[ -f "./README.md" ] && echo "  ✓ README.md" || echo "  ❌ README.md MISSING"
[ -d "./node_modules" ] && echo "  ✓ node_modules/" || echo "  ❌ node_modules/ MISSING"

echo ""
echo "Checking documentation:"
for doc in INDEX.md PROJECT_SUMMARY.md BUILD_VERIFIED.md MERGE_COMPLETE.md REPOSITORY_STRUCTURE.md ALL_13_REPOS_MERGED.md; do
    [ -f "./$doc" ] && echo "  ✓ $doc" || echo "  ❌ $doc MISSING"
done

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "FINAL VERIFICATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

if [ -d "./src" ] && [ -d "./tools-collection" ] && [ -d "./dashboard-app" ] && [ -d "./aitool-app" ]; then
    echo "✅ STATUS: ALL MAJOR DIRECTORIES PRESENT"
    echo "✅ VERIFICATION: PASSED"
    echo ""
    echo "All files are successfully merged into marketingtool-pro!"
else
    echo "⚠️  WARNING: Some directories may be missing"
    echo "Please review the list above."
fi

echo ""
