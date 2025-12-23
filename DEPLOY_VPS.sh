#!/bin/bash

# VPS Deployment Script for MarketingTool.Pro
# VPS: 31.220.107.19
# User: root
# Password: Lokendra@9078

set -e

echo "🚀 Deploying MarketingTool.Pro to VPS..."
echo "VPS: 31.220.107.19"
echo ""

# VPS Configuration
VPS_HOST="31.220.107.19"
VPS_USER="root"
VPS_PORT="22"
DEPLOY_PATH="/opt/marketingtool-pro"
PROJECT_NAME="marketingtool-pro"

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}Step 1: Testing SSH connection...${NC}"
ssh -o ConnectTimeout=10 ${VPS_USER}@${VPS_HOST} "echo 'SSH connection successful'" || {
    echo -e "${RED}❌ SSH connection failed. Please check:${NC}"
    echo "  - VPS is running"
    echo "  - SSH key is configured"
    echo "  - Host: ${VPS_HOST}"
    echo ""
    echo "To add SSH key:"
    echo "  ssh-copy-id ${VPS_USER}@${VPS_HOST}"
    exit 1
}

echo -e "${GREEN}✅ SSH connection successful${NC}"
echo ""

echo -e "${BLUE}Step 2: Preparing VPS environment...${NC}"
ssh ${VPS_USER}@${VPS_HOST} << 'ENDSSH'
    # Install Docker if not exists
    if ! command -v docker &> /dev/null; then
        echo "Installing Docker..."
        curl -fsSL https://get.docker.com -o get-docker.sh
        sh get-docker.sh
        systemctl enable docker
        systemctl start docker
    fi
    
    # Install Docker Compose if not exists
    if ! command -v docker-compose &> /dev/null; then
        echo "Installing Docker Compose..."
        curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
        chmod +x /usr/local/bin/docker-compose
    fi
    
    echo "✅ Docker environment ready"
ENDSSH

echo -e "${GREEN}✅ VPS environment prepared${NC}"
echo ""

echo -e "${BLUE}Step 3: Creating deployment directory...${NC}"
ssh ${VPS_USER}@${VPS_HOST} "mkdir -p ${DEPLOY_PATH}"
echo -e "${GREEN}✅ Directory created: ${DEPLOY_PATH}${NC}"
echo ""

echo -e "${BLUE}Step 4: Copying files to VPS...${NC}"
echo "This may take several minutes (22GB data)..."

# Build locally first
echo "Building locally..."
npm run build

# Copy essential files only
rsync -avz --progress \
    --exclude='node_modules' \
    --exclude='.git' \
    --exclude='.astro' \
    --exclude='dist' \
    --exclude='*.log' \
    ./src \
    ./public \
    ./package.json \
    ./package-lock.json \
    ./astro.config.mjs \
    ./tsconfig.json \
    ./docker-compose.production.yml \
    ./nginx.conf \
    ./.dockerignore \
    ${VPS_USER}@${VPS_HOST}:${DEPLOY_PATH}/

# Copy built dist separately
rsync -avz --progress ./dist ${VPS_USER}@${VPS_HOST}:${DEPLOY_PATH}/

echo -e "${GREEN}✅ Files copied to VPS${NC}"
echo ""

echo -e "${BLUE}Step 5: Starting Docker containers...${NC}"
ssh ${VPS_USER}@${VPS_HOST} << ENDSSH
    cd ${DEPLOY_PATH}
    
    # Stop existing containers
    docker-compose -f docker-compose.production.yml down 2>/dev/null || true
    
    # Start new containers
    docker-compose -f docker-compose.production.yml up -d
    
    echo "✅ Docker containers started"
    echo ""
    echo "Waiting for services to be ready..."
    sleep 10
    
    # Check container status
    docker-compose -f docker-compose.production.yml ps
ENDSSH

echo ""
echo -e "${GREEN}✅ Deployment complete!${NC}"
echo ""
echo "🌐 Your site is now live at:"
echo "   http://${VPS_HOST}"
echo "   http://${VPS_HOST}:8080 (nginx)"
echo ""
echo "📊 Check logs:"
echo "   ssh ${VPS_USER}@${VPS_HOST} 'cd ${DEPLOY_PATH} && docker-compose -f docker-compose.production.yml logs -f'"
echo ""
echo "🔄 Restart services:"
echo "   ssh ${VPS_USER}@${VPS_HOST} 'cd ${DEPLOY_PATH} && docker-compose -f docker-compose.production.yml restart'"
echo ""
echo "🛑 Stop services:"
echo "   ssh ${VPS_USER}@${VPS_HOST} 'cd ${DEPLOY_PATH} && docker-compose -f docker-compose.production.yml down'"
echo ""
