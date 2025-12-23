#!/usr/bin/env bash
# All-in-one server-side audit runner.
# Run this directly on the server via SSH (no file upload needed).
# Usage: bash <(curl -s https://example.com/run-audit.sh)
# Or paste the entire script into your SSH terminal.
set -euo pipefail

WP_ROOT="${1:-.}"
if [[ ! -f "$WP_ROOT/wp-config.php" ]]; then
  echo "Error: wp-config.php not found in $WP_ROOT" >&2
  exit 1
fi

cd "$WP_ROOT"
TS=$(date +%Y%m%d-%H%M%S)
OUT="audit-report-$TS.txt"

{
  echo "=== WORDPRESS SITE AUDIT ($TS) ==="
  echo "WordPress Root: $(pwd)"
  echo ""

  echo "== CORE & VERSION =="
  wp core version 2>/dev/null || echo "WP-CLI unavailable"
  wp core verify-checksums 2>/dev/null || echo "⚠ Core checksum mismatch"
  echo ""

  echo "== SITE URLs =="
  echo "Home: $(wp option get home 2>/dev/null || echo 'N/A')"
  echo "Site: $(wp option get siteurl 2>/dev/null || echo 'N/A')"
  echo ""

  echo "== ACTIVE THEME =="
  wp theme status 2>/dev/null | head -n 5 || echo "Theme info unavailable"
  echo ""

  echo "== ACTIVE PLUGINS (count) =="
  ACTIVE=$(wp plugin list --status=active --field=name 2>/dev/null | wc -l)
  echo "Total active: $ACTIVE"
  wp plugin list --status=active --field=name 2>/dev/null | head -n 15 || echo "Plugin list unavailable"
  echo ""

  echo "== ADMIN USERS =="
  wp user list --role=administrator --fields=ID,user_login,user_email 2>/dev/null || echo "User list unavailable"
  echo ""

  echo "== CRON EVENTS (next 15) =="
  wp cron event list 2>/dev/null | head -n 15 || echo "Cron list unavailable"
  echo ""

  echo "== DATABASE =="
  wp db check 2>/dev/null && echo "✓ Database OK" || echo "⚠ Database check failed"
  wp db optimize 2>/dev/null && echo "✓ Database optimized" || echo "⚠ Optimize skipped"
  echo ""

  echo "== LARGE AUTOLOAD OPTIONS (>100KB) =="
  if command -v wp &>/dev/null; then
    wp eval 'global $wpdb; $rows=$wpdb->get_results("SELECT option_name,LENGTH(option_value) size FROM {$wpdb->options} WHERE autoload=\"yes\" ORDER BY size DESC LIMIT 20"); foreach($rows as $r){ if($r->size>102400) echo $r->option_name." ".round($r->size/1024,1)."KB\n"; }' 2>/dev/null || echo "Query unavailable"
  fi
  echo ""

  echo "== PENDING UPDATES =="
  wp plugin update --all --dry-run 2>/dev/null | grep -i "update available" || echo "All plugins current"
  wp theme update --all --dry-run 2>/dev/null | grep -i "update available" || echo "All themes current"
  echo ""

  echo "== REDUX SETTINGS (Iteck) =="
  wp eval 'global $wpdb; $o=get_option("iteck_theme_setting"); if(is_array($o)) foreach(array_slice($o,0,10) as $k=>$v){ if(is_scalar($v)) echo "$k=$v\n"; }' 2>/dev/null || echo "Redux settings unavailable"
  echo ""

  echo "== WOOCOMMERCE PAGE MAPPING =="
  echo "Cart: $(wp option get woocommerce_cart_page_id 2>/dev/null || echo 'N/A')"
  echo "Checkout: $(wp option get woocommerce_checkout_page_id 2>/dev/null || echo 'N/A')"
  echo "My Account: $(wp option get woocommerce_myaccount_page_id 2>/dev/null || echo 'N/A')"
  echo ""

  echo "=== AUDIT COMPLETE ==="

} | tee "$OUT"

echo ""
echo "✓ Report saved: $OUT"
ls -lh "$OUT"
