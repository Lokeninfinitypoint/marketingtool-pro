# ✅ Publish Rebrand - Quick Checklist

## Your Company Information

**Business Name**: MarketingTool.pro
**Address**: F-12 Govinddam Tower, Jaipur 302012
**Email**: Help@marketingtool.pro
**Phone**: +91 85555744532

---

## Quick Actions (30 minutes)

### 1. Update Footer in Webflow (15 min)

**Open Webflow Designer** → Navigate to Footer section

#### Contact Information Block:

Add or update these elements:

```
Contact Us

📍 F-12 Govinddam Tower, Jaipur 302012
✉️  Help@marketingtool.pro
📞 +91 85555744532
```

**In Webflow**:
1. Select footer section
2. Find or create "Contact Information" div
3. Add 3 text blocks:
   - Address: "F-12 Govinddam Tower, Jaipur 302012"
   - Email: "Help@marketingtool.pro" (make it a link: `mailto:Help@marketingtool.pro`)
   - Phone: "+91 85555744532" (make it a link: `tel:+918555574453`)

#### Copyright:
```
© 2025 MarketingTool.pro. All rights reserved.
```

### 2. Update Contact Page (10 min)

**Navigate to Contact Page** in Webflow

Update these sections:

**Contact Cards**:
```
Email: Help@marketingtool.pro
Phone: +91 85555744532
Address: F-12 Govinddam Tower, Jaipur 302012
```

**Contact Form** (if not already set):
- Form name: "Contact Form"
- Submit email: Help@marketingtool.pro

### 3. Update Header/Navbar (5 min)

**Sign In Button** link:
- URL: `https://app.marketingtool.pro/login`

**Start Free Trial Button** link:
- URL: `https://app.marketingtool.pro/signup`

---

## Footer Layout in Webflow

### Complete Footer Structure:

```
Footer Section
├─ Container
│  ├─ Footer Hero
│  │  ├─ Logo
│  │  └─ Tagline
│  │
│  ├─ Footer Columns (Grid: 3 columns)
│  │  ├─ Column 1: Product
│  │  │  ├─ Heading: "Product"
│  │  │  └─ Links (6 links to tool categories)
│  │  │
│  │  ├─ Column 2: Resources
│  │  │  ├─ Heading: "Resources"
│  │  │  └─ Links (Documentation, Academy, Help, etc.)
│  │  │
│  │  └─ Column 3: Company
│  │     ├─ Heading: "Company"
│  │     └─ Links (About, Contact, Blog, Terms, Privacy)
│  │
│  ├─ Contact Section ← UPDATE THIS
│  │  ├─ Heading: "Contact Us"
│  │  └─ Contact Details
│  │     ├─ 📍 F-12 Govinddam Tower, Jaipur 302012
│  │     ├─ ✉️  Help@marketingtool.pro
│  │     └─ 📞 +91 85555744532
│  │
│  └─ Footer Bottom
│     ├─ Copyright: "© 2025 MarketingTool.pro"
│     └─ Social Links (Facebook, Twitter, LinkedIn, Instagram)
```

---

## Step-by-Step Webflow Instructions

### Update Contact Information:

1. **Open Webflow Designer**
2. **Navigate to Footer** (scroll to bottom of any page)
3. **Find or Create "Contact Us" section**:
   - If it doesn't exist, add a div block
   - Name it "Footer Contact"

4. **Add Heading**:
   - Add Text block
   - Text: "Contact Us"
   - Style: H4, white color, margin-bottom: 24px

5. **Add Address**:
   - Add div with class "contact-item"
   - Add emoji/icon: 📍
   - Add text: "F-12 Govinddam Tower, Jaipur 302012"
   - Style: color #9CA3AF, font-size 14px

6. **Add Email**:
   - Add div with class "contact-item"
   - Add emoji/icon: ✉️
   - Add **Link Block**:
     - Text: "Help@marketingtool.pro"
     - URL: `mailto:Help@marketingtool.pro`
     - Color: #60A5FA (blue)
     - Hover: #93C5FD (lighter blue)

7. **Add Phone**:
   - Add div with class "contact-item"
   - Add emoji/icon: 📞
   - Add **Link Block**:
     - Text: "+91 85555744532"
     - URL: `tel:+918555574453`
     - Color: #60A5FA
     - Hover: #93C5FD

8. **Style Contact Section**:
   - Background: rgba(255,255,255,0.05)
   - Padding: 32px
   - Border-radius: 8px
   - Margin-bottom: 40px

---

## Contact Page Updates

### Update Contact Information Cards:

**Card 1: Email Support**
```
✉️ Email Us
Help@marketingtool.pro
Response time: Within 24 hours
```

**Card 2: Phone Support**
```
📞 Call Us
+91 85555744532
Monday - Friday: 9 AM - 6 PM IST
```

**Card 3: Visit Us**
```
📍 Office Location
F-12 Govinddam Tower
Jaipur 302012
Rajasthan, India
```

### Update Contact Form Settings:

