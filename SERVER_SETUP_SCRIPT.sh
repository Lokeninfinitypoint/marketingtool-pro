#!/bin/bash

# Complete Server Setup Script
# Run this ON THE SERVER after initial setup

set -e

APP_DIR="/var/www/marketingtool"

echo "🚀 Setting up applications on server..."
echo "========================================"

# Create directories
mkdir -p $APP_DIR/aitool-app
mkdir -p $APP_DIR/dashboard-app
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
    "start": "next start -p 3000",
    "lint": "next lint"
  },
  "dependencies": {
    "next": "^14.0.0",
    "react": "^18.2.0",
    "react-dom": "^18.2.0",
    "lucide-react": "^0.294.0",
    "appwrite": "^15.0.0",
    "@windmill/windmill-js-client": "^1.0.0",
    "axios": "^1.6.0"
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
    "start": "next start -p 3001",
    "lint": "next lint"
  },
  "dependencies": {
    "next": "^14.0.0",
    "react": "^18.2.0",
    "react-dom": "^18.2.0",
    "lucide-react": "^0.294.0",
    "appwrite": "^15.0.0",
    "@windmill/windmill-js-client": "^1.0.0",
    "axios": "^1.6.0"
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

# Create basic Next.js configs
cat > $APP_DIR/aitool-app/next.config.js << 'EOF'
/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  compress: true,
  swcMinify: true,
  productionBrowserSourceMaps: false,
}

module.exports = nextConfig
EOF

cat > $APP_DIR/dashboard-app/next.config.js << 'EOF'
/** @type {import('next').NextConfig} */
const nextConfig = {
  output: 'standalone',
  reactStrictMode: true,
  compress: true,
  swcMinify: true,
  productionBrowserSourceMaps: false,
}

module.exports = nextConfig
EOF

# Create basic app structure
mkdir -p $APP_DIR/aitool-app/src/app
mkdir -p $APP_DIR/dashboard-app/src/app

# Create minimal page.tsx for aitool-app
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

# Create minimal layout.tsx for aitool-app
cat > $APP_DIR/aitool-app/src/app/layout.tsx << 'EOF'
export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
EOF

# Create minimal page.tsx for dashboard-app
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

# Create minimal layout.tsx for dashboard-app
cat > $APP_DIR/dashboard-app/src/app/layout.tsx << 'EOF'
export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  );
}
EOF

# Create tsconfig.json files
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

echo "✅ Basic app structure created!"
echo ""
echo "Now installing dependencies..."

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
echo "Setup complete! Now configure PM2 and Nginx."
