# 🌐 marketingtool.pro - Domain Status & Access

## Your Domains

- **Main:** marketingtool.pro (aitool-app on port 3000)
- **Dashboard:** app.marketingtool.pro (dashboard-app on port 3001)
- **WWW:** www.marketingtool.pro (redirects to main)

---

## ✅ Check if Apps are Running

On your server, run:

```bash
# Check PM2 status
pm2 status

# Should show:
# ┌─────┬──────────────┬─────────┬─────────┬──────────┐
# │ id  │ name         │ status  │ port    │ restarts │
# ├─────┼──────────────┼─────────┼─────────┼──────────┤
# │ 0   │ aitool-app   │ online  │ 3000    │ 0        │
# │ 1   │ dashboard-app│ online  │ 3001    │ 0        │
# └─────┴──────────────┴─────────┴─────────┴──────────┘

# Test locally
curl http://localhost:3000
curl http://localhost:3001

# Check Nginx
systemctl status nginx
nginx -t
```

---

## 🌐 Access Your Sites

### HTTP (Port 80):
- http://marketingtool.pro
- http://www.marketingtool.pro
- http://app.marketingtool.pro

### HTTPS (After SSL setup):
- https://marketingtool.pro
- https://www.marketingtool.pro
- https://app.marketingtool.pro

---

## 🔒 Setup SSL Certificate

If not done yet, run on server:

```bash
certbot --nginx -d marketingtool.pro -d www.marketingtool.pro -d app.marketingtool.pro --non-interactive --agree-tos --email admin@marketingtool.pro
```

---

## 🔍 Troubleshooting

### Domain not loading?

1. **Check DNS:**
   ```bash
   # From your local machine
   nslookup marketingtool.pro
   # Should show: 31.220.107.19
   ```

2. **Check if apps are running:**
   ```bash
   pm2 status
   pm2 logs
   ```

3. **Check Nginx:**
   ```bash
   systemctl status nginx
   tail -f /var/log/nginx/error.log
   ```

4. **Check ports:**
   ```bash
   netstat -tulpn | grep -E '3000|3001'
   ```

### Apps not starting?

```bash
# Check logs
pm2 logs aitool-app
pm2 logs dashboard-app

# Restart
pm2 restart all

# Rebuild if needed
cd /var/www/marketingtool/aitool-app && npm run build
cd /var/www/marketingtool/dashboard-app && npm run build
pm2 restart all
```

---

## 📊 Quick Status Check

Run this on server:

```bash
echo "=== PM2 Status ===" && \
pm2 status && \
echo "" && \
echo "=== Nginx Status ===" && \
systemctl status nginx --no-pager -l && \
echo "" && \
echo "=== Port Check ===" && \
netstat -tulpn | grep -E '3000|3001|80|443' && \
echo "" && \
echo "=== Test Apps ===" && \
curl -s http://localhost:3000 | head -5 && \
echo "" && \
curl -s http://localhost:3001 | head -5
```

---

## 🎯 Expected Results

After deployment, you should see:

✅ **PM2:** Both apps running (online status)
✅ **Nginx:** Active and running
✅ **Ports:** 3000, 3001, 80, 443 listening
✅ **Local test:** curl returns HTML content
✅ **Domain:** Accessible via browser

---

## 🔗 Quick Links

- **Main App:** http://marketingtool.pro
- **Dashboard:** http://app.marketingtool.pro
- **Server IP:** 31.220.107.19

---

*Check your domain status and access your apps!*
