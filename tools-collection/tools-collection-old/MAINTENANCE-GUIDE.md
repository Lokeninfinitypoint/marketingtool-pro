# 🚀 SITE MAINTENANCE & MONITORING GUIDE
**adswebstrom.com** | Quick Reference

---

## ✅ WHAT'S WORKING NOW

| Feature | Status | Location |
|---------|--------|----------|
| **Homepage** | ✅ Working | https://adswebstrom.com/ |
| **Shop** | ✅ Working | https://adswebstrom.com/shop |
| **Cart** | ✅ Working | https://adswebstrom.com/cart |
| **Checkout** | ✅ Working | https://adswebstrom.com/checkout |
| **My Account** | ✅ Working | https://adswebstrom.com/my-account |
| **Calculator** | ✅ Working | https://adswebstrom.com/tools/calculator/ |
| **Converter** | ✅ Working | https://adswebstrom.com/tools/converter/ |
| **Admin Panel** | ✅ Working | https://adswebstrom.com/wp-admin/ |

---

## 🔧 QUICK MAINTENANCE COMMANDS

### Check Site Health
```bash
# SSH into server
ssh -p 65002 u520004865@77.37.90.129

# Navigate to WordPress root
cd /home/u520004865/public_html

# Quick health check
wp core version
wp plugin status
wp theme status
wp db check
```

### Update Everything
```bash
# Update WordPress core
wp core update

# Update all plugins
wp plugin update --all

# Update themes
wp theme update --all

# Check for updates without applying
wp plugin update --all --dry-run
```

### Cache Management
```bash
# Flush WordPress cache
wp cache flush

# Clear transients (temporary data)
wp transient delete --all

# Clear LiteSpeed cache (if needed)
# Note: Usually automatic, but can be done from Hostinger hPanel
```

### Database Optimization
```bash
# Verify database integrity
wp db check

# Optimize database tables
wp db optimize

# Check for autoload bloat
wp option list --autoload=on --fields=option_name,size | sort -nr -k2 | head -20
```

---

## 📋 MONTHLY MAINTENANCE CHECKLIST

- [ ] Run database check: `wp db check && wp db optimize`
- [ ] Verify no stuck cron tasks: `wp cron event list`
- [ ] Check for plugin updates: `wp plugin update --all --dry-run`
- [ ] Review recent errors: `tail -20 wp-content/debug.log`
- [ ] Verify admin users: `wp user list --role=administrator`
- [ ] Test backup restoration to staging
- [ ] Check SSL certificate expiration
- [ ] Verify WooCommerce orders processing correctly
- [ ] Test checkout form submission
- [ ] Confirm calculator and converter tools loading
- [ ] Verify responsive design on mobile
- [ ] Check for any 404 errors in logs

---

## 🔍 MONITORING DASHBOARD

### Key Pages to Monitor
- **Homepage:** https://adswebstrom.com/ (should load in < 3 seconds)
- **Shop:** https://adswebstrom.com/shop (products should display)
- **Cart:** Test adding items - should work seamlessly
- **Checkout:** Verify payment flow is smooth
- **Tools:** Both calculator and converter should be accessible

### Performance Indicators
- **Page Load Time:** Track trends (currently ✅ fast with LiteSpeed)
- **Database Size:** Monitor growth (currently ✅ healthy)
- **Plugin Count:** Keep under 30 (currently ✅ 25 plugins)
- **Server Errors:** Should be 0 in logs (currently ✅ clean)

### WooCommerce Health
- **Product Count:** Verify all products visible
- **Cart Functionality:** Test add-to-cart button
- **Checkout Process:** Verify all steps work
- **Order History:** Check My Account page loads
- **Payment Gateway:** Monitor transaction success rates

---

## 🎯 COMMON ISSUES & FIXES

### Issue: Page showing "Coming Soon"
**Solution:** 
1. Log into Hostinger hPanel
2. Go to Website Settings
3. Turn off "Coming Soon" page
4. Flush LiteSpeed cache

### Issue: Tools not loading
**Solution:**
1. Check `.htaccess` bypass rules for `/tools/`
2. Verify `.htaccess` exists and is readable
3. Run: `wp rewrite flush`
4. Clear LiteSpeed cache

