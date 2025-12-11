# 🐳 Docker + VS Code Insiders + Git Setup

> Complete development environment for MarketingTool.Pro

---

## ✅ What's Included

- **Docker Container** with Node.js 20
- **Git** pre-configured with your credentials
- **GitHub CLI** for repository management
- **VS Code Insiders** Dev Container support
- **Port forwarding** for development (3000, 4321)

---

## 🚀 Quick Start

### Option 1: VS Code Insiders (Recommended)

1. **Install VS Code Insiders Remote Containers Extension**
   ```bash
   # Open VS Code Insiders
   # Install extension: "Dev Containers" (ms-vscode-remote.remote-containers)
   ```

2. **Open in Container**
   ```bash
   # In VS Code Insiders:
   # Cmd+Shift+P → "Dev Containers: Open Folder in Container"
   # Select: /Users/loken
   ```

3. **VS Code will automatically:**
   - Build the Docker container
   - Mount your Git config and SSH keys
   - Install VS Code extensions
   - Run `npm install`
   - Open integrated terminal in container

### Option 2: Docker Compose

```bash
cd /Users/loken

# Build and start container
docker-compose up -d

# Access container shell
docker exec -it marketingtool-dev sh

# Verify Git is configured
git config --list

# Test GitHub CLI
gh auth status

# Start development
npm install
npm run dev
```

### Option 3: Docker CLI

```bash
# Build image
docker build -f Dockerfile.devcontainer -t marketingtool-dev .

# Run container with Git/SSH mounted
docker run -d \
  --name marketingtool-dev \
  -v $(pwd):/workspace \
  -v ~/.ssh:/root/.ssh:ro \
  -v ~/.gitconfig:/root/.gitconfig:ro \
  -p 3000:3000 \
  -p 4321:4321 \
  marketingtool-dev

# Access shell
docker exec -it marketingtool-dev sh
```

---

## 🔧 Configuration Files

### 1. `Dockerfile.devcontainer`
- Base image: Node.js 20 Alpine
- Includes: Git, SSH, GitHub CLI, Bash
- Pre-configured Git user settings

### 2. `.devcontainer/devcontainer.json`
- VS Code Insiders integration
- Mounts SSH keys and Git config
- Installs recommended extensions:
  - Astro
  - ESLint
  - Prettier
  - Tailwind CSS
  - GitHub Copilot
  - GitLens

### 3. `docker-compose.yml`
- Service configuration
- Volume management
- Port mappings

---

## 📦 Inside the Container

### Verify Git Setup
```bash
docker exec -it marketingtool-dev sh

# Check Git config
git config --global --list

# Should show:
# user.name=infinity-jpg
# user.email=noreply@github.com
```

### Verify GitHub CLI
```bash
# Check authentication
gh auth status

# If not authenticated, login:
gh auth login
```

### Development Workflow
```bash
# Inside container
cd /workspace

# Install dependencies
npm install

# Start dev server
npm run dev
# Access at: http://localhost:4321

# Git operations work normally
git status
git add .
git commit -m "feat: new feature"
git push origin main
```

---

## 🔑 SSH Keys & Git Credentials

The container automatically mounts:
- `~/.ssh` → SSH keys for Git operations
- `~/.gitconfig` → Your Git configuration

This means:
✅ Git push/pull works seamlessly
✅ GitHub CLI inherits authentication
✅ No need to reconfigure credentials

---

## 🛠️ VS Code Insiders Extensions

Auto-installed in Dev Container:
- **Astro** - Astro language support
- **ESLint** - Code linting
- **Prettier** - Code formatting
- **Tailwind CSS** - Tailwind IntelliSense
- **GitHub Copilot** - AI pair programming
- **GitLens** - Advanced Git features

---

## 📊 Port Forwarding

| Port | Service | Access |
|------|---------|--------|
| 3000 | Alternative dev server | http://localhost:3000 |
| 4321 | Astro dev server | http://localhost:4321 |

---

## 🐛 Troubleshooting

### Container won't start
```bash
# Check Docker is running
docker ps

# Check logs
docker logs marketingtool-dev

# Rebuild container
docker-compose down
docker-compose up --build -d
```

### Git not working
```bash
# Verify SSH keys mounted
docker exec -it marketingtool-dev ls -la /root/.ssh

# Verify Git config
docker exec -it marketingtool-dev git config --list

# Test SSH connection
docker exec -it marketingtool-dev ssh -T git@github.com
```

### VS Code Insiders not connecting
```bash
# Ensure Dev Containers extension installed
# Cmd+Shift+P → "Dev Containers: Show Container Log"

# Rebuild container
# Cmd+Shift+P → "Dev Containers: Rebuild Container"
```

---

## 🧹 Cleanup

### Stop container
```bash
docker-compose down
# or
docker stop marketingtool-dev
docker rm marketingtool-dev
```

### Remove all
```bash
# Stop and remove container
docker-compose down -v

# Remove image
docker rmi marketingtool-dev

# Clean Docker system
docker system prune -a
```

---

## 📝 Quick Commands

```bash
# Start everything
docker-compose up -d

# View logs
docker-compose logs -f

# Access shell
docker exec -it marketingtool-dev sh

# Stop everything
docker-compose down

# Rebuild
docker-compose up --build -d

# Git operations (from host or container)
git status
git add .
git commit -m "message"
git push
```

---

## 🎯 Next Steps

1. **Open in VS Code Insiders**
   ```bash
   # Install Dev Containers extension first
   # Then: Cmd+Shift+P → "Dev Containers: Reopen in Container"
   ```

2. **Start Development**
   ```bash
   npm install
   npm run dev
   ```

3. **Make Changes & Push**
   ```bash
   git add .
   git commit -m "feat: your feature"
   git push origin main
   ```

---

## 🌟 Benefits

✅ **Consistent Environment** - Same setup on any machine
✅ **Isolated Dependencies** - No conflicts with host system
✅ **Git Integration** - Seamless push/pull with GitHub
✅ **VS Code Integration** - Native IDE experience in container
✅ **Port Forwarding** - Access services on localhost
✅ **Volume Mounts** - Changes persist on host

---

**Ready to develop in Docker! 🐳**
