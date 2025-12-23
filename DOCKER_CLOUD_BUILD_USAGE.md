# Docker Build Cloud Usage Guide

Complete guide for using Docker Build Cloud with MarketingTool.Pro.

## Overview

Docker Build Cloud provides faster, multi-platform Docker builds using cloud infrastructure. This eliminates the need for local computing resources and enables building for multiple architectures (amd64, arm64) simultaneously.

**Benefits:**
- ⚡ **Faster builds** - Cloud infrastructure with more resources
- 🌍 **Multi-platform** - Build for multiple architectures at once
- 💾 **Shared caching** - Registry-based caching across builds
- 🚀 **No local resources** - Builds don't consume local CPU/memory
- 🔄 **Consistency** - Same environment locally and in CI/CD

---

## Quick Start

### 1. Prerequisites

- Docker installed (with Buildx support)
- Docker Hub account: `lokendra334`
- Git repository

### 2. Initial Setup (One-Time)

```bash
# Login to Docker Hub
docker login -u lokendra334

# Run the setup script
./setup-cloud-builder.sh
```

This creates a cloud builder named `cloud-lokendra334-loken`.

### 3. Build with Cloud

```bash
# Build and push to Docker Hub
./build-cloud.sh
```

This builds for both `linux/amd64` and `linux/arm64` platforms.

---

## Detailed Usage

### Setup Cloud Builder

The [setup-cloud-builder.sh](setup-cloud-builder.sh) script creates and configures the cloud builder:

```bash
./setup-cloud-builder.sh
```

**What it does:**
1. Checks Docker and Buildx are installed
2. Verifies Docker Hub authentication
3. Creates cloud builder: `cloud-lokendra334-loken`
4. Sets it as the default builder
5. Bootstraps the builder (connects to cloud service)

**Manual setup** (if needed):
```bash
# Create cloud builder
docker buildx create \
  --driver cloud \
  lokendra334/loken \
  --name cloud-lokendra334-loken

# Set as default
docker buildx use cloud-lokendra334-loken

# Bootstrap
docker buildx inspect cloud-lokendra334-loken --bootstrap
```

### Build with Cloud

The [build-cloud.sh](build-cloud.sh) script builds and pushes to Docker Hub:

```bash
./build-cloud.sh
```

**What it does:**
1. Checks cloud builder exists
2. Verifies Docker Hub authentication
3. Builds for `linux/amd64` and `linux/arm64`
4. Uses registry caching for speed
5. Tags with `latest` and git SHA
6. Pushes to `lokendra334/marketingtool-pro`

**Manual cloud build:**
```bash
docker buildx build \
  --builder cloud-lokendra334-loken \
  --tag lokendra334/marketingtool-pro:latest \
  --tag lokendra334/marketingtool-pro:$(git rev-parse --short HEAD) \
  --platform linux/amd64,linux/arm64 \
  --cache-from type=registry,ref=lokendra334/marketingtool-pro:cache \
  --cache-to type=registry,ref=lokendra334/marketingtool-pro:cache,mode=max \
  --push \
  .
```

### Build Locally (Development)

The [build-local.sh](build-local.sh) script builds without cloud:

```bash
./build-local.sh
```

This creates a local image: `marketingtool-pro:local`

**Run locally:**
```bash
docker run -p 4321:4321 marketingtool-pro:local
```

---

## Command Reference

### List Builders

```bash
docker buildx ls
```

Example output:
```
NAME/NODE                      DRIVER/ENDPOINT             STATUS   BUILDKIT PLATFORMS
cloud-lokendra334-loken *      cloud                       running
default                        docker                      running
```

### Inspect Builder

```bash
docker buildx inspect cloud-lokendra334-loken
```

### Switch Builder

```bash
# Use cloud builder
docker buildx use cloud-lokendra334-loken

# Use default builder
docker buildx use default
```

### Remove Builder

```bash
docker buildx rm cloud-lokendra334-loken
```

---

## Build Options

### Build Without Pushing

Build to test, but don't push to Docker Hub:

```bash
docker buildx build \
  --builder cloud-lokendra334-loken \
  --tag marketingtool-pro:test \
  --platform linux/amd64 \
  .
```

Note: Can't use `--load` with multi-platform builds.

### Build Single Platform

Build for one platform only (faster):

```bash
docker buildx build \
  --builder cloud-lokendra334-loken \
  --tag lokendra334/marketingtool-pro:amd64 \
  --platform linux/amd64 \
  --push \
  .
```

### Build with Different Tag

```bash
docker buildx build \
  --builder cloud-lokendra334-loken \
  --tag lokendra334/marketingtool-pro:v1.0.0 \
  --platform linux/amd64,linux/arm64 \
  --push \
  .
```

### Build with Verbose Output

```bash
docker buildx build \
  --builder cloud-lokendra334-loken \
  --progress=plain \
  --tag lokendra334/marketingtool-pro:latest \
  --push \
  .
```

---

## Caching Strategy

Docker Build Cloud uses registry-based caching for optimal performance.

### Cache Configuration

The build scripts use:
```bash
--cache-from type=registry,ref=lokendra334/marketingtool-pro:cache
--cache-to type=registry,ref=lokendra334/marketingtool-pro:cache,mode=max
```

**What this does:**
- `cache-from`: Pulls cache from registry before building
- `cache-to`: Saves cache to registry after building
- `mode=max`: Saves all layers (not just final image)

