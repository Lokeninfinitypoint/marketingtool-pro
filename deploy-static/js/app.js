/**
 * MarketingTool.pro - Marketing Tools Directory
 * V1: Complete standalone app with 425+ tools
 * Shows all tools organized by systems/categories
 */

// Import tools data
let TOOLS_DATA = {};
let TOTAL_TOOLS = 0;

// Load tools data - tools-data.js is loaded via script tag in HTML
// Wait for it to be available
function waitForToolsData() {
  if (typeof TOOLS_DATABASE !== 'undefined') {
    TOOLS_DATA = TOOLS_DATABASE;
    TOTAL_TOOLS = typeof TOTAL_TOOLS_COUNT !== 'undefined' ? TOTAL_TOOLS_COUNT : 425;
    initApp();
  } else {
    // Wait a bit and try again
    setTimeout(waitForToolsData, 100);
  }
}

// Start checking for tools data
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    setTimeout(waitForToolsData, 500);
  });
} else {
  setTimeout(waitForToolsData, 500);
}

function loadInlineToolsData() {
  // Inline tools data as fallback
  TOOLS_DATA = {
    categories: [
      {
        id: 'advertising-ppc',
        name: 'Advertising & PPC',
        icon: '🎯',
        gradient: 'linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%)',
        count: 50,
        tools: ['Facebook Ads Manager', 'Meta AI Comment Responder', 'Meta Comment Manager', 'Facebook Performance Dashboard', 'Facebook Ads Orchestrator', 'Facebook Marketing Tools Suite', 'Real-Time Meta Ads Optimizer', 'Meta Budget Optimizer', 'Meta Campaign Analyzer', 'Meta Audience Builder', 'Meta Creative Studio', 'Meta Placement Optimizer', 'Meta Attribution Tool', 'Meta Conversion Tracker', 'Instagram Marketing Platform', 'Google Ads Performance Grader', 'Facebook Ads Performance Grader', 'Microsoft Ads Performance Grader', 'Keyword Research', 'Ad Copy Analyzer', 'Budget Calculator', 'ROI Calculator', 'CTR Predictor', 'Quality Score Checker', 'Keyword Audit', 'Campaign Audit', 'Ad Group Audit', 'Ad Audit', 'Negative Keyword Audit', 'Search Term Audit', 'Quality Score Audit', 'Placement Audit', 'Landing Page Audit', 'Bid Suggestions', 'Performance Monitor', 'Budget Manager', 'Reporting Tools', 'Ad Testing Tools', 'Ads Launcher', 'Ads Rotation Agent', 'Creative Refresh Agent', 'AI Ads Generator', 'Ad Fatigue Detector', 'Advanced Ad Analyzer', 'Automated Ad Launch Tool', 'AI Ad Management Tools', 'AI Advertising Tools Suite', 'Campaign Cloner Pro', 'Campaign Template Library', 'Campaign Builder', 'Campaign Optimizer']
      },
      {
        id: 'content-creation',
        name: 'Content Creation',
        icon: '✍️',
        gradient: 'linear-gradient(135deg, #FA709A 0%, #FEE140 100%)',
        count: 50,
        tools: ['Article Generator', 'Article Rewriter', 'Article Summarizer', 'Blog Intro Writer', 'Blog Conclusion Writer', 'Blog Outline Writer', 'Blog Post Ideas', 'Content Rewriter', 'Copy Generator', 'CTA Writer', 'Email Writer', 'Hashtag Generator', 'Marketing Copy', 'Product Description', 'Social Media Post', 'YouTube Description Generator', 'YouTube Title Generator', 'Paragraph Expander', 'Paragraph Improver', 'Paragraph Rewriter', 'Paragraph Shortener', 'Paragraph Simplifier', 'Sentence Expander', 'Sentence Rewriter', 'Sentence Shortener', 'Sentence Simplifier', 'API Documentation', 'Code Comments', 'Technical Explanation', 'Academic Writer', 'Exam Prep', 'Explainer', 'Study Notes', 'AI Creative Director', 'Creative Intelligence Platform', 'Ad Intelligence Software', 'Creative Performance Analyzer', 'Ad Copy Sentiment Analyzer', 'Visual Performance Optimizer', 'Creative Optimization AI', 'Creative Fatigue Predictor', 'Brand Safety Monitor', 'Competitive Benchmarking AI', 'SEO Title Generator', 'Meta Description Generator', 'Backlink Outreach Email Generator', 'FAQ Schema Writer', 'Content Gap Finder', 'Internal Linking Suggestions', 'Content Strategist', 'Content Planner']
      },
      {
        id: 'seo-growth',
        name: 'SEO & Growth',
        icon: '📈',
        gradient: 'linear-gradient(135deg, #30CFD0 0%, #330867 100%)',
        count: 50,
        tools: ['On-Page SEO Checker', 'Competitor Analysis Tool', 'SERP Analyzer', 'Keyword Cluster Generator', 'Schema Generator', 'SEO Audit Tool', 'Long-Tail Keyword Generator', 'Keyword Intent Detector', 'Website Grader', 'Free Keyword Tools', 'Google Analytics Grader', 'Keyword Research', 'Backlink Analyzer', 'Rank Tracker', 'Technical SEO Checker', 'Site Speed Optimizer', 'Schema Markup Generator', 'Local SEO Optimizer', 'Competitor SEO Analyzer', 'Content Gap Analyzer', 'Link Building Tool', 'Broken Link Checker', 'XML Sitemap Generator', 'Robots.txt Generator', 'Meta Tags Generator', 'Open Graph Generator', 'Canonical URL Checker', 'Redirect Checker', 'Mobile-Friendly Test', 'Core Web Vitals Analyzer', 'Page Speed Insights', 'Structured Data Validator', 'Rich Snippets Generator', 'Breadcrumb Generator', 'Image SEO Optimizer', 'Video SEO Optimizer', 'Local Business Schema', 'Review Schema Generator', 'Product Schema Generator', 'Event Schema Generator', 'Article Schema Generator', 'FAQ Schema Generator', 'HowTo Schema Generator', 'Organization Schema', 'Person Schema Generator', 'BreadcrumbList Schema', 'WebSite Schema Generator', 'SearchAction Schema', 'AggregateRating Schema', 'Offer Schema Generator', 'SEO Performance Tracker']
      },
      {
        id: 'social-media',
        name: 'Social Media',
        icon: '📱',
        gradient: 'linear-gradient(135deg, #A8EDEA 0%, #FED6E3 100%)',
        count: 50,
        tools: ['Instagram Management Tools', 'Instagram AI Automation', 'Instagram Story Ads Manager', 'Instagram Reels Optimizer', 'Instagram Shopping Ads', 'Instagram Influencer Finder', 'Instagram Engagement Analyzer', 'Instagram Content Scheduler', 'Hashtag Generator', 'Caption Creator', 'Post Scheduler', 'Engagement Calculator', 'Image Resizer', 'Social Analytics Dashboard', 'Social Post Scheduler', 'Engagement Analyzer', 'Best Time Poster', 'Content Calendar', 'Social Listening Tool', 'Influencer Finder', 'Social ROI Calculator', 'Trend Analyzer', 'Community Manager', 'Social Media Post Generator', 'Hashtag Research Tool', 'Social Media Analytics', 'Post Performance Tracker', 'Audience Insights', 'Competitor Social Analyzer', 'Social Media Planner', 'Content Ideas Generator', 'Social Media Report Generator', 'Engagement Rate Calculator', 'Reach Analyzer', 'Impressions Tracker', 'Social Media ROI Calculator', 'Influencer Outreach Tool', 'Social Media Monitoring', 'Sentiment Analyzer', 'Brand Mention Tracker', 'Social Media Crisis Manager', 'Social Media Compliance Checker', 'Multi-Platform Manager', 'Social Media Automation', 'Social Media A/B Tester', 'Social Media Budget Planner', 'Social Media Campaign Manager', 'Social Media Performance Dashboard', 'Social Media KPI Tracker', 'Social Media Report Builder', 'Social Media Optimizer']
      },
      {
        id: 'email-marketing',
        name: 'Email Marketing',
        icon: '📧',
        gradient: 'linear-gradient(135deg, #FF9A9E 0%, #FECFEF 100%)',
        count: 50,
        tools: ['Email Template Builder', 'Subject Line Optimizer', 'Send Time Optimizer', 'A/B Test Manager', 'Open Rate Predictor', 'Email List Cleaner', 'Personalization Engine', 'Automation Workflow Builder', 'Email Performance Tracker', 'Deliverability Checker', 'Email Writer', 'Email Campaign Manager', 'Email Analytics Dashboard', 'Email Segmentation Tool', 'Email Personalization', 'Email Automation Builder', 'Email Sequence Creator', 'Email Drip Campaign', 'Email Newsletter Builder', 'Email Signature Generator', 'Email Preview Tool', 'Email Spam Checker', 'Email Validation Tool', 'Email List Manager', 'Email Unsubscribe Handler', 'Email Bounce Handler', 'Email Click Tracker', 'Email Conversion Tracker', 'Email Revenue Tracker', 'Email Engagement Score', 'Email A/B Testing', 'Email Subject Line Tester', 'Email Content Optimizer', 'Email Timing Optimizer', 'Email Frequency Optimizer', 'Email List Growth Tool', 'Email Lead Nurturing', 'Email Re-engagement Campaign', 'Email Welcome Series', 'Email Onboarding Sequence', 'Email Upsell Campaign', 'Email Cross-sell Campaign', 'Email Retention Campaign', 'Email Win-back Campaign', 'Email Survey Tool', 'Email Feedback Collector', 'Email Event Trigger', 'Email Behavioral Trigger', 'Email Segmentation Engine', 'Email Personalization AI']
      },
      {
        id: 'analytics-attribution',
        name: 'Analytics & Attribution',
        icon: '📊',
        gradient: 'linear-gradient(135deg, #667EEA 0%, #764BA2 100%)',
        count: 50,
        tools: ['Unified Analytics Platform', 'ROAS Prediction Platform', 'Performance Intelligence Dashboard', 'Campaign Optimization Engine', 'Multi-Channel Attribution', 'Conversion Path Analyzer', 'Customer Journey Intelligence', 'Performance Forecasting', 'Budget Performance Analyzer', 'ROI Intelligence Platform', 'Google Analytics Grader', 'Attribution Model Analyzer', 'Conversion Path Tracker', 'Revenue Attribution', 'Customer Journey Mapper', 'Channel Performance Compare', 'Attribution Window Optimizer', 'Cross-Channel Insights', 'Performance Decay Signals', 'ROI Prediction Engine', 'Analytics Dashboard', 'Custom Report Builder', 'Data Visualization Tool', 'KPI Dashboard', 'Real-Time Analytics', 'Historical Data Analyzer', 'Trend Analysis Tool', 'Comparative Analytics', 'Cohort Analysis Tool', 'Funnel Analysis', 'Conversion Funnel Builder', 'Goal Tracking', 'Event Tracking', 'E-commerce Analytics', 'Revenue Tracking', 'Customer Lifetime Value', 'Churn Analysis', 'Retention Analysis', 'Engagement Metrics', 'Performance Benchmarking', 'Competitive Analytics', 'Market Share Analyzer', 'Industry Benchmarking', 'Predictive Analytics', 'Forecasting Tool', 'Anomaly Detection', 'Alert System', 'Automated Reporting', 'Report Scheduler', 'Data Export Tool', 'Analytics API']
      },
      {
        id: 'automation-scaling',
        name: 'Automation & Scaling',
        icon: '⚙️',
        gradient: 'linear-gradient(135deg, #F093FB 0%, #F5576C 100%)',
        count: 50,
        tools: ['Marketing Efficiency Software', 'Intelligent Automation Platform', 'Workflow Automation Builder', 'Rule-Based Campaign Manager', 'Auto-Scaling Budget Tool', 'Smart Scheduling Engine', 'Automated Reporting Suite', 'AI Task Prioritizer', 'Campaign Auto-Optimizer', 'Performance Auto-Alerts', 'Budget Rules Engine', 'Creative Rotation Manager', 'Campaign Lifecycle Logic', 'Auto-Bid Adjuster', 'Performance-Based Pauser', 'Scaling Opportunity Finder', 'Rule-Based Optimizer', 'Automated Reporting', 'Smart Budget Reallocation', 'Campaign Health Monitor', 'Automation Workflow Builder', 'Trigger-Based Automation', 'Time-Based Automation', 'Event-Based Automation', 'Conditional Automation', 'Multi-Step Automation', 'Automation Template Library', 'Automation Testing Tool', 'Automation Performance Monitor', 'Automation Error Handler', 'Automation Log Viewer', 'Automation Analytics', 'Automation ROI Calculator', 'Automation Cost Analyzer', 'Automation Efficiency Score', 'Automation Success Rate', 'Automation Failure Alert', 'Automation Recovery Tool', 'Automation Backup System', 'Automation Version Control', 'Automation Rollback Tool', 'Automation Audit Trail', 'Automation Compliance Checker', 'Automation Security Monitor', 'Automation Access Control', 'Automation Permission Manager', 'Automation Notification System', 'Automation Integration Hub', 'Automation API Connector', 'Automation Data Sync']
      },
      {
        id: 'ai-strategy',
        name: 'AI Strategy & Forecasting',
        icon: '🤖',
        gradient: 'linear-gradient(135deg, #4FACFE 0%, #00F2FE 100%)',
        count: 50,
        tools: ['Spend Forecasting Engine', 'Outcome Simulation', 'Growth Recommendations', 'AI Campaign Strategist', 'Performance Predictor', 'Budget Forecast Analyzer', 'ROI Projection Tool', 'Market Trend Analyzer', 'Competitive Intelligence', 'Strategic Planner', 'AI Creative Director', 'AI Performance Marketer', 'AI Campaign Manager', 'Autonomous Marketing Manager', 'AI Paid Social Manager', 'AI Media Buyer', 'Marketing AI Agents Hub', 'AI Optimization Engine', 'AI Content Generator', 'AI Copywriter', 'AI Image Generator', 'AI Video Generator', 'AI Voice Generator', 'AI Translation Tool', 'AI Sentiment Analyzer', 'AI Trend Predictor', 'AI Customer Profiler', 'AI Audience Builder', 'AI Targeting Optimizer', 'AI Bid Optimizer', 'AI Creative Optimizer', 'AI Landing Page Optimizer', 'AI Conversion Optimizer', 'AI A/B Test Designer', 'AI Experiment Analyzer', 'AI Recommendation Engine', 'AI Personalization Engine', 'AI Chatbot Builder', 'AI Email Writer', 'AI Social Media Manager', 'AI SEO Optimizer', 'AI Content Strategist', 'AI Marketing Planner', 'AI Budget Allocator', 'AI Performance Analyzer', 'AI Report Generator', 'AI Insights Generator', 'AI Decision Support', 'AI Risk Analyzer', 'AI Opportunity Finder']
      },
      {
        id: 'ecommerce',
        name: 'E-Commerce',
        icon: '🛒',
        gradient: 'linear-gradient(135deg, #FFD89B 0%, #19547B 100%)',
        count: 30,
        tools: ['E-commerce Ad Platform', 'Shopify Marketing Tools', 'Shopify Campaign Manager', 'Product Feed Optimizer', 'Dynamic Product Ads', 'Shopping Campaign Intelligence', 'Cart Recovery Ads', 'Product Performance Tracker', 'Inventory-Based Ad Manager', 'Seasonal E-commerce Planner', 'Cross-Sell Ad Generator', 'Product Recommendation Engine', 'E-commerce Analytics', 'Product Listing Optimizer', 'Shopping Feed Manager', 'Product Catalog Manager', 'Inventory Sync Tool', 'Price Optimization Tool', 'Product Review Manager', 'E-commerce SEO Tool', 'Product Schema Generator', 'Rich Product Snippets', 'Shopping Ad Builder', 'Product Ad Generator', 'E-commerce Conversion Tracker', 'Shopping Cart Analyzer', 'Checkout Optimizer', 'Abandoned Cart Recovery', 'Product Upsell Tool', 'E-commerce ROI Calculator']
      },
      {
        id: 'creative-studio',
        name: 'Creative Studio',
        icon: '🎨',
        gradient: 'linear-gradient(135deg, #C471ED 0%, #F64F59 100%)',
        count: 50,
        tools: ['Creative Intelligence Platform', 'Ad Intelligence Software', 'Competitive Benchmarking AI', 'Creative Performance Analyzer', 'Brand Safety Monitor', 'Creative Fatigue Predictor', 'Ad Copy Sentiment Analyzer', 'Visual Performance Optimizer', 'Creative Optimization AI', 'Creative Studio Hub', 'Ad Template Library', 'Creative Asset Manager', 'Image Editor', 'Video Editor', 'Graphic Designer', 'Logo Maker', 'Banner Creator', 'Social Media Image Maker', 'Thumbnail Generator', 'Infographic Creator', 'Presentation Builder', 'Mockup Generator', 'Color Palette Generator', 'Font Pairing Tool', 'Icon Library', 'Stock Photo Finder', 'Image Optimizer', 'Video Compressor', 'GIF Maker', 'Animation Creator', 'Brand Kit Manager', 'Asset Library', 'Creative Brief Builder', 'Design Approval Workflow', 'Version Control', 'Collaboration Tool', 'Creative Analytics', 'Performance Tracker', 'A/B Test Creator', 'Creative Testing Platform', 'Ad Preview Tool', 'Multi-Format Exporter', 'Creative Calendar', 'Inspiration Gallery', 'Trend Analyzer', 'Style Guide Generator', 'Brand Consistency Checker', 'Creative ROI Calculator', 'Creative Budget Planner', 'Creative Team Manager', 'Creative Workflow']
      },
      {
        id: 'crm-sales',
        name: 'CRM & Sales',
        icon: '💼',
        gradient: 'linear-gradient(135deg, #FF6B95 0%, #C44569 100%)',
        count: 20,
        tools: ['CRM Integration', 'Lead Management System', 'Contact Manager', 'Sales Pipeline Tracker', 'Deal Manager', 'Customer Database', 'Sales Analytics', 'Revenue Tracker', 'Sales Forecast', 'Lead Scoring Tool', 'Email Integration', 'Calendar Sync', 'Task Manager', 'Note Taker', 'Document Manager', 'Proposal Generator', 'Quote Builder', 'Invoice Generator', 'Payment Tracker', 'Customer Portal']
      },
      {
        id: 'optimization',
        name: 'Optimization Software',
        icon: '⚡',
        gradient: 'linear-gradient(135deg, #FFA726 0%, #FB8C00 100%)',
        count: 25,
        tools: ['Optimization Software Suite', 'Bid Optimization Engine', 'Creative Optimization AI', 'Audience Optimization Tool', 'Placement Optimization', 'Device Optimization', 'Dayparting Optimizer', 'Geo-Targeting Optimizer', 'Budget Optimization', 'Campaign Optimization', 'Ad Group Optimization', 'Keyword Optimization', 'Landing Page Optimization', 'Conversion Rate Optimization', 'A/B Testing Platform', 'Multivariate Testing', 'Heatmap Analyzer', 'Click Tracking Tool', 'Scroll Depth Analyzer', 'Form Analyzer', 'Exit Intent Detector', 'Page Speed Optimizer', 'Mobile Optimization', 'Performance Optimizer', 'ROI Optimizer']
      },
      {
        id: 'campaign-management',
        name: 'Campaign Management',
        icon: '📋',
        gradient: 'linear-gradient(135deg, #42A5F5 0%, #1E88E5 100%)',
        count: 50,
        tools: ['Campaign Tools Suite', 'Multi-Platform Campaign Hub', 'Campaign Cloner Pro', 'Campaign Template Library', 'Campaign Performance Predictor', 'Campaign Health Monitor', 'Campaign Scaling Assistant', 'Campaign Budget Allocator', 'Campaign A/B Test Manager', 'Campaign Compliance Checker', 'Campaign Builder', 'Campaign Editor', 'Campaign Scheduler', 'Campaign Launcher', 'Campaign Pauser', 'Campaign Archiver', 'Campaign Analyzer', 'Campaign Reporter', 'Campaign Optimizer', 'Campaign Manager', 'Multi-Campaign Manager', 'Campaign Comparison Tool', 'Campaign Performance Tracker', 'Campaign ROI Calculator', 'Campaign Budget Tracker', 'Campaign Spend Analyzer', 'Campaign Efficiency Score', 'Campaign Success Predictor', 'Campaign Risk Analyzer', 'Campaign Opportunity Finder', 'Campaign Automation', 'Campaign Workflow Builder', 'Campaign Approval System', 'Campaign Collaboration', 'Campaign Comments', 'Campaign Notes', 'Campaign Tags', 'Campaign Labels', 'Campaign Filters', 'Campaign Search', 'Campaign Export', 'Campaign Import', 'Campaign Backup', 'Campaign Restore', 'Campaign Versioning', 'Campaign History', 'Campaign Audit Trail', 'Campaign Analytics', 'Campaign Insights', 'Campaign Recommendations', 'Campaign Strategist']
      }
    ]
  };
  
  TOTAL_TOOLS = TOOLS_DATA.categories.reduce((sum, cat) => sum + cat.tools.length, 0);
}

