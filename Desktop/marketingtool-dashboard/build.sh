#!/bin/bash
# MarketingTool.Pro - Build Script

echo "🏗️  Building MarketingTool.Pro"
echo "=============================="

# Clean previous builds
echo "🧹 Cleaning previous builds..."
rm -rf .next

# Generate Prisma Client
echo "🔨 Generating Prisma Client..."
npx prisma generate

# Build Next.js
echo "📦 Building Next.js application..."
npm run build

echo "✅ Build complete!"
