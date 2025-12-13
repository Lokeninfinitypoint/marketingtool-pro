#!/bin/bash
# MarketingTool.Pro - Server Deployment Script
# Run this script ON YOUR SERVER (31.220.107.19)

set -e  # Exit on error

echo "🚀 MarketingTool.Pro - Production Deployment"
echo "============================================"

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
APP_DIR="/var/www/marketingtool-dashboard"
REPO_URL="https://github.com/Lokeninfinitypoint/marketingtool-pro.git"
APP_NAME="marketingtool-dashboard"

echo -e "${BLUE}📋 Deployment Configuration:${NC}"
echo "  - Server: 31.220.107.19"
echo "  - App Directory: $APP_DIR"
echo "  - Repository: $REPO_URL"
echo ""

# Step 1: Check if directory exists
if [ -d "$APP_DIR" ]; then
    echo -e "${YELLOW}⚠️  Directory exists. Updating...${NC}"
    cd $APP_DIR
    git pull origin main
else
    echo -e "${BLUE}📦 Cloning repository...${NC}"
    cd /var/www
    git clone $REPO_URL marketingtool-dashboard
    cd $APP_DIR
fi

# Step 2: Install Node.js dependencies
echo -e "${BLUE}📦 Installing dependencies...${NC}"
npm install

# Step 3: Setup environment
if [ ! -f ".env.production" ]; then
    echo -e "${YELLOW}⚠️  Creating .env.production...${NC}"
    cat > .env.production << 'EOF'
# Database
DATABASE_URL="file:./prisma/dev.db"

# NextAuth
NEXTAUTH_SECRET="change-this-to-a-random-secret-key"
NEXTAUTH_URL="https://app.marketingtool.pro"

# OpenAI API
OPENAI_API_KEY="your-openai-api-key-here"

# Anthropic API
ANTHROPIC_API_KEY="your-anthropic-api-key-here"

# Node Environment
NODE_ENV="production"
EOF
    echo -e "${RED}⚠️  IMPORTANT: Edit .env.production with your API keys!${NC}"
    echo -e "${YELLOW}Run: nano .env.production${NC}"
    read -p "Press Enter after you've edited .env.production..."
fi

# Step 4: Generate Prisma Client
echo -e "${BLUE}🔨 Generating Prisma Client...${NC}"
npx prisma generate

# Step 5: Run database migrations
echo -e "${BLUE}🗄️  Running database migrations...${NC}"
npx prisma migrate deploy || echo "No migrations to run"

# Step 6: Build application
echo -e "${BLUE}🏗️  Building application...${NC}"
npm run build

# Step 7: Install PM2 if not present
if ! command -v pm2 &> /dev/null; then
    echo -e "${BLUE}📦 Installing PM2...${NC}"
    npm install -g pm2
fi

# Step 8: Start or restart with PM2
echo -e "${BLUE}🚀 Starting application with PM2...${NC}"
if pm2 list | grep -q "$APP_NAME"; then
    echo -e "${YELLOW}Restarting existing application...${NC}"
    pm2 restart $APP_NAME
else
    echo -e "${GREEN}Starting new application...${NC}"
    pm2 start npm --name "$APP_NAME" -- start
fi

# Step 9: Save PM2 configuration
pm2 save

# Step 10: Setup PM2 to start on boot
if ! systemctl is-enabled pm2-root &> /dev/null; then
    echo -e "${BLUE}🔧 Configuring PM2 to start on boot...${NC}"
    pm2 startup
    echo -e "${YELLOW}Run the command above if shown${NC}"
fi

# Step 11: Check status
echo -e "${BLUE}📊 Application Status:${NC}"
pm2 status

echo ""
echo -e "${GREEN}✅ Deployment Complete!${NC}"
echo ""
echo "📋 Next Steps:"
echo "  1. Configure Nginx (if not done):"
echo "     sudo nano /etc/nginx/sites-available/app.marketingtool.pro"
echo ""
echo "  2. Enable site:"
echo "     sudo ln -s /etc/nginx/sites-available/app.marketingtool.pro /etc/nginx/sites-enabled/"
echo "     sudo nginx -t"
echo "     sudo systemctl reload nginx"
echo ""
echo "  3. Setup SSL:"
echo "     sudo certbot --nginx -d app.marketingtool.pro"
echo ""
echo "  4. Test your deployment:"
echo "     https://app.marketingtool.pro"
echo ""
echo -e "${GREEN}🎉 Dashboard is now live!${NC}"
