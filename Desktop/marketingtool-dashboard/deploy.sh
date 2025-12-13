#!/bin/bash
# MarketingTool.Pro - Production Deployment Script

set -e  # Exit on error

echo "🚀 MarketingTool.Pro Deployment Script"
echo "======================================"

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Configuration
SERVER_IP="31.220.107.19"
APP_NAME="marketingtool-dashboard"
DOMAIN="app.marketingtool.pro"

echo -e "${BLUE}📦 Building Docker image...${NC}"
docker-compose build

echo -e "${BLUE}🔄 Stopping old containers...${NC}"
docker-compose down

echo -e "${BLUE}🚀 Starting new containers...${NC}"
docker-compose up -d

echo -e "${BLUE}🔍 Running database migrations...${NC}"
docker-compose exec app npx prisma migrate deploy

echo -e "${BLUE}📊 Checking container status...${NC}"
docker-compose ps

echo -e "${GREEN}✅ Deployment complete!${NC}"
echo -e "${GREEN}🌐 Dashboard: http://localhost:3001${NC}"
echo -e "${GREEN}🌐 Production: https://${DOMAIN}${NC}"
