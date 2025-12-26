#!/bin/bash

# Quick Deploy - Tries password method, falls back to manual instructions

VPS_IP="31.220.107.19"
VPS_USER="root"
VPS_PASSWORD="Cloth-vastr@123#"

echo "🚀 Quick Deployment"
echo "==================="

# Check if sshpass available
if command -v sshpass &> /dev/null; then
    echo "✅ Using password authentication..."
    ./DEPLOY_WITH_PASSWORD.sh
else
    echo "⚠️ sshpass not installed"
    echo ""
    echo "Option 1: Install sshpass and run:"
    echo "  sudo apt-get install -y sshpass"
    echo "  ./DEPLOY_WITH_PASSWORD.sh"
    echo ""
    echo "Option 2: Manual deployment - see MANUAL_DEPLOY_STEPS.md"
    echo ""
    echo "Option 3: Connect manually:"
    echo "  ssh root@$VPS_IP"
    echo "  Password: $VPS_PASSWORD"
    echo ""
    echo "Then follow steps in MANUAL_DEPLOY_STEPS.md"
fi
