#!/bin/bash

# Fix Port 80 Conflict - MarketingTool.Pro
# VPS: 31.220.107.19

set -e

VPS_HOST="31.220.107.19"
VPS_USER="root"
DEPLOY_PATH="/opt/marketingtool-pro"

echo "🔧 Fixing Port 80 Conflict on VPS..."
echo ""

# Copy updated docker-compose
echo "📤 Copying updated docker-compose.yml..."
scp docker-compose.production.yml ${VPS_USER}@${VPS_HOST}:${DEPLOY_PATH}/

echo ""
echo "🔄 Restarting containers with new ports..."
ssh ${VPS_USER}@${VPS_HOST} << 'ENDSSH'
    cd /opt/marketingtool-pro
    
    # Stop all containers
    docker-compose -f docker-compose.production.yml down 2>/dev/null || true
    
    # Remove conflicting containers if any
    docker ps -a | grep -E '(marketingtool|nginx)' | awk '{print $1}' | xargs -r docker rm -f 2>/dev/null || true
    
    # Start with new configuration
    docker-compose -f docker-compose.production.yml up -d
    
    echo ""
    echo "✅ Containers started with new ports"
    sleep 5
    
    # Show status
    docker-compose -f docker-compose.production.yml ps
ENDSSH

echo ""
echo "✅ Port conflict fixed!"
echo ""
echo "🌐 Your site is now accessible at:"
echo "   http://31.220.107.19:3000 (Astro app)"
echo "   http://31.220.107.19:8080 (Nginx)"
echo ""
echo "📊 Check logs:"
echo "   ssh root@31.220.107.19 'cd /opt/marketingtool-pro && docker-compose -f docker-compose.production.yml logs -f'"
