#!/bin/bash
# MarketingTool.Pro - Development Script

echo "🔧 Starting MarketingTool.Pro Development Server"
echo "==============================================="

# Check if node_modules exists
if [ ! -d "node_modules" ]; then
    echo "📦 Installing dependencies..."
    npm install
fi

# Generate Prisma Client
echo "🔨 Generating Prisma Client..."
npx prisma generate

# Start development server
echo "🚀 Starting development server on port 3001..."
npm run dev
