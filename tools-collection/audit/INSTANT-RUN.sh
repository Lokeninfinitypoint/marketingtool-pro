# INSTANT WORDPRESS AUDIT (Copy-Paste into SSH Terminal)
# Run this directly from your Hostinger SSH session.
# No file uploads needed.

cat > /tmp/wp_audit.sh << 'AUDIT_EOF'
#!/bin/bash
# All-in-one WordPress audit
WP_ROOT="${1:-.}"
[[ -f "$WP_ROOT/wp-config.php" ]] || { echo "Error: wp-config.php not found"; exit 1; }
cd "$WP_ROOT"
TS=$(date +%Y%m%d-%H%M%S)
OUT="audit-report-$TS.txt"
{
  echo "=== WORDPRESS SITE AUDIT ==="
  echo "Time: $TS"
  echo "Root: $(pwd)"
  echo ""
  echo "== CORE =="
  wp core version 2>/dev/null || echo "WP-CLI not available"
  wp core verify-checksums 2>/dev/null || echo "⚠ Checksum issues"
  echo ""
  echo "== URLS =="
  echo "Home: $(wp option get home 2>/dev/null || echo 'N/A')"
  echo "Site: $(wp option get siteurl 2>/dev/null || echo 'N/A')"
  echo ""
  echo "== ACTIVE PLUGINS (first 20) =="
  wp plugin list --status=active 2>/dev/null | head -n 20 || echo "N/A"
  echo ""
  echo "== DATABASE =="
  wp db check 2>/dev/null && echo "✓ OK" || echo "⚠ Issue"
  echo ""
  echo "== ADMIN USERS =="
  wp user list --role=administrator --fields=ID,user_login,user_email 2>/dev/null || echo "N/A"
  echo ""
  echo "== CRON (next 10) =="
  wp cron event list 2>/dev/null | head -n 10 || echo "N/A"
  echo ""
  echo "=== END AUDIT ==="
} | tee "$OUT"
echo ""
echo "✓ Report saved: $OUT"
ls -lh "$OUT"
AUDIT_EOF

# Run it
bash /tmp/wp_audit.sh "${1:-.}"
