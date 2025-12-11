#!/bin/bash

# MarketingTool.Pro Deployment Script
# This script builds and deploys the Astro application to Cloudflare Workers

echo "🚀 Starting deployment of MarketingTool.Pro..."

# Build the application
echo "📦 Building the application..."
npm run build

if [ $? -ne 0 ]; then
    echo "❌ Build failed!"
    exit 1
fi

# Deploy to Cloudflare
echo "☁️  Deploying to Cloudflare Workers..."
npx wrangler deploy

if [ $? -eq 0 ]; then
    echo "✅ Deployment successful!"
    echo "🌐 Your app is live at: https://marketingtool-pro.madav6310.workers.dev"
else
    echo "❌ Deployment failed!"
    exit 1
fi