### Clear Cache

To force a fresh build without cache:

```bash
docker buildx build \
  --builder cloud-lokendra334-loken \
  --tag lokendra334/marketingtool-pro:latest \
  --no-cache \
  --push \
  .
```

---

## CI/CD Integration

### GitHub Actions

The repository includes [.github/workflows/docker-build-cloud.yml](.github/workflows/docker-build-cloud.yml) which uses Docker Build Cloud:

```yaml
- name: Set up Docker Buildx
  uses: docker/setup-buildx-action@v3
  with:
    driver: cloud
    endpoint: lokendra334/loken
```

### Consistency

Local builds and CI/CD builds use the same cloud builder configuration, ensuring:
- Same build environment
- Same cache
- Same output
- Reproducible results

---

## Troubleshooting

### Builder Not Found

**Error:** `cloud-lokendra334-loken not found`

**Solution:**
```bash
./setup-cloud-builder.sh
```

### Authentication Failed

**Error:** `Username and password required`

**Solution:**
```bash
docker login -u lokendra334
```

Then retry the build.

### Build Fails

**Check builder status:**
```bash
docker buildx ls
docker buildx inspect cloud-lokendra334-loken
```

**View detailed logs:**
```bash
docker buildx build \
  --builder cloud-lokendra334-loken \
  --progress=plain \
  .
```

### Cannot Push to Registry

**Error:** `denied: requested access to the resource is denied`

**Solutions:**
1. Verify login: `docker login -u lokendra334`
2. Check repository exists on Docker Hub
3. Verify repository permissions

### Slow Builds

**Possible causes:**
- Cold cache (first build is slower)
- Network speed to Docker Hub
- Large image size

**Solutions:**
- Use cache (already configured in scripts)
- Split Dockerfile into multi-stage build
- Optimize .dockerignore

---

## Best Practices

### 1. Use Scripts

Use the provided scripts for consistency:
```bash
./setup-cloud-builder.sh  # One-time setup
./build-cloud.sh          # Production builds
./build-local.sh          # Local testing
```

### 2. Tag Properly

Always tag with both `latest` and version/SHA:
```bash
--tag lokendra334/marketingtool-pro:latest
--tag lokendra334/marketingtool-pro:$(git rev-parse --short HEAD)
```

### 3. Multi-Platform Builds

Always build for both platforms (unless testing):
```bash
--platform linux/amd64,linux/arm64
```

### 4. Use Caching

Always configure cache for faster builds:
```bash
--cache-from type=registry,ref=lokendra334/marketingtool-pro:cache
--cache-to type=registry,ref=lokendra334/marketingtool-pro:cache,mode=max
```

### 5. Monitor Builds

Use `--progress=plain` for detailed output:
```bash
docker buildx build --progress=plain ...
```

---

## Dockerfile Optimization

Current [Dockerfile](Dockerfile) configuration:
- Base: `node:20-alpine`
- Single-stage build
- Includes: git, openssh, bash, curl
- Builds Astro application
- Exposes port 4321

**Future optimization opportunities:**
- Multi-stage build to reduce final image size
- Separate development and production images
- Layer optimization for better caching

---

## Docker Hub Repository

**Repository:** https://hub.docker.com/r/lokendra334/marketingtool-pro

**Images available:**
- `lokendra334/marketingtool-pro:latest` - Latest build
- `lokendra334/marketingtool-pro:<git-sha>` - Specific commit
- `lokendra334/marketingtool-pro:cache` - Build cache (not runnable)

**Pull and run:**
```bash
docker pull lokendra334/marketingtool-pro:latest
docker run -p 4321:4321 lokendra334/marketingtool-pro:latest
```

**Visit:** http://localhost:4321

---

## Cost and Limits

Docker Build Cloud is included with Docker subscriptions:
- **Free tier**: Limited build minutes per month
- **Pro/Team/Business**: More build minutes included
- Check current usage: https://app.docker.com/

**Monitor usage:**
- View builds: https://app.docker.com/builds
- Check limits: https://app.docker.com/settings/plan

---

## Additional Resources

### Documentation
- [Docker Build Cloud Official Docs](https://docs.docker.com/build/cloud/)
- [Docker Buildx Documentation](https://docs.docker.com/buildx/working-with-buildx/)
- [Multi-platform Builds](https://docs.docker.com/build/building/multi-platform/)

### Related Files
- [Dockerfile](Dockerfile) - Main application Dockerfile
- [.dockerignore](.dockerignore) - Build exclusions
- [docker-compose.yml](docker-compose.yml) - Development environment
- [docker-compose.production.yml](docker-compose.production.yml) - Production setup
- [.github/workflows/docker-build-cloud.yml](.github/workflows/docker-build-cloud.yml) - CI/CD workflow

### Scripts
- [setup-cloud-builder.sh](setup-cloud-builder.sh) - Initial cloud builder setup
- [build-cloud.sh](build-cloud.sh) - Cloud build script
- [build-local.sh](build-local.sh) - Local build script

---

## Support

For issues or questions:
1. Check this documentation
2. Review [troubleshooting section](#troubleshooting)
3. Check Docker Build Cloud status: https://status.docker.com/
4. Contact Docker support: https://www.docker.com/support/

---

Last updated: December 23, 2025
