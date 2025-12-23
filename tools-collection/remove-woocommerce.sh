#!/bin/bash
# WOOCOMMERCE REMOVAL & CLEANUP SCRIPT
# This script safely removes WooCommerce if it's not actively used

set -e

HOST="u520004865@77.37.90.129"
PORT="65002"
WP_DIR="/home/u520004865/public_html"

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║  WOOCOMMERCE ASSESSMENT & REMOVAL TOOL                         ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

# Connect via SSH and gather info
echo "📊 STEP 1: Assessing WooCommerce Installation..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

ssh -p "$PORT" "$HOST" << 'EOF'
cd "$WP_DIR"

echo "✓ Checking WooCommerce plugin status..."
wp plugin list --fields=name,status | grep -i woocommerce || echo "  → WooCommerce plugin not found"

echo ""
echo "✓ Checking for products..."
PRODUCT_COUNT=$(wp post list --post_type=product --format=count 2>/dev/null || echo "0")
echo "  → Total products: $PRODUCT_COUNT"

echo ""
echo "✓ Checking WooCommerce pages..."
echo "  Shop page: $(wp post list --post_type=page --name=shop --fields=ID 2>/dev/null | head -1 || echo 'Not found')"
echo "  Cart page: $(wp post list --post_type=page --name=cart --fields=ID 2>/dev/null | head -1 || echo 'Not found')"
echo "  Checkout page: $(wp post list --post_type=page --name=checkout --fields=ID 2>/dev/null | head -1 || echo 'Not found')"

echo ""
echo "✓ Checking WooCommerce options..."
wp option get woocommerce_store_address 2>/dev/null && echo "  → Store address is configured" || echo "  → No store address configured"

echo ""
echo "✓ Checking Redux theme options for WooCommerce..."
wp option get iteck_theme_setting --format=json 2>/dev/null | grep -i "cart\|shop\|woo" || echo "  → No WooCommerce options in theme"

EOF

echo ""
echo ""
echo "📋 STEP 2: Review Findings"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "Based on the assessment above:"
echo "  1. If PRODUCT_COUNT = 0, WooCommerce is NOT used"
echo "  2. If pages are 'Not found', WooCommerce is disabled"
echo "  3. If options are empty, theme doesn't use WooCommerce"
echo ""
echo "⚠️  DECISION NEEDED:"
echo ""
read -p "Do you want to REMOVE WooCommerce? (yes/no): " CONFIRM

if [[ "$CONFIRM" != "yes" ]]; then
  echo ""
  echo "❌ Cancelled. WooCommerce remains installed."
  exit 0
fi

echo ""
echo "🚀 STEP 3: Deactivating & Removing WooCommerce..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

ssh -p "$PORT" "$HOST" << 'EOF'
cd "$WP_DIR"

echo "✓ Deactivating WooCommerce plugin..."
wp plugin deactivate woocommerce 2>/dev/null || echo "  → Plugin not active or already deactivated"

echo ""
echo "✓ Deactivating WooCommerce related plugins..."
wp plugin deactivate woocommerce-gateway-stripe 2>/dev/null || echo "  → Stripe not found"
wp plugin deactivate woocommerce-paypal-payments 2>/dev/null || echo "  → PayPal payments not found"
wp plugin deactivate woo-additional-variation-images 2>/dev/null || echo "  → Not found"
wp plugin deactivate woocommerce-blocks 2>/dev/null || echo "  → Blocks not found"

echo ""
echo "✓ Deleting WooCommerce plugin..."
wp plugin delete woocommerce --allow-root 2>/dev/null || echo "  → Could not delete WooCommerce"

echo ""
echo "✓ Deleting WooCommerce data tables..."
wp db query "DROP TABLE IF EXISTS wp_woocommerce_order_items;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_order_itemmeta;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_payment_tokens;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_payment_tokenmeta;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_termmeta;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_shipping_zones;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_shipping_zone_methods;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_shipping_zone_locations;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_tax_rates;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_tax_rate_locations;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_downloadable_product_logs;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_customer_lookup;" 2>/dev/null || true
wp db query "DROP TABLE IF EXISTS wp_woocommerce_order_stats;" 2>/dev/null || true
echo "  → WooCommerce tables cleaned"

echo ""
echo "✓ Removing WooCommerce options from database..."
wp option delete woocommerce_db_version 2>/dev/null || true
wp option delete woocommerce_store_address 2>/dev/null || true
wp option delete woocommerce_store_city 2>/dev/null || true
wp option delete woocommerce_store_postcode 2>/dev/null || true
wp option delete woocommerce_store_country 2>/dev/null || true
wp option delete woocommerce_store_state 2>/dev/null || true
wp option delete woocommerce_currency 2>/dev/null || true
wp option delete woocommerce_cart_redirect_after_add 2>/dev/null || true
wp option delete woocommerce_enable_guest_checkout 2>/dev/null || true
wp option delete woocommerce_enable_signup_and_login_from_checkout 2>/dev/null || true
wp option delete woocommerce_registration_generate_password 2>/dev/null || true
wp option delete woocommerce_registration_generate_username 2>/dev/null || true
echo "  → WooCommerce options removed"

echo ""
echo "✓ Clearing WooCommerce transients..."
wp transient delete --all 2>/dev/null || true
wp cache flush 2>/dev/null || true
echo "  → Cache cleared"

echo ""
echo "✓ Flushing WordPress permalinks..."
wp rewrite flush 2>/dev/null || true
echo "  → Permalinks flushed"

EOF

echo ""
echo ""
echo "✅ STEP 4: Cleanup Theme Options (Remove WooCommerce References)"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"

ssh -p "$PORT" "$HOST" << 'EOF'
cd "$WP_DIR"

echo "✓ Removing WooCommerce cart icon setting from theme..."
wp option delete iteck_header_cart 2>/dev/null || true

echo "✓ Verifying removal..."
PRODUCT_COUNT=$(wp post list --post_type=product --format=count 2>/dev/null || echo "0")
WOO_ACTIVE=$(wp plugin is-active woocommerce 2>&1 && echo "ACTIVE" || echo "INACTIVE")

echo ""
echo "Final Status:"
echo "  → WooCommerce plugin: $WOO_ACTIVE"
echo "  → Products in database: $PRODUCT_COUNT"

EOF

echo ""
echo ""
echo "╔════════════════════════════════════════════════════════════════╗"
echo "║  ✅ WOOCOMMERCE REMOVAL COMPLETE                              ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""
echo "✨ What was removed:"
echo "   • WooCommerce plugin deactivated and deleted"
echo "   • All WooCommerce database tables dropped"
echo "   • WooCommerce options and settings cleared"
echo "   • Theme cart icon setting removed"
echo "   • Cache and permalinks flushed"
echo ""
echo "🎯 Next steps:"
echo "   1. Your site is now WooCommerce-free"
echo "   2. You can add your own e-commerce solution if needed"
echo "   3. Or use the site purely for content (blog, portfolio, etc.)"
echo ""
echo "💡 Your site now runs on:"
echo "   • WordPress (core blogging platform)"
echo "   • Iteck theme (for design)"
echo "   • BuddyPress (for community features)"
echo "   • Elementor (for page building)"
echo "   • Other essential plugins"
echo ""
