# 📊 Analytics Setup Guide

> Complete analytics tracking for MarketingTool.Pro

---

## 🎯 TRACKING SYSTEMS TO SET UP

### 1. Google Analytics 4
**Purpose:** Website traffic, user behavior, conversions

**Setup:**
```bash
# 1. Go to: https://analytics.google.com/
# 2. Create new property: "MarketingTool.Pro"
# 3. Get Measurement ID (starts with G-)
# 4. Add tracking code to your site
```

**Tracking Code:**
```html
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
  
  // Track custom events
  function trackToolLaunch(toolName) {
    gtag('event', 'tool_launch', {
      'tool_name': toolName,
      'source': 'webflow_extension'
    });
  }
  
  function trackInstall() {
    gtag('event', 'extension_install', {
      'platform': 'webflow'
    });
  }
</script>
```

### 2. Mixpanel
**Purpose:** Product analytics, user engagement, funnels

**Setup:**
```bash
# 1. Go to: https://mixpanel.com/
# 2. Create project: "MarketingTool.Pro"
# 3. Get Project Token
# 4. Integrate with your extension
```

**Tracking Code:**
```html
<!-- Mixpanel -->
<script>
(function(f,b){if(!b.__SV){var e,g,i,h;window.mixpanel=b;b._i=[];b.init=function(e,f,c){function g(a,d){var b=d.split(".");2==b.length&&(a=a[b[0]],d=b[1]);a[d]=function(){a.push([d].concat(Array.prototype.slice.call(arguments,0)))}}var a=b;"undefined"!==typeof c?a=b[c]=[]:c="mixpanel";a.people=a.people||[];a.toString=function(a){var d="mixpanel";"mixpanel"!==c&&(d+="."+c);a||(d+=" (stub)");return d};a.people.toString=function(){return a.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms track_with_groups add_group set_group remove_group register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking start_batch_senders people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user people.remove".split(" ");
for(h=0;h<i.length;h++)g(a,i[h]);var j="set set_once union unset remove delete".split(" ");a.get_group=function(){function b(c){d[c]=function(){call2_args=arguments;call2=[c].concat(Array.prototype.slice.call(call2_args,0));a.push([e,call2])}}for(var d={},e=["get_group"].concat(Array.prototype.slice.call(arguments,0)),c=0;c<j.length;c++)b(j[c]);return d};b._i.push([e,f,c])};b.__SV=1.2;e=f.createElement("script");e.type="text/javascript";e.async=!0;e.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?
MIXPANEL_CUSTOM_LIB_URL:"file:"===f.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";g=f.getElementsByTagName("script")[0];g.parentNode.insertBefore(e,g)}})(document,window.mixpanel||[]);

// Initialize with your token
mixpanel.init('YOUR_PROJECT_TOKEN', {
  debug: true,
  track_pageview: true,
  persistence: 'localStorage'
});

// Track events
mixpanel.track('Extension Loaded');

function trackToolUse(toolName, category) {
  mixpanel.track('Tool Used', {
    'Tool Name': toolName,
    'Category': category,
    'Platform': 'Webflow'
  });
}

function trackContentGeneration(itemCount, contentType) {
  mixpanel.track('Content Generated', {
    'Item Count': itemCount,
    'Content Type': contentType,
    'Platform': 'Webflow CMS'
  });
}
</script>
```

### 3. Stripe Revenue Tracking
**Purpose:** Payment processing, revenue monitoring, subscriptions

**Dashboard Access:**
- URL: https://dashboard.stripe.com/
- Track MRR (Monthly Recurring Revenue)
- Monitor churn rate
- View customer lifetime value

**Key Metrics to Watch:**
- `mrr` - Monthly Recurring Revenue
- `active_subscriptions` - Total active customers
- `churn_rate` - % of cancelled subscriptions
- `ltv` - Customer Lifetime Value

### 4. Webflow Analytics
**Purpose:** Extension usage within Webflow

**Available Metrics:**
- Total installs
- Active users (DAU/MAU)
- Tool launch frequency
- CMS content generated
- User retention

**Access:**
Go to: https://webflow.com/dashboard/apps → Your App → Analytics

---

## 📈 KEY METRICS TO TRACK

