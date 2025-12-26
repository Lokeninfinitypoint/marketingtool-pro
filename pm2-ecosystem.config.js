// PM2 Ecosystem Configuration
// Place in: /var/www/marketingtool/ecosystem.config.js

module.exports = {
  apps: [
    {
      name: 'aitool-app',
      cwd: '/var/www/marketingtool/aitool-app',
      script: 'npm',
      args: 'start',
      instances: 2,
      exec_mode: 'cluster',
      env: {
        NODE_ENV: 'production',
        PORT: 3000,
      },
      error_file: '/var/log/pm2/aitool-error.log',
      out_file: '/var/log/pm2/aitool-out.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss Z',
      merge_logs: true,
      autorestart: true,
      max_memory_restart: '1G',
      watch: false,
    },
    {
      name: 'dashboard-app',
      cwd: '/var/www/marketingtool/dashboard-app',
      script: 'npm',
      args: 'start',
      instances: 2,
      exec_mode: 'cluster',
      env: {
        NODE_ENV: 'production',
        PORT: 3001,
      },
      error_file: '/var/log/pm2/dashboard-error.log',
      out_file: '/var/log/pm2/dashboard-out.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss Z',
      merge_logs: true,
      autorestart: true,
      max_memory_restart: '1G',
      watch: false,
    },
  ],
};