### Issue: WooCommerce pages showing 404
**Solution:**
1. Verify WooCommerce pages exist: `wp post list --post_type=page | grep -i cart`
2. Check page IDs are set in settings: `wp option get woocommerce_cart_page_id`
3. Flush permalinks: `wp rewrite flush`

### Issue: Slow page loads
**Solution:**
1. Check LiteSpeed cache status
2. Run database optimization: `wp db optimize`
3. Review plugin count and disable unused ones
4. Check for large autoload options: `wp option list --autoload=on --fields=option_name,size`

### Issue: Admin panel not accessible
**Solution:**
1. Verify admin user exists: `wp user list --role=administrator`
2. Ensure permissions: `chmod 755 wp-admin`
3. Check `.htaccess` doesn't block `/wp-admin/`

---

## 📞 EMERGENCY CONTACTS & INFO

### Server Details
- **Host:** Hostinger CloudLinux 8
- **IP:** 77.37.90.129
- **SSH Port:** 65002
- **User:** u520004865
- **WordPress Path:** /home/u520004865/public_html

### Database Info
- **Type:** MySQL
- **Tables:** 60+
- **Status:** ✅ Healthy and optimized
- **Auto-optimize:** Scheduled via WP-CLI cron

### Important Credentials
- **WordPress Admin:** madav6310@gmail.com
- **Admin Users:** 1 verified user
- **Recovery:** Hostinger backup available

---

## 🛡️ SECURITY CHECKLIST

- [x] SSL/HTTPS enabled and enforced
- [x] Single admin user verified
- [x] All plugins from official WordPress.org repository
- [x] WordPress core verified with checksums
- [x] Database integrity confirmed
- [x] No malware detected
- [x] Firewall active (Hostinger default)
- [x] Regular backups enabled (Hostinger)

**Monthly Security Review:**
1. Check for failed login attempts: `wp user list`
2. Verify no suspicious plugins: `wp plugin list --fields=name,status,update`
3. Review admin activities: `wp comment list --status=spam`
4. Verify user roles: `wp user list --fields=ID,user_login,roles`

---

## 📊 PERFORMANCE OPTIMIZATION TIPS

### Currently Enabled ✅
- LiteSpeed caching
- Gzip compression
- CSS/JS minification (Elementor)
- Image optimization (theme settings)
- Database optimization

### Additional Options (if needed)
- CloudFlare CDN (paid, optional)
- Lazy loading for images (Redux setting)
- Browser caching headers (already set)
- Database cleanup script (run monthly)

---

## 🔗 HELPFUL COMMANDS REFERENCE

```bash
# Quick status check
wp core version && wp plugin status && wp theme status

# List all pages/posts
wp post list --post_type=page

# Search for specific page
wp post list --post_type=page --s="shop"

# Update single plugin
wp plugin update wordpress-mcp

# Disable plugin temporarily
wp plugin deactivate plugin-name

# Check for errors
wp eval 'error_log(print_r(get_option("wordpress_mcp_settings"), true));'

# Verify WooCommerce pages
wp option get woocommerce_cart_page_id
wp option get woocommerce_checkout_page_id
wp option get woocommerce_myaccount_page_id

# Clear all transients
wp transient delete --all

# Backup (local)
wp db export backup-$(date +%Y%m%d).sql
```

---

## ✨ FINAL NOTES

Your site is **fully operational** and requires minimal maintenance. The automated checks have verified:

- ✅ All pages loading correctly
- ✅ Navigation working properly
- ✅ Tools (Calculator & Converter) functional
- ✅ WooCommerce integration complete
- ✅ No broken links or 404 errors
- ✅ Security measures in place
- ✅ Performance optimized with LiteSpeed caching

**Recommended Cadence:**
- **Weekly:** Spot-check homepage and shop
- **Monthly:** Run full maintenance checklist
- **Quarterly:** Test backup restoration
- **As-needed:** Update plugins and themes

**Last Validation:** 2025-11-21  
**Next Recommended Check:** 2025-12-21 (monthly)

---

For questions or issues, refer to the comprehensive `SITE-VALIDATION-REPORT.md` for detailed diagnostics.
