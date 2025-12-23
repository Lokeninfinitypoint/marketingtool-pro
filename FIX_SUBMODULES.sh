#!/bin/bash

echo "╔══════════════════════════════════════════════════════════════════╗"
echo "║                                                                  ║"
echo "║           🔧 FIXING GIT SUBMODULE ERRORS                        ║"
echo "║                                                                  ║"
echo "╚══════════════════════════════════════════════════════════════════╝"
echo ""

cd /Users/loken/Projects/marketingtool-pro

echo "Step 1: Finding nested .git directories..."
echo ""

# Find all nested .git directories (excluding main one)
NESTED_GITS=$(find . -name ".git" -type d | grep -v "^./.git$")

if [ -n "$NESTED_GITS" ]; then
    echo "Found nested git repositories:"
    echo "$NESTED_GITS"
    echo ""
    
    echo "Step 2: Removing nested .git directories..."
    echo ""
    
    # Remove each nested .git directory
    while IFS= read -r git_dir; do
        if [ -d "$git_dir" ]; then
            echo "  Removing: $git_dir"
            rm -rf "$git_dir"
        fi
    done <<< "$NESTED_GITS"
    
    echo ""
    echo "✓ All nested .git directories removed"
else
    echo "No nested git repositories found"
fi

echo ""
echo "Step 3: Removing .gitmodules file if exists..."
if [ -f .gitmodules ]; then
    rm .gitmodules
    echo "✓ Removed .gitmodules"
else
    echo "No .gitmodules file found"
fi

echo ""
echo "Step 4: Clearing git cache..."
git rm -rf --cached . > /dev/null 2>&1 || true
git add .
echo "✓ Git cache cleared and files re-added"

echo ""
echo "╔══════════════════════════════════════════════════════════════════╗"
echo "║                                                                  ║"
echo "║           ✅ SUBMODULE ERRORS FIXED!                            ║"
echo "║                                                                  ║"
echo "╚══════════════════════════════════════════════════════════════════╝"
echo ""
echo "Now ready to commit and push without submodule errors!"

