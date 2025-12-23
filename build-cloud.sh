#!/bin/bash
# Build MarketingTool.Pro using Docker Build Cloud
# Fast, multi-platform builds using cloud infrastructure

set -e

BUILDER_NAME="cloud-lokendra334-loken"
IMAGE_NAME="lokendra334/marketingtool-pro"
GIT_SHA=$(git rev-parse --short HEAD 2>/dev/null || echo "local")

echo "═══════════════════════════════════════════════════"
echo "  Docker Build Cloud - MarketingTool.Pro"
echo "═══════════════════════════════════════════════════"
echo ""
echo "Builder:    $BUILDER_NAME"
echo "Image:      $IMAGE_NAME"
echo "Git SHA:    $GIT_SHA"
echo "Platforms:  linux/amd64, linux/arm64"
echo ""

# Check if builder exists
if ! docker buildx ls | grep -q "$BUILDER_NAME"; then
    echo "❌ Error: Cloud builder '$BUILDER_NAME' not found"
    echo ""
    echo "Please run setup first:"
    echo "  ./setup-cloud-builder.sh"
    exit 1
fi

# Check Docker authentication
echo "Checking Docker Hub authentication..."
if ! docker info 2>/dev/null | grep -q "Username:"; then
    echo "❌ Error: Not logged in to Docker Hub"
    echo ""
    echo "Please login first:"
    echo "  docker login -u lokendra334"
    exit 1
fi

echo "✅ Ready to build"
echo ""

# Start build
echo "Starting cloud build..."
echo "This will:"
echo "  - Build for linux/amd64 and linux/arm64"
echo "  - Use registry caching for faster builds"
echo "  - Push to Docker Hub: $IMAGE_NAME"
echo ""

docker buildx build \
  --builder "$BUILDER_NAME" \
  --tag "$IMAGE_NAME:latest" \
  --tag "$IMAGE_NAME:$GIT_SHA" \
  --platform linux/amd64,linux/arm64 \
  --cache-from type=registry,ref="$IMAGE_NAME:cache" \
  --cache-to type=registry,ref="$IMAGE_NAME:cache",mode=max \
  --push \
  --progress=plain \
  .

echo ""
echo "═══════════════════════════════════════════════════"
echo "  ✅ Build complete!"
echo "═══════════════════════════════════════════════════"
echo ""
echo "Images pushed to Docker Hub:"
echo "  - $IMAGE_NAME:latest"
echo "  - $IMAGE_NAME:$GIT_SHA"
echo ""
echo "View on Docker Hub:"
echo "  https://hub.docker.com/r/$IMAGE_NAME"
echo ""
echo "Pull and run:"
echo "  docker pull $IMAGE_NAME:latest"
echo "  docker run -p 4321:4321 $IMAGE_NAME:latest"
echo ""
