# 🆘 Unstuck Guide - Upload Files to Server

## Problem: Can't upload files with scp

### Solution: Create files directly on the server

Since you're already connected to the server, let's create everything there!

---

## 🎯 Quick Fix - Run This ON THE SERVER

Copy and paste this entire block into your SSH session:

```bash
# Create app structure
mkdir -p /var/www/marketingtool/aitool-app/src/app
mkdir -p /var/www/marketingtool/dashboard-app/src/app

# Create package.json for aitool-app
cat > /var/www/marketingtool/aitool-app/package.json << 'PKGEOF'
{
  "name": "aitool-software",
  "version": "1.0.0",
  "private": true,
  "scripts": {
    "dev": "next dev -p 3000",
    "build": "next build",
    "start": "next start -p 3000"
  },
  "dependencies": {
    "next": "^14.0.0",
    "react": "^18.2.0",
    "react-dom": "^18.2.0",
    "lucide-react": "^0.294.0"
  },
  "devDependencies": {
    "@types/node": "^20.0.0",
    "@types/react": "^18.2.0",
    "@types/react-dom": "^18.2.0",
    "typescript": "^5.0.0",
    "tailwindcss": "^3.4.0",
    "postcss": "^8.4.0",
    "autoprefixer": "^10.4.0"
  }
}
PKGEOF

# Create package.json for dashboard-app
cat > /var/www/marketingtool/dashboard-app/package.json << 'PKGEOF'
{
  "name": "marketingtool-dashboard",
  "version": "1.0.0",
  "private": true,
  "scripts": {
    "dev": "next dev -p 3001",
    "build": "next build",
    "start": "next start -p 3001"
  },
  "dependencies": {
    "next": "^14.0.0",
    "react": "^18.2.0",
    "react-dom": "^18.2.0",
    "lucide-react": "^0.294.0"
  },
  "devDependencies": {
    "@types/node": "^20.0.0",
    "@types/react": "^18.2.0",
    "@types/react-dom": "^18.2.0",
    "typescript": "^5.0.0",
    "tailwindcss": "^3.4.0",
    "postcss": "^8.4.0",
    "autoprefixer": "^10.4.0"
  }
}
PKGEOF

# Create next.config.js files
cat > /var/www/marketingtool/aitool-app/next.config.js << 'CFGEOF'
module.exports = { reactStrictMode: true, compress: true, swcMinify: true }
CFGEOF

cat > /var/www/marketingtool/dashboard-app/next.config.js << 'CFGEOF'
module.exports = { reactStrictMode: true, compress: true, swcMinify: true }
CFGEOF

# Create basic pages
cat > /var/www/marketingtool/aitool-app/src/app/page.tsx << 'PAGEEOF'
export default function Home() {
  return (
    <div style={{ padding: '2rem', textAlign: 'center' }}>
      <h1>AI Tool Software</h1>
      <p>Running on port 3000</p>
    </div>
  );
}
PAGEEOF

cat > /var/www/marketingtool/aitool-app/src/app/layout.tsx << 'LAYOUTEOF'
export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
LAYOUTEOF

cat > /var/www/marketingtool/dashboard-app/src/app/page.tsx << 'PAGEEOF'
export default function Home() {
  return (
    <div style={{ padding: '2rem', textAlign: 'center' }}>
      <h1>Marketing Tool Dashboard</h1>
      <p>Running on port 3001</p>
    </div>
  );
}
PAGEEOF

cat > /var/www/marketingtool/dashboard-app/src/app/layout.tsx << 'LAYOUTEOF'
export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
LAYOUTEOF

# Create tsconfig.json
cat > /var/www/marketingtool/aitool-app/tsconfig.json << 'TSEOF'
{
  "compilerOptions": {
    "target": "ES2017",
    "lib": ["dom", "dom.iterable", "esnext"],
    "allowJs": true,
    "skipLibCheck": true,
    "strict": true,
    "noEmit": true,
    "esModuleInterop": true,
    "module": "esnext",
    "moduleResolution": "bundler",
    "resolveJsonModule": true,
    "isolatedModules": true,
    "jsx": "preserve",
    "incremental": true,
    "plugins": [{"name": "next"}],
    "paths": {"@/*": ["./src/*"]}
  },
  "include": ["next-env.d.ts", "**/*.ts", "**/*.tsx", ".next/types/**/*.ts"],
  "exclude": ["node_modules"]
}
TSEOF

cp /var/www/marketingtool/aitool-app/tsconfig.json /var/www/marketingtool/dashboard-app/tsconfig.json

echo "✅ Files created! Now installing dependencies..."
```

---

## Then Install & Build

```bash
# Install dependencies
cd /var/www/marketingtool/aitool-app && npm install
cd /var/www/marketingtool/dashboard-app && npm install

# Build apps
cd /var/www/marketingtool/aitool-app && npm run build
cd /var/www/marketingtool/dashboard-app && npm run build
```

---

## Then Setup PM2 & Nginx

```bash
# Create PM2 config
cat > /var/www/marketingtool/ecosystem.config.js << 'PM2EOF'
module.exports = {
  apps: [
    {
      name: 'aitool-app',
      cwd: '/var/www/marketingtool/aitool-app',
      script: 'npm',
      args: 'start',
      env: { NODE_ENV: 'production', PORT: 3000 },
      error_file: '/var/log/pm2/aitool-error.log',
      out_file: '/var/log/pm2/aitool-out.log',
    },
    {
      name: 'dashboard-app',
      cwd: '/var/www/marketingtool/dashboard-app',
      script: 'npm',
      args: 'start',
      env: { NODE_ENV: 'production', PORT: 3001 },
      error_file: '/var/log/pm2/dashboard-error.log',
      out_file: '/var/log/pm2/dashboard-out.log',
    },
  ],
};
PM2EOF

# Start PM2
cd /var/www/marketingtool
pm2 start ecosystem.config.js
pm2 save
pm2 startup
# Run the command PM2 outputs

# Setup Nginx (copy from nginx-marketingtool.conf content)
# Then:
ln -s /etc/nginx/sites-available/marketingtool.pro /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx
```

---

## ✅ You're Unstuck!

This creates minimal working apps directly on the server. You can add more files later!