// Marketing Tools App
class MarketingToolsApp {
  constructor() {
    this.state = {
      searchQuery: '',
      selectedCategory: 'all',
      viewMode: 'categories' // 'categories' or 'tools'
    };
  }

  init() {
    // Hide loading screen
    setTimeout(() => {
      const loading = document.querySelector('.loading-screen');
      if (loading) {
        loading.style.opacity = '0';
        setTimeout(() => loading.style.display = 'none', 500);
      }
    }, 500);

    this.render();
    this.attachEventListeners();
  }

  render() {
    const root = document.getElementById('root');
    root.innerHTML = `
      <div class="app-container">
        ${this.renderHeader()}
        ${this.renderWelcome()}
        ${this.renderSearch()}
        ${this.renderCategoryFilters()}
        ${this.renderContent()}
      </div>
    `;
  }

  renderHeader() {
    return `
      <header class="app-header">
        <div class="header-content">
          <div class="logo-section">
            <div class="logo">M</div>
            <div>
              <h1>Marketing Tools</h1>
              <p>Professional Suite</p>
            </div>
          </div>
          <nav class="header-nav">
            <a href="https://marketingtool.pro" class="nav-link">Website</a>
            <a href="https://marketingtool.pro/pricing" class="nav-link">Pricing</a>
          </nav>
        </div>
      </header>
    `;
  }

