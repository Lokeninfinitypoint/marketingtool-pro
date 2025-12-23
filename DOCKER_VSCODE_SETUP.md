# 🐳 Docker + VS Code Setup Guide

> Connect Docker and VS Code Insiders to MarketingTool.Pro project

---

## ✅ WHAT'S CONFIGURED

Your project now has:
- ✅ `.devcontainer/devcontainer.json` - VS Code Dev Container config
- ✅ `docker-compose.yml` - Docker orchestration
- ✅ Node.js 20 environment
- ✅ Auto-installed extensions
- ✅ Port forwarding (3000, 4321, 5173)

---

## 🚀 QUICK START (2 Steps)

### Step 1: Install VS Code Extension
```bash
# Option A: Use Visual Studio Code (regular)
1. Open VS Code
2. Install "Dev Containers" extension
   (ms-vscode-remote.remote-containers)

# Option B: Use VS Code Insiders
1. Download from: https://code.visualstudio.com/insiders/
2. Install "Dev Containers" extension
```

### Step 2: Open in Container
```bash
# Method 1: Command Palette
1. Open VS Code
2. Press Cmd+Shift+P (Mac) or Ctrl+Shift+P (Windows)
3. Type: "Dev Containers: Open Folder in Container"
4. Select: ~/Desktop/marketingtool-pro
5. Wait for container to build (2-3 minutes first time)

# Method 2: From Terminal
cd ~/Desktop/marketingtool-pro
code-insiders .
# Then: Click "Reopen in Container" when prompted
```

---

## 🛠️ DETAILED SETUP INSTRUCTIONS

### For VS Code (Regular)

**1. Install VS Code**
```bash
# If not installed, download from:
https://code.visualstudio.com/
```

**2. Install Dev Containers Extension**
```
1. Open VS Code
2. Click Extensions icon (left sidebar)
3. Search: "Dev Containers"
4. Install: "Dev Containers" by Microsoft
```

**3. Open Project in Container**
```
1. File → Open Folder → Select ~/Desktop/marketingtool-pro
2. VS Code will detect .devcontainer/
3. Click "Reopen in Container" (bottom right notification)
4. Wait for build (~2-3 minutes first time)
```

### For VS Code Insiders

**1. Install VS Code Insiders**
```bash
# Download from:
https://code.visualstudio.com/insiders/

# Or use Homebrew (Mac):
brew install --cask visual-studio-code-insiders
```

**2. Add to PATH**
```bash
# Add this to ~/.zshrc or ~/.bash_profile:
export PATH="/Applications/Visual Studio Code - Insiders.app/Contents/Resources/app/bin:$PATH"

# Then reload:
source ~/.zshrc
```

**3. Open Project**
```bash
cd ~/Desktop/marketingtool-pro
code-insiders .
```

---

## 🐳 DOCKER SETUP

### What Gets Installed Automatically

**Container Features:**
- ✅ Node.js 20
- ✅ Git
- ✅ GitHub CLI (gh)
- ✅ npm & dependencies

**VS Code Extensions:**
- ✅ Astro Language Support
- ✅ ESLint
- ✅ Prettier
- ✅ Tailwind CSS IntelliSense
- ✅ GitHub Copilot
- ✅ GitHub Copilot Chat
- ✅ Docker Extension
- ✅ GitLens

**Port Forwarding:**
- Port 3000: Dev Server
- Port 4321: Astro
- Port 5173: Vite

---

## 📂 PROJECT STRUCTURE IN CONTAINER

```
/workspace/                    # Your project root
├── .devcontainer/
│   └── devcontainer.json     # Container config
├── docker-compose.yml        # Docker services
├── src/                      # Source files
├── webflow-app/              # Webflow extension
├── node_modules/             # Dependencies (auto-installed)
└── package.json
```

---

## 🎯 COMMON COMMANDS IN CONTAINER

### Terminal Access
```bash
# Open integrated terminal in VS Code
Ctrl + ` (backtick)

# Commands work normally:
npm install
npm run dev
npm run build
git status
gh auth login
```

### Development
```bash
# Start Astro dev server
npm run dev

# Starts on http://localhost:4321
# Auto-opens in browser via port forwarding

# Start Webflow extension dev
cd webflow-app
npm run dev
# Starts on http://localhost:5173
```

### Git Operations
```bash
git status
git add .
git commit -m "your message"
git push origin main
```

### GitHub CLI
```bash
gh auth login
gh repo view
gh issue list
gh pr create
```

---

## 🔧 TROUBLESHOOTING

### Issue: "Cannot connect to Docker daemon"
```bash
# Solution: Start Docker Desktop
1. Open Docker Desktop application
2. Wait for "Docker is running" message
3. Try again in VS Code
```

### Issue: "Failed to connect to container"
```bash
# Solution: Rebuild container
1. Cmd+Shift+P → "Dev Containers: Rebuild Container"
2. Wait for rebuild to complete
3. Terminal should now work
```

### Issue: "Extension not found"
```bash
# Solution: Install Dev Containers extension
1. Open Extensions (Cmd+Shift+X)
2. Search: "Dev Containers"
3. Install by Microsoft
4. Reload VS Code
```

### Issue: "Port already in use"
```bash
# Solution: Stop conflicting process
docker ps  # See running containers
docker stop <container-id>

