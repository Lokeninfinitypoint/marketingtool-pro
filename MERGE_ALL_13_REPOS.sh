#!/bin/bash
set -e

echo "╔══════════════════════════════════════════════════════════╗"
echo "║  MERGING ALL 13 REPOSITORIES INTO ONE                   ║"
echo "╚══════════════════════════════════════════════════════════╝"
echo ""

# Check for additional repos not yet merged
echo "Checking for additional repositories..."

# Desktop/marketingtool-pro
if [ -d "/Users/loken/Desktop/marketingtool-pro" ]; then
  echo "✓ Found: Desktop/marketingtool-pro"
  if [ -d "/Users/loken/Desktop/marketingtool-pro/src" ]; then
    echo "  Merging Desktop version..."
    cp -rn /Users/loken/Desktop/marketingtool-pro/src/* ./src/ 2>/dev/null || true
    cp -rn /Users/loken/Desktop/marketingtool-pro/public/* ./public/ 2>/dev/null || true
  fi
fi

# github-collected versions
if [ -d "/Users/loken/github-collected/marketingtool-pro" ]; then
  echo "✓ Found: github-collected/marketingtool-pro"
  if [ -d "/Users/loken/github-collected/marketingtool-pro/src" ]; then
    echo "  Merging github-collected version..."
    cp -rn /Users/loken/github-collected/marketingtool-pro/src/* ./src/ 2>/dev/null || true
  fi
fi

# github-collected/addswebstorm
if [ -d "/Users/loken/github-collected/addswebstorm" ]; then
  echo "✓ Found: github-collected/addswebstorm"
  if [ ! -d "./tools-collection-github" ]; then
    mkdir -p ./tools-collection-github
    echo "  Merging github-collected/addswebstorm..."
    cp -r /Users/loken/github-collected/addswebstorm/* ./tools-collection-github/ 2>/dev/null || true
  fi
fi

# Downloads versions
if [ -d "/Users/loken/Downloads/marketingtool_website_complete" ]; then
  echo "✓ Found: Downloads/marketingtool_website_complete"
  mkdir -p ./downloads-version-1
  cp -r /Users/loken/Downloads/marketingtool_website_complete/* ./downloads-version-1/ 2>/dev/null || true
fi

if [ -d "/Users/loken/Downloads/marketingtool-website-v2" ]; then
  echo "✓ Found: Downloads/marketingtool-website-v2"
  mkdir -p ./downloads-version-2
  cp -r /Users/loken/Downloads/marketingtool-website-v2/* ./downloads-version-2/ 2>/dev/null || true
fi

echo ""
echo "╔══════════════════════════════════════════════════════════╗"
echo "║  ALL REPOSITORIES MERGED                                 ║"
echo "╚══════════════════════════════════════════════════════════╝"
