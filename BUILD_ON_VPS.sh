#!/bin/bash

echo "🚀 Building MarketingTool.Pro directly on VPS..."

VPS="root@31.220.107.19"

# Copy files to VPS
echo "📤 Copying files to VPS..."
scp Dockerfile ${VPS}:/opt/marketingtool-pro/
scp -r src ${VPS}:/opt/marketingtool-pro/
scp -r public ${VPS}:/opt/marketingtool-pro/
scp package*.json ${VPS}:/opt/marketingtool-pro/
scp astro.config.mjs ${VPS}:/opt/marketingtool-pro/
scp tsconfig.json ${VPS}:/opt/marketingtool-pro/

echo ""
echo "🔨 Building Docker image on VPS (takes 5-10 minutes)..."

# Build and run on VPS
ssh ${VPS} << 'ENDSSH'
cd /opt/marketingtool-pro

# Stop and remove old container
docker stop marketingtool-pro 2>/dev/null || true
docker rm marketingtool-pro 2>/dev/null || true

# Build new image
docker build -t marketingtool-pro:latest .

# Run container
docker run -d \
  --name marketingtool-pro \
  --restart unless-stopped \
  -p 3000:4321 \
  marketingtool-pro:latest

echo ""
echo "✅ Container started!"
docker ps | grep marketingtool
echo ""
echo "⏳ Waiting 10 seconds for startup..."
sleep 10
echo ""
echo "📊 Recent logs:"
docker logs marketingtool-pro --tail 20
ENDSSH

echo ""
echo "✅ Deployment complete!"
echo ""
echo "🌐 Your site: http://31.220.107.19:3000"
echo ""
echo "📊 View logs: ssh root@31.220.107.19 'docker logs marketingtool-pro -f'"