  renderWelcome() {
    return `
      <section class="welcome-section">
        <div class="welcome-card">
          <h2>Welcome to Your Marketing Arsenal</h2>
          <p>Access ${TOTAL_TOOLS}+ professional tools designed to supercharge your marketing campaigns.</p>
          <div class="welcome-actions">
            <button class="btn btn-primary" onclick="app.scrollToTools()">Explore All Tools →</button>
            <button class="btn btn-secondary">Create Campaign</button>
          </div>
        </div>
      </section>
    `;
  }

  renderSearch() {
    return `
      <section class="search-section">
        <div class="search-container">
          <input 
            type="text" 
            id="search-input"
            class="search-input" 
            placeholder="Q Search ${TOTAL_TOOLS}+ marketing tools..."
            value="${this.state.searchQuery}"
          />
        </div>
      </section>
    `;
  }

  renderCategoryFilters() {
    const categories = TOOLS_DATA.categories;
    const activeCategory = this.state.selectedCategory;
    
    return `
      <section class="category-filters-section">
        <div class="category-filters">
          <button 
            class="category-filter-btn ${activeCategory === 'all' ? 'active' : ''}"
            onclick="app.filterByCategory('all')"
            data-category="all"
          >
            All Tools (${TOTAL_TOOLS})
          </button>
          ${categories.map(cat => `
            <button 
              class="category-filter-btn ${activeCategory === cat.id ? 'active' : ''}"
              onclick="app.filterByCategory('${cat.id}')"
              data-category="${cat.id}"
            >
              ${cat.name} (${cat.tools.length})
            </button>
          `).join('')}
          <button class="category-filter-btn dropdown-btn">
            All Categories ▼
          </button>
        </div>
      </section>
    `;
  }

