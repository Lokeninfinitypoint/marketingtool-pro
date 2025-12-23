# WordStream Clone WordPress Theme

A professional WordPress theme designed to mimic WordStream's marketing website design, built as a child theme of Divi.

## Features

- 🎨 **WordStream Brand Design** - Authentic colors, typography, and layout matching WordStream.com
- 📱 **Fully Responsive** - Optimized for desktop, tablet, and mobile devices
- ⚡ **Performance Optimized** - Fast loading with optimized CSS and JavaScript
- 🔧 **Divi Compatible** - Built as a child theme of Divi for maximum flexibility
- 🛠️ **Custom Components** - Ready-to-use WordStream-style sections and elements
- 📊 **Marketing Tools Integration** - Built-in support for grader tools and lead magnets
- 🎯 **Conversion Optimized** - Designed with marketing psychology and CRO best practices

## Installation

### Prerequisites
- WordPress 5.0 or higher
- Divi Theme installed and activated
- PHP 7.4 or higher

### Steps

1. **Install Divi Theme** (if not already installed)
   - Purchase and download Divi from Elegant Themes
   - Upload and activate the Divi theme in WordPress

2. **Install WordStream Clone Theme**
   ```bash
   # Option 1: Upload via WordPress Admin
   # - Zip the wordpress-theme folder
   # - Go to Appearance > Themes > Add New > Upload Theme
   # - Upload the zip file and activate

   # Option 2: FTP Upload
   # - Upload the wordpress-theme folder to /wp-content/themes/
   # - Rename it to 'wordstream-clone'
   # - Activate in WordPress admin
   ```

3. **Configure Theme**
   - Go to Appearance > Customize
   - Set up your logo in the WordStream Brand Settings section
   - Configure navigation menus (Appearance > Menus)
   - Assign menus to Primary Menu and Footer Menu locations

## Theme Structure

```
wordpress-theme/
├── style.css                 # Main theme styles with WordStream branding
├── functions.php             # Theme functionality and hooks
├── header.php                # Header template with navigation
├── footer.php                # Footer template with links
├── index.php                 # Homepage template
├── js/
│   └── wordstream-clone.js   # Custom JavaScript functionality
└── images/
    └── wordstream-logo.svg   # WordStream logo (replace with your own)
```

## Customization

### Brand Colors
The theme uses CSS custom properties for easy color customization:

```css
:root {
  --ws-primary-blue: #1e3b99;      /* Primary brand color */
  --ws-accent-orange: #ff6900;      /* CTA buttons and accents */
  --ws-accent-green: #00c851;       /* Success states */
  --ws-dark-gray: #333333;          /* Text and footer */
  --ws-light-gray: #f8f9fa;         /* Background sections */
}
```

### Fonts
The theme is optimized for WordStream's Unify Sans font family:
- **Primary**: 'unify sans regular' (body text)
- **Headings**: 'unify sans bold' (headings)
- **Fallback**: 'Open Sans', Arial, sans-serif

### Custom Shortcodes

#### Hero Section
```php
[ws_hero title="Your Headline" subtitle="Your subtitle" cta_text="Button Text" cta_url="/your-link"]
```

#### Statistics Section
```php
[ws_stats stat1_number="142M" stat1_label="hours saved" stat2_number="275M" stat2_label="leads delivered"]
```

## Page Templates

### Homepage (index.php)
- Hero section with main headline and CTA
- Performance grader promotion
- Business growth statistics
- Google Ads guide section
- Free keyword tool section
- Blog post highlights
- Free tools overview

### Responsive Breakpoints
- **Desktop**: 1200px+ (full layout)
- **Tablet**: 768px - 1199px (stacked grid)
- **Mobile**: Below 768px (mobile menu, single column)

## Divi Integration

This theme is designed to work seamlessly with Divi:

- **Custom Post Types**: Free Tools and Resources with Divi Builder support
- **Divi Modules**: All standard Divi modules work with WordStream styling
- **Page Builder**: Use Divi Builder for custom page layouts
- **Theme Customizer**: WordStream brand settings integrated with Divi options

## WordPress Features

### Custom Post Types
- **Free Tools**: For marketing tools and graders
- **Resources**: For guides, ebooks, and downloads

### Widget Areas
- **Sidebar**: Standard sidebar widget area
- **Footer Widgets**: Footer widget area for additional links

### Navigation Menus
- **Primary Menu**: Main navigation (header)
- **Footer Menu**: Footer navigation links

## Performance Features

- **Lazy Loading**: Images load only when needed
- **Optimized CSS**: Minified and organized stylesheets
- **JavaScript Optimization**: Efficient jQuery-based interactions
- **Font Loading**: Preloaded critical fonts for faster rendering

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Internet Explorer 11+

## Development

### Local Development Setup
1. Set up local WordPress environment (XAMPP, MAMP, or Docker)
2. Install Divi theme
3. Clone this theme to your themes directory
4. Activate and start customizing

### CSS Architecture
- **Variables**: CSS custom properties for consistent theming
- **Components**: Modular CSS for reusable elements
- **Responsive**: Mobile-first responsive design
- **Performance**: Optimized selectors and minimal overrides

## Support

For questions and support:
1. Check the WordPress admin for any error messages
2. Ensure Divi theme is properly installed and activated
3. Verify all file permissions are set correctly
4. Check browser console for JavaScript errors

## License

This theme is provided as-is for educational and development purposes. Make sure to comply with:
- Divi license requirements
- WordStream trademark and brand guidelines
- WordPress GPL license

## Changelog

### Version 1.0
- Initial release with full WordStream design replication
- Responsive design for all devices
- Divi theme compatibility
- Custom post types for tools and resources
- Performance optimizations
- SEO-friendly structure

---

**Note**: This theme is inspired by WordStream's design for educational purposes. Please respect WordStream's branding and trademarks when using this theme for commercial projects.