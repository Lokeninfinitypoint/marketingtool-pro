# 🚀 Simple Deploy - Copy & Paste

## You're on the server? Perfect! Just copy-paste this:

---

## Step 1: Create Everything (Copy this entire block)

```bash
APP_DIR="/var/www/marketingtool"
mkdir -p $APP_DIR/aitool-app/src/app $APP_DIR/dashboard-app/src/app /var/log/pm2

cat > $APP_DIR/aitool-app/package.json << 'EOF'
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
EOF

cat > $APP_DIR/dashboard-app/package.json << 'EOF'
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
EOF

cat > $APP_DIR/aitool-app/next.config.js << 'EOF'
module.exports = { reactStrictMode: true, compress: true, swcMinify: true }
EOF

cat > $APP_DIR/dashboard-app/next.config.js << 'EOF'
module.exports = { reactStrictMode: true, compress: true, swcMinify: true }
EOF

cat > $APP_DIR/aitool-app/tsconfig.json << 'EOF'
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
EOF

cp $APP_DIR/aitool-app/tsconfig.json $APP_DIR/dashboard-app/tsconfig.json

cat > $APP_DIR/aitool-app/src/app/page.tsx << 'EOF'
export default function Home() {
  return (
    <div style={{ padding: '2rem', textAlign: 'center' }}>
      <h1>AI Tool Software</h1>
      <p>Running!</p>
    </div>
  );
}
EOF

cat > $APP_DIR/aitool-app/src/app/layout.tsx << 'EOF'
export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
EOF

cat > $APP_DIR/dashboard-app/src/app/page.tsx << 'EOF'
export default function Home() {
  return (
    <div style={{ padding: '2rem', textAlign: 'center' }}>
      <h1>Marketing Tool Dashboard</h1>
      <p>Running!</p>
    </div>
  );
}
EOF

cat > $APP_DIR/dashboard-app/src/app/layout.tsx << 'EOF'
export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
EOF
```

---

## Step 2: Install & Build (Copy this)

```bash
cd /var/www/marketingtool/aitool-app && npm install && npm run build
cd /var/www/marketingtool/dashboard-app && npm install && npm run build
```

---

## Step 3: Setup PM2 (Copy this)

```bash
cat > /var/www/marketingtool/ecosystem.config.js << 'EOF'
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
EOF

cd /var/www/marketingtool
pm2 stop all || true
pm2 delete all || true
pm2 start ecosystem.config.js
pm2 save
pm2 startup
# Copy and run the command PM2 outputs
```

---

## Step 4: Setup Nginx (Copy this)

```bash
cat > /etc/nginx/sites-available/marketingtool.pro << 'EOF'
server {
    listen 80;
    server_name marketingtool.pro www.marketingtool.pro;
    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}
server {
    listen 80;
    server_name app.marketingtool.pro;
    location / {
        proxy_pass http://localhost:3001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
    }
}
EOF

ln -s /etc/nginx/sites-available/marketingtool.pro /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx
```

---

## Step 5: Setup SSL

```bash
certbot --nginx -d marketingtool.pro -d www.marketingtool.pro -d app.marketingtool.pro --non-interactive --agree-tos --email admin@marketingtool.pro
```

---

## ✅ Done!

Check status:
```bash
pm2 status
curl http://localhost:3000
curl http://localhost:3001
```

Your apps are live at:
- http://marketingtool.pro
- http://app.marketingtool.pro