1. Select the form element
2. Go to Form Settings
3. Set "Notification Email": Help@marketingtool.pro
4. Set "Redirect URL" (optional): /thank-you

---

## Test Before Publishing

### Pre-Publish Checklist:

- [ ] Footer shows correct address
- [ ] Footer shows correct email (clickable)
- [ ] Footer shows correct phone (clickable)
- [ ] Contact page shows all correct info
- [ ] Contact form sends to Help@marketingtool.pro
- [ ] Copyright shows "© 2025 MarketingTool.pro"
- [ ] All links work (test email and phone clicks)
- [ ] Mobile view looks correct

---

## Publish Steps

### 1. Preview First:
- Click **Preview** button in Webflow
- Scroll to footer
- Verify all contact info is correct
- Test email link (should open email client)
- Test phone link (should open dialer on mobile)

### 2. Publish:
- Click **Publish** button (top right)
- Select **Publish to Selected Domains**
- Choose: www.marketingtool.pro
- Click **Publish to Selected Domains**
- Wait for publish to complete (~30 seconds)

### 3. Verify Live Site:
- Go to www.marketingtool.pro
- Scroll to footer
- Verify all information is correct
- Test links

---

## Social Media Links (Optional - Add Later)

### Footer Social Icons:

Add these links when you create social profiles:

- **Facebook**: https://facebook.com/marketingtoolpro
- **Twitter**: https://twitter.com/marketingtoolpro
- **LinkedIn**: https://linkedin.com/company/marketingtoolpro
- **Instagram**: https://instagram.com/marketingtoolpro

**In Webflow**:
1. Add div with class "social-links"
2. Add 4 link blocks
3. Insert SVG icons for each platform
4. Set URLs
5. Style: 40x40px circles, hover effect

---

## Legal Pages (Add Later)

Create these pages with contact information:

### Terms of Service (`/terms`):
- Company: MarketingTool.pro
- Address: F-12 Govinddam Tower, Jaipur 302012
- Contact: Help@marketingtool.pro
- Governing Law: India

### Privacy Policy (`/privacy`):
- Data Controller: MarketingTool.pro
- Contact: Help@marketingtool.pro
- Address: F-12 Govinddam Tower, Jaipur 302012

### Link from Footer:
- Add "Terms of Service" link
- Add "Privacy Policy" link
- Place in Company column

---

## SEO Updates (Optional)

### Add Local Business Schema:

In Webflow → Pages → Page Settings → Custom Code → Footer Code:

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "MarketingTool.pro",
  "url": "https://www.marketingtool.pro",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "F-12 Govinddam Tower",
    "addressLocality": "Jaipur",
    "postalCode": "302012",
    "addressRegion": "Rajasthan",
    "addressCountry": "IN"
  },
  "email": "Help@marketingtool.pro",
  "telephone": "+918555574453"
}
</script>
```

---

## Quick Copy-Paste

### For Footer Text Blocks:

**Address**:
```
F-12 Govinddam Tower, Jaipur 302012
```

**Email**:
```
Help@marketingtool.pro
```

**Phone**:
```
+91 85555744532
```

**Copyright**:
```
© 2025 MarketingTool.pro. All rights reserved.
```

---

## Mobile Check

### Test on Mobile View in Webflow:

1. Click mobile icon (top toolbar)
2. Check footer layout
3. Verify contact info stacks properly
4. Test tap targets (email, phone)
5. Ensure text is readable

---

## ✅ Final Checklist

Before clicking Publish:

### Footer:
- [ ] Address: F-12 Govinddam Tower, Jaipur 302012
- [ ] Email: Help@marketingtool.pro (clickable)
- [ ] Phone: +91 85555744532 (clickable)
- [ ] Copyright: © 2025 MarketingTool.pro
- [ ] Social links (if added)

### Contact Page:
- [ ] Email card shows Help@marketingtool.pro
- [ ] Phone card shows +91 85555744532
- [ ] Address card shows F-12 Govinddam Tower, Jaipur 302012
- [ ] Contact form sends to Help@marketingtool.pro

### Navigation:
- [ ] Sign In button links to app
- [ ] Start Free Trial button links to app

### Test:
- [ ] Preview in Webflow
- [ ] Test email link
- [ ] Test phone link
- [ ] Check mobile view

### Publish:
- [ ] Click Publish
- [ ] Select www.marketingtool.pro
- [ ] Confirm publish
- [ ] Verify live site

---

## 🚀 You're Ready to Publish!

**Time Required**: 30 minutes

**Steps**:
1. Open Webflow Designer
2. Update footer contact info
3. Update contact page
4. Preview
5. Publish
6. Verify

---

**Need the detailed branding guide?**
→ See **BRANDING-INFO.md** for complete specifications

**Need help with layout?**
→ Follow the footer structure diagram above

**Ready to publish?**
→ Follow the checklist and hit that Publish button! 🎉

---

*Created: December 9, 2025*
*Company: MarketingTool.pro*
*Location: Jaipur, India*
