#!/bin/bash

echo "╔══════════════════════════════════════════════════════════════════╗"
echo "║                                                                  ║"
echo "║        🚀 MARKETINGTOOL.PRO DEPLOYMENT OPTIONS                  ║"
echo "║                                                                  ║"
echo "╚══════════════════════════════════════════════════════════════════╝"
echo ""

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "CHOOSE YOUR DEPLOYMENT METHOD:"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "1. 🌐 Cloudflare Pages (Recommended)"
echo "   - Free hosting"
echo "   - Auto deploy from GitHub"
echo "   - CDN included"
echo "   - Perfect for Astro"
echo ""
echo "2. ▲ Vercel"
echo "   - Free tier available"
echo "   - Auto deploy from GitHub"
echo "   - Serverless functions"
echo ""
echo "3. 🔷 Netlify"
echo "   - Free tier"
echo "   - Easy setup"
echo "   - Forms & Functions"
echo ""
echo "4. 🖥️  Your VPS (root@31.220.107.19)"
echo "   - Full control"
echo "   - Already have server"
echo "   - Custom configuration"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

read -p "Enter your choice (1-4): " choice

case $choice in
    1)
        echo ""
        echo "🌐 CLOUDFLARE PAGES DEPLOYMENT"
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
        echo ""
        echo "Steps:"
        echo "1. Go to: https://dash.cloudflare.com/"
        echo "2. Click 'Workers & Pages' → 'Create Application' → 'Pages'"
        echo "3. Connect to GitHub: Lokeninfinitypoint/marketingtool-pro"
        echo "4. Build settings:"
        echo "   - Framework: Astro"
        echo "   - Build command: npm run build"
        echo "   - Output directory: dist"
        echo "5. Click 'Save and Deploy'"
        echo ""
        echo "✅ Your site will be live at: https://marketingtool-pro.pages.dev"
        ;;
    2)
        echo ""
        echo "▲ VERCEL DEPLOYMENT"
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
        echo ""
        echo "Steps:"
        echo "1. Go to: https://vercel.com/new"
        echo "2. Import Git Repository"
        echo "3. Select: Lokeninfinitypoint/marketingtool-pro"
        echo "4. Framework: Astro (auto-detected)"
        echo "5. Click 'Deploy'"
        echo ""
        echo "✅ Your site will be live at: https://marketingtool-pro.vercel.app"
        ;;
    3)
        echo ""
        echo "🔷 NETLIFY DEPLOYMENT"
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
        echo ""
        echo "Steps:"
        echo "1. Go to: https://app.netlify.com/start"
        echo "2. Connect GitHub"
        echo "3. Select: Lokeninfinitypoint/marketingtool-pro"
        echo "4. Build settings:"
        echo "   - Build command: npm run build"
        echo "   - Publish directory: dist"
        echo "5. Click 'Deploy site'"
        echo ""
        echo "✅ Your site will be live at: https://marketingtool-pro.netlify.app"
        ;;
    4)
        echo ""
        echo "🖥️  VPS DEPLOYMENT (31.220.107.19)"
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
        echo ""
        echo "Would you like to deploy now to your VPS?"
        echo ""
        read -p "Deploy to VPS? (y/n): " deploy_vps
        
        if [ "$deploy_vps" = "y" ]; then
            echo ""
            echo "🚀 Deploying to VPS..."
            echo ""
            
            # Build the project
            echo "Step 1: Building project..."
            npm run build
            
            echo ""
            echo "Step 2: Deploying to server..."
            echo ""
            echo "Run these commands on your VPS (ssh root@31.220.107.19):"
            echo ""
            echo "# On VPS:"
            echo "cd /var/www/marketingtool.pro"
            echo "git pull origin main"
            echo "npm install"
            echo "npm run build"
            echo "pm2 restart marketingtool"
            echo ""
            echo "Or use scp to upload dist folder:"
            echo "scp -r dist/* root@31.220.107.19:/var/www/marketingtool.pro/html/"
        else
            echo ""
            echo "VPS deployment cancelled."
        fi
        ;;
    *)
        echo ""
        echo "❌ Invalid choice. Please run the script again."
        ;;
esac

echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