  renderContent() {
    if (this.state.viewMode === 'categories') {
      return this.renderCategories();
    } else {
      return this.renderTools();
    }
  }

  renderCategories() {
    const filteredCategories = this.getFilteredCategories();
    
    return `
      <section id="tools" class="systems-section">
        <div class="systems-grid">
          ${filteredCategories.map(category => `
            <div class="system-card" data-category="${category.id}">
              <div class="system-icon" style="background: ${category.gradient}">
                ${category.icon}
              </div>
              <h3>${category.name}</h3>
              <p class="system-description">${this.getCategoryDescription(category.id)}</p>
              <div class="system-tools-count">
                <span class="count-number">${category.tools.length}</span>
                <span class="count-label">tools available</span>
              </div>
              <button class="btn-explore" onclick="app.viewCategory('${category.id}')">
                Explore Tools →
              </button>
            </div>
          `).join('')}
        </div>
      </section>
    `;
  }

  renderTools() {
    const filteredTools = this.getFilteredTools();
    
    return `
      <section id="tools-list" class="tools-list-section">
        <div class="tools-grid">
          ${filteredTools.map(tool => `
            <div class="tool-card">
              <div class="tool-badge">Premium</div>
              <h4 class="tool-name">${tool.name}</h4>
              <p class="tool-category">${tool.category}</p>
              <div class="tool-arrow">→</div>
            </div>
          `).join('')}
        </div>
      </section>
    `;
  }

