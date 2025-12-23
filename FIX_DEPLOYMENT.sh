#!/bin/bash

# Fix Tailwind Dependencies Issue
echo "🔧 Fixing Tailwind CSS dependencies..."

VPS_HOST="31.220.107.19"
VPS_USER="root"
DEPLOY_PATH="/opt/marketingtool-pro"

# Copy updated docker-compose
echo "📤 Uploading fixed docker-compose.yml..."
scp docker-compose.production.yml ${VPS_USER}@${VPS_HOST}:${DEPLOY_PATH}/

echo "🔄 Restarting containers with full dependencies..."
ssh ${VPS_USER}@${VPS_HOST} << 'ENDSSH'
    cd /opt/marketingtool-pro
    
    # Stop containers
    docker-compose -f docker-compose.production.yml down
    
    # Remove old volumes to force fresh install
    docker volume rm marketingtool-pro_node_modules 2>/dev/null || true
    
    # Start with updated config
    docker-compose -f docker-compose.production.yml up -d
    
    echo ""
    echo "⏳ Waiting for build to complete (this takes 2-3 minutes)..."
    sleep 30
    
    # Show logs
    docker logs marketingtool-pro --tail 50
ENDSSH

echo ""
echo "✅ Fix applied! Check the logs above."
echo ""
echo "Monitor build progress:"
echo "  ssh root@31.220.107.19 'docker logs marketingtool-pro -f'"
