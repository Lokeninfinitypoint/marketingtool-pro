#!/bin/bash
# Build MarketingTool.Pro locally (not using cloud)
# For local development and testing

set -e

IMAGE_TAG="marketingtool-pro:local"

echo "═══════════════════════════════════════════════════"
echo "  Local Docker Build"
echo "═══════════════════════════════════════════════════"
echo ""
echo "Building locally with default Docker builder"
echo "Image tag: $IMAGE_TAG"
echo ""

# Build locally
docker buildx build \
  --tag "$IMAGE_TAG" \
  --load \
  --progress=plain \
  .

echo ""
echo "═══════════════════════════════════════════════════"
echo "  ✅ Local build complete!"
echo "═══════════════════════════════════════════════════"
echo ""
echo "Image: $IMAGE_TAG"
echo ""
echo "Run the container:"
echo "  docker run -p 4321:4321 $IMAGE_TAG"
echo ""
echo "Or run with volume mounts for development:"
echo "  docker run -p 4321:4321 \\"
echo "    -v \$(pwd)/src:/app/src \\"
echo "    -v \$(pwd)/public:/app/public \\"
echo "    $IMAGE_TAG"
echo ""