  getCategoryDescription(categoryId) {
    const descriptions = {
      'advertising-ppc': 'Bidding intelligence, search intent analysis, budget allocation',
      'content-creation': 'AI-powered content generation and optimization',
      'seo-growth': 'Search optimization and organic growth tools',
      'social-media': 'Social media management and optimization',
      'email-marketing': 'Email campaign optimization and automation',
      'analytics-attribution': 'Cross-channel insights, performance decay signals, ROI prediction',
      'automation-scaling': 'Budget rules, creative rotation, campaign lifecycle logic',
      'ai-strategy': 'Spend forecasting, outcome simulation, growth recommendations',
      'ecommerce': 'E-commerce marketing and optimization tools',
      'creative-studio': 'Creative design and asset management',
      'crm-sales': 'CRM integration and sales tools',
      'optimization': 'Performance optimization and testing',
      'campaign-management': 'Campaign creation, management, and optimization'
    };
    return descriptions[categoryId] || 'Professional marketing tools';
  }

  getFilteredCategories() {
    let filtered = [...TOOLS_DATA.categories];

    if (this.state.selectedCategory !== 'all') {
      filtered = filtered.filter(c => c.id === this.state.selectedCategory);
    }

    if (this.state.searchQuery) {
      const query = this.state.searchQuery.toLowerCase();
      filtered = filtered.filter(c => 
        c.name.toLowerCase().includes(query) ||
        c.tools.some(t => t.toLowerCase().includes(query))
      );
    }

    return filtered;
  }