### User Metrics
| Metric | Formula | Target |
|--------|---------|--------|
| Total Installs | Count of installations | 100+ by Month 3 |
| Active Users | Users in last 30 days | 70% of installs |
| DAU/MAU Ratio | Daily Active / Monthly Active | >30% |
| Retention Rate | Users still active after 30 days | >60% |

### Revenue Metrics
| Metric | Formula | Target |
|--------|---------|--------|
| MRR | Sum of monthly subscriptions | $3,000+ by Month 6 |
| ARPU | MRR / Active Users | $49 |
| Churn Rate | Cancelled / Total Subscribers | <5% |
| LTV | ARPU × (1 / Churn Rate) | $980+ |

### Engagement Metrics
| Metric | Description | Target |
|--------|-------------|--------|
| Tools Per Session | Avg tools used per visit | 5+ |
| Content Generated | CMS items created | 1,000+/month |
| Session Duration | Time spent in extension | 10+ minutes |
| Feature Adoption | % using Data Client | 40%+ |

---

## 🔧 IMPLEMENTATION STEPS

### Step 1: Google Analytics
```bash
# 1. Create GA4 property
1. Go to https://analytics.google.com/
2. Create Account → "MarketingTool.Pro"
3. Create Property → "Webflow Extension"
4. Get Measurement ID (G-XXXXXXXXXX)

# 2. Add tracking code
Add to src/index.html and src/data-client.html

# 3. Set up custom events
- extension_install
- tool_launch
- content_generated
- subscription_started
```

### Step 2: Mixpanel
```bash
# 1. Create project
1. Go to https://mixpanel.com/
2. Sign up / Login
3. Create Project → "MarketingTool.Pro"
4. Get Project Token

# 2. Integrate SDK
Add Mixpanel code to your extension files

# 3. Define events
- User Signed Up
- Tool Used
- Content Generated
- Subscription Upgraded
```

### Step 3: Stripe Dashboard
```bash
# 1. Connect Stripe
1. Link your Stripe account to Webflow
2. Configure webhook endpoints
3. Set up subscription plans

# 2. Monitor revenue
Check dashboard daily for:
- New subscriptions
- Cancellations
- Revenue trends
```

### Step 4: Custom Dashboard
```bash
# Use the analytics-setup.html file I created
open ~/Desktop/marketingtool-pro/analytics-setup.html

# Features:
- Real-time metrics
- Revenue projections
- Growth charts
- User statistics
```

---

## 📊 DASHBOARD TEMPLATE

Open `analytics-setup.html` for a ready-to-use dashboard with:

✅ **Key Metrics Cards:**
- Total Installs
- Active Users
- Monthly Revenue
- Conversion Rate

✅ **Growth Chart:**
- 12-month revenue projection
- Visual trend line
- Monthly targets

✅ **Revenue Table:**
- Monthly breakdown
- Install tracking
- MRR calculations
- Status indicators

---

## 🎯 WEEKLY REVIEW CHECKLIST

Every Monday:
- [ ] Check total installs (Webflow dashboard)
- [ ] Review MRR growth (Stripe)
- [ ] Analyze user engagement (Mixpanel)
- [ ] Check conversion rate (Google Analytics)
- [ ] Review support tickets
- [ ] Check user feedback
- [ ] Monitor churn rate
- [ ] Update projections

---

## 📈 GROWTH TARGETS

### Month 1 (Post-Approval)
- **Installs:** 10
- **MRR:** $343
- **Focus:** Initial user acquisition

### Month 3
- **Installs:** 30 total
- **MRR:** $1,030
- **Focus:** Product-market fit

### Month 6
- **Installs:** 75 total
- **MRR:** $2,573
- **Focus:** Scale marketing

### Month 12
- **Installs:** 200+ total
- **MRR:** $6,860+
- **Focus:** Enterprise sales

---

## 🚨 ALERTS TO SET UP

### Revenue Alerts
- MRR drops by >10%
- Churn rate exceeds 5%
- Failed payment attempts

### Usage Alerts
- DAU drops by >20%
- Error rate exceeds 1%
- Support tickets spike

### Growth Alerts
- New installs below target
- Conversion rate drops
- User complaints increase

---

## 📞 SUPPORT

**Questions about analytics?**
- Google Analytics Help: https://support.google.com/analytics
- Mixpanel Docs: https://docs.mixpanel.com/
- Stripe Support: https://support.stripe.com/

---

**Your analytics infrastructure is ready to track success!** 📊✨