# Or change ports in docker-compose.yml
```

### Issue: "npm install fails"
```bash
# Solution: Clear cache and retry
docker-compose down -v
docker-compose up -d
# Then rebuild container in VS Code
```

---

## 🎨 VS CODE TIPS

### Useful Shortcuts
```
Cmd+Shift+P     Command Palette
Cmd+`           Toggle Terminal
Cmd+B           Toggle Sidebar
Cmd+Shift+E     Explorer
Cmd+Shift+F     Search
Cmd+Shift+G     Source Control (Git)
Cmd+Shift+D     Debug
```

### Recommended Settings
```json
{
  "editor.formatOnSave": true,
  "editor.defaultFormatter": "esbenp.prettier-vscode",
  "editor.codeActionsOnSave": {
    "source.fixAll.eslint": true
  },
  "typescript.preferences.importModuleSpecifier": "relative",
  "git.autofetch": true
}
```

---

## 🚦 VERIFICATION CHECKLIST

After setup, verify everything works:

- [ ] VS Code opens project
- [ ] Container is running (green "Dev Container" in bottom left)
- [ ] Terminal works (Ctrl+`)
- [ ] `npm --version` works
- [ ] `git --version` works
- [ ] `node --version` shows v20.x
- [ ] Extensions are installed (check left sidebar)
- [ ] Can run `npm run dev`
- [ ] Can access http://localhost:4321

---

## 📊 CONTAINER MANAGEMENT

### Start/Stop Container
```bash
# From VS Code:
Cmd+Shift+P → "Dev Containers: Reopen Folder Locally"  # Exits container
Cmd+Shift+P → "Dev Containers: Open Folder in Container"  # Starts again

# From Terminal:
docker ps  # List running containers
docker stop marketingtool-dev  # Stop container
docker start marketingtool-dev  # Start container
```

### View Logs
```bash
docker logs marketingtool-dev
docker logs -f marketingtool-dev  # Follow logs
```

### Access Container Shell
```bash
docker exec -it marketingtool-dev bash
```

### Rebuild Container
```bash
# If you change devcontainer.json:
Cmd+Shift+P → "Dev Containers: Rebuild Container"
```

---

## 🔄 WORKFLOW EXAMPLES

### Example 1: Start Development
```bash
1. Open VS Code
2. Open folder: ~/Desktop/marketingtool-pro
3. Click "Reopen in Container"
4. Wait for container ready (~30 seconds after first build)
5. Open terminal (Ctrl+`)
6. Run: npm run dev
7. Browser opens to http://localhost:4321
8. Start coding!
```

### Example 2: Commit Changes
```bash
1. Make changes to files
2. Files auto-save (if enabled)
3. Cmd+Shift+G (open Git panel)
4. Review changes
5. Stage files (+ icon)
6. Write commit message
7. Click ✓ Commit
8. Click "..." → Push
```

### Example 3: Debug
```bash
1. Set breakpoint (click left of line number)
2. F5 or Cmd+Shift+D
3. Select "Launch Chrome" or "Node"
4. Debugger starts
5. Inspect variables in left panel
```

---

## 💡 BENEFITS OF THIS SETUP

✅ **Consistent Environment**
- Same Node.js version everywhere
- No "works on my machine" issues

✅ **Isolated Dependencies**
- Project dependencies in container
- Don't pollute your system

✅ **Easy Onboarding**
- New developers: just open in container
- Everything configured automatically

✅ **Git Included**
- Full Git support in container
- GitHub CLI pre-installed

✅ **Port Forwarding**
- Access container ports from host
- http://localhost:3000 works seamlessly

---

## 🎯 NEXT STEPS

1. **Install VS Code Extension**
   - Dev Containers by Microsoft

2. **Open Project**
   - Open ~/Desktop/marketingtool-pro
   - Reopen in Container

3. **Start Development**
   - Terminal: `npm run dev`
   - Browser: http://localhost:4321

4. **Start Coding!**
   - All tools ready
   - Extensions installed
   - Environment configured

---

## 📞 SUPPORT

**Issues with Docker?**
- Check Docker Desktop is running
- Restart Docker Desktop
- Check Docker logs

**Issues with VS Code?**
- Update to latest version
- Reinstall Dev Containers extension
- Check output panel for errors

**Still stuck?**
- Check Docker logs: `docker logs marketingtool-dev`
- Rebuild container: Cmd+Shift+P → "Rebuild Container"
- Ask for help with specific error message

---

**Your development environment is ready! 🎉**

**Open VS Code, reopen in container, and start coding!** 🚀