  getFilteredTools() {
    let tools = [];
    
    const categories = this.state.selectedCategory === 'all' 
      ? TOOLS_DATA.categories 
      : TOOLS_DATA.categories.filter(c => c.id === this.state.selectedCategory);

    categories.forEach(cat => {
      cat.tools.forEach(toolName => {
        if (!this.state.searchQuery || toolName.toLowerCase().includes(this.state.searchQuery.toLowerCase())) {
          tools.push({
            name: toolName,
            category: cat.name
          });
        }
      });
    });

    return tools;
  }

  filterByCategory(categoryId) {
    this.state.selectedCategory = categoryId;
    if (this.state.searchQuery) {
      this.state.viewMode = 'tools';
    }
    this.render();
    this.attachEventListeners();
  }

  viewCategory(categoryId) {
    this.state.selectedCategory = categoryId;
    this.state.viewMode = 'tools';
    this.render();
    this.attachEventListeners();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  scrollToTools() {
    document.getElementById('tools')?.scrollIntoView({ behavior: 'smooth' });
  }

  attachEventListeners() {
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
      searchInput.addEventListener('input', (e) => {
        this.state.searchQuery = e.target.value;
        if (this.state.searchQuery) {
          this.state.viewMode = 'tools';
        } else {
          this.state.viewMode = 'categories';
        }
        this.render();
        this.attachEventListeners();
      });
    }
  }
}

// Initialize app
function initApp() {
  if (typeof window.app === 'undefined') {
    window.app = new MarketingToolsApp();
    window.app.init();
  }
}

// Start app when DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initApp);
} else {
  initApp();
}
