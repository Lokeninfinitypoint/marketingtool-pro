# 🔧 Nginx Won't Start - Troubleshooting

## Problem: Config test passes but service won't start

---

## 🚀 Quick Fix - Run This

Copy and paste this entire block:

```bash
# Kill everything
systemctl stop nginx 2>/dev/null || true
pkill -9 nginx 2>/dev/null || true
pkill -9 certbot 2>/dev/null || true
fuser -k 80/tcp 2>/dev/null || true
sleep 3

# Check what's using port 80
echo "=== Port 80 Check ==="
lsof -i :80 || echo "Port 80 is free"
netstat -tulpn | grep :80 || echo "Port 80 is free"

# Check Nginx config
echo ""
echo "=== Testing Config ==="
nginx -t

# Try starting
echo ""
echo "=== Starting Nginx ==="
systemctl start nginx

# Check status
echo ""
echo "=== Status ==="
systemctl status nginx --no-pager | head -15

# If failed, show logs
if ! systemctl is-active --quiet nginx; then
    echo ""
    echo "=== Error Logs ==="
    journalctl -xeu nginx.service --no-pager | tail -30
fi
```

---

## 🔍 Common Issues & Fixes

### Issue 1: Port 80 Already in Use

**Check:**
```bash
lsof -i :80
netstat -tulpn | grep :80
```

**Fix:**
```bash
# Find and kill process
fuser -k 80/tcp
# Or
kill -9 $(lsof -t -i:80)
```

### Issue 2: Another Web Server Running

**Check:**
```bash
systemctl list-units | grep -E 'apache|httpd|nginx'
ps aux | grep -E 'apache|httpd|nginx'
```

**Fix:**
```bash
# Stop Apache if running
systemctl stop apache2 2>/dev/null || systemctl stop httpd 2>/dev/null
systemctl disable apache2 2>/dev/null || systemctl disable httpd 2>/dev/null
```

### Issue 3: Permission Issues

**Fix:**
```bash
# Check Nginx user
grep "user" /etc/nginx/nginx.conf

# Fix permissions
chown -R www-data:www-data /var/log/nginx
chmod -R 755 /var/log/nginx
```

### Issue 4: PID File Lock

**Fix:**
```bash
# Remove PID file
rm -f /var/run/nginx.pid
rm -f /var/run/nginx/nginx.pid

# Try starting again
systemctl start nginx
```

---

## 🎯 Complete Reset

If nothing works, reset Nginx:

```bash
# Stop everything
systemctl stop nginx
pkill -9 nginx
fuser -k 80/tcp 2>/dev/null || true

# Remove PID files
rm -f /var/run/nginx.pid
rm -f /var/run/nginx/nginx.pid

# Check config
nginx -t

# Start fresh
systemctl daemon-reload
systemctl start nginx
systemctl status nginx
```

---

## ✅ Verify It's Working

```bash
# Check status
systemctl status nginx

# Check port
netstat -tulpn | grep :80

# Test locally
curl http://localhost

# Test domain
curl http://marketingtool.pro
```

---

*Run the quick fix script above to diagnose and fix!*
