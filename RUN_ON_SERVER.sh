#!/bin/bash
# Copy and paste this ENTIRE script into your SSH session on the server

set -e

APP_DIR="/var/www/marketingtool"

echo "🚀 Creating applications on server..."
echo "====================================="

# Create directories
mkdir -p $APP_DIR/aitool-app/src/app
mkdir -p $APP_DIR/dashboard-app/src/app
mkdir -p /var/log/pm2

# Create aitool-app package.json
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

# Create dashboard-app package.json
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

# Create next.config.js files
cat > $APP_DIR/aitool-app/next.config.js << 'EOF'
module.exports = { reactStrictMode: true, compress: true, swcMinify: true }
EOF

cat > $APP_DIR/dashboard-app/next.config.js << 'EOF'
module.exports = { reactStrictMode: true, compress: true, swcMinify: true }
EOF

# Create tsconfig.json
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

# Create basic pages
cat > $APP_DIR/aitool-app/src/app/page.tsx << 'EOF'
export default function Home() {
  return (
    <div style={{ padding: '2rem', textAlign: 'center' }}>
      <h1>AI Tool Software</h1>
      <p>Application is running!</p>
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
      <p>Dashboard is running!</p>
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

echo "✅ Files created!"
echo ""
echo "Installing dependencies..."

cd $APP_DIR/aitool-app
npm install

cd $APP_DIR/dashboard-app
npm install

echo "✅ Dependencies installed!"
echo ""
echo "Building applications..."

cd $APP_DIR/aitool-app
npm run build

cd $APP_DIR/dashboard-app
npm run build

echo "✅ Applications built!"
echo ""
echo "Creating PM2 config..."

cat > $APP_DIR/ecosystem.config.js << 'EOF'
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

echo "✅ PM2 config created!"
echo ""
echo "Starting applications with PM2..."

cd $APP_DIR
pm2 stop all || true
pm2 delete all || true
pm2 start ecosystem.config.js
pm2 save

echo "✅ PM2 started!"
echo ""
echo "Setting up Nginx..."

cat > /etc/nginx/sites-available/marketingtool.pro << 'EOF'
server {
    listen 80;
    listen [::]:80;
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

    location /_next/static {
        proxy_pass http://localhost:3000;
        proxy_cache_valid 200 60m;
        add_header Cache-Control "public, immutable";
    }
}

server {
    listen 80;
    listen [::]:80;
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

    location /_next/static {
        proxy_pass http://localhost:3001;
        proxy_cache_valid 200 60m;
        add_header Cache-Control "public, immutable";
    }
}
EOF

ln -sf /etc/nginx/sites-available/marketingtool.pro /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl restart nginx

echo "✅ Nginx configured!"
echo ""
echo "Setting up firewall..."

ufw allow 22/tcp || true
ufw allow 80/tcp || true
ufw allow 443/tcp || true
ufw --force enable || true

echo "✅ Firewall configured!"
echo ""
echo "🎉 DEPLOYMENT COMPLETE!"
echo ""
echo "Your apps are running at:"
echo "  - http://marketingtool.pro (port 3000)"
echo "  - http://app.marketingtool.pro (port 3001)"
echo ""
echo "Check status: pm2 status"
echo "View logs: pm2 logs"
echo ""
echo "Next: Setup SSL with:"
echo "  certbot --nginx -d marketingtool.pro -d www.marketingtool.pro -d app.marketingtool.pro"
