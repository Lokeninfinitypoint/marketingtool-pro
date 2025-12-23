#!/bin/bash
# Setup Docker Build Cloud builder for MarketingTool.Pro
# This script creates a cloud builder instance for faster, multi-platform builds

set -e

# Use environment variables with sensible defaults
DOCKER_USER="${DOCKER_USER:-builder}"
REPO="${REPO:-loken}"
BUILDER_BASE="${BUILDER_BASE:-cloud}"
BUILDER_NAME="${BUILDER_NAME:-${BUILDER_BASE}-${DOCKER_USER}-${REPO}}"
CLOUD_BUILDER="${CLOUD_BUILDER:-${DOCKER_USER}/${REPO}}"

echo "═══════════════════════════════════════════════════"
echo "  Docker Build Cloud Setup"
echo "═══════════════════════════════════════════════════"
echo ""

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Error: Docker is not installed"
    echo "Please install Docker first: https://docs.docker.com/get-docker/"
    exit 1
fi

# Check if Docker Buildx is available
if ! docker buildx version &> /dev/null; then
    echo "❌ Error: Docker Buildx is not available"
    echo "Please upgrade Docker to a version that includes Buildx"
    exit 1
fi

echo "✅ Docker and Buildx are installed"
echo ""

# Check Docker authentication
echo "Checking Docker Hub authentication..."
if ! docker info 2>/dev/null | grep -q "Username:"; then
    echo "⚠️  Warning: Not logged in to Docker Hub"
    echo ""
    echo "Please login to Docker Hub:"
    echo "  docker login -u ${DOCKER_USER}"
    echo ""
    read -p "Press Enter after logging in, or Ctrl+C to cancel..."
fi

echo "✅ Docker Hub authentication verified"
echo ""

# Check if builder already exists
if docker buildx ls | grep -q "$BUILDER_NAME"; then
  echo "ℹ️  Builder '$BUILDER_NAME' already exists"
  read -p "Do you want to remove and recreate it? (y/N): " -n 1 -r
  echo ""
  if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "Removing existing builder..."
    docker buildx rm "$BUILDER_NAME" || true
  else
    echo "Using existing builder"
    docker buildx use "$BUILDER_NAME"
    echo ""
    echo "✅ Cloud builder is ready!"
    echo ""
    docker buildx ls
    exit 0
  fi
fi

# Create new cloud builder
echo "Creating cloud builder: $BUILDER_NAME"
echo "Cloud builder endpoint: $CLOUD_BUILDER"
echo ""

docker buildx create \
  --driver cloud \
  "$CLOUD_BUILDER" \
  --name "$BUILDER_NAME"

# Set as default builder
echo ""
echo "Setting as default builder..."
docker buildx use "$BUILDER_NAME"

# Bootstrap the builder
echo ""
echo "Bootstrapping builder (this may take a moment)..."
docker buildx inspect "$BUILDER_NAME" --bootstrap

echo ""
echo "═══════════════════════════════════════════════════"
echo "  ✅ Cloud builder setup complete!"
echo "═══════════════════════════════════════════════════"
echo ""
echo "Builder name: $BUILDER_NAME"
echo "Cloud endpoint: $CLOUD_BUILDER"
echo ""
echo "Available builders:"
docker buildx ls
echo ""
echo "Next steps:"
echo "  1. Run a cloud build:  ./build-cloud.sh"
echo "  2. Or build manually:  docker buildx build --builder $BUILDER_NAME ."
echo ""
