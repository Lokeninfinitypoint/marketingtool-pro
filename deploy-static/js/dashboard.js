/**
 * AITool.Software - Marketing Tools Dashboard
 * Madgicx.com Style - Professional Dashboard
 */

class MarketingToolsDashboard {
  constructor() {
    this.state = {
      searchQuery: '',
      selectedCategory: 'all',
      viewMode: 'grid'
    };
    this.toolsData = null;
    this.totalTools = 0;
  }

  async init() {
    try {
      console.log('🚀 Initializing dashboard...');
      const root = document.getElementById('root');
      if (!root) {
        console.error('Root element not found!');
        return;
      }

      await this.loadToolsData();
      
      setTimeout(() => {
        const loading = document.querySelector('.loading-screen');
        if (loading) {
          loading.style.opacity = '0';
          setTimeout(() => loading.style.display = 'none', 500);
        }
      }, 500);

      this.render();
      this.attachEventListeners();
      console.log('✅ Dashboard initialized!');
    } catch (error) {
      console.error('❌ Error:', error);
    }
  }

  async loadToolsData() {
    let attempts = 0;
    while (typeof TOOLS_DATABASE === 'undefined' && attempts < 50) {
      await new Promise(resolve => setTimeout(resolve, 100));
      attempts++;
    }
    if (typeof TOOLS_DATABASE !== 'undefined') {
      this.toolsData = TOOLS_DATABASE;
      this.totalTools = typeof TOTAL_TOOLS_COUNT !== 'undefined' ? TOTAL_TOOLS_COUNT : 425;
    } else {
      this.toolsData = { categories: [] };
      this.totalTools = 425;
    }
  }

  render() {
    const root = document.getElementById('root');
    if (!root) return;
    
    root.innerHTML = `
      <div class="dashboard-container">
        ${this.renderSidebar()}
        ${this.renderMainContent()}
      </div>
    `;
  }

  renderSidebar() {
    return `
      <aside class="dashboard-sidebar">
        <div class="sidebar-header">
          <div class="logo">A</div>
          <div>
            <h1>AITool</h1>
            <p>Professional Suite</p>
          </div>
        </div>
        <nav class="sidebar-nav">
          <div class="nav-section">
            <div class="nav-section-title">TOOLS</div>
            <div class="nav-item active">
              <span>All Tools</span>
            </div>
          </div>
        </nav>
      </aside>
    `;
  }

  renderMainContent() {
    return `
      <main class="dashboard-main">
        <div class="main-header">
          <h2>Marketing Tools Directory</h2>
          <p>${this.totalTools}+ professional tools</p>
        </div>
        ${this.renderSearchBar()}
        ${this.renderCategoryFilters()}
        ${this.renderToolsGrid()}
      </main>
    `;
  }

  renderSearchBar() {
    return `
      <div class="search-bar-container">
        <input type="text" id="tool-search" placeholder="Search ${this.totalTools}+ tools..." />
      </div>
    `;
  }

  renderCategoryFilters() {
    const categories = this.toolsData?.categories || [];
    return `
      <div class="category-tabs">
        <button class="category-tab ${this.state.selectedCategory === 'all' ? 'active' : ''}" 
                onclick="dashboard.filterCategory('all')">
          All (${this.totalTools})
        </button>
        ${categories.map(cat => `
          <button class="category-tab ${this.state.selectedCategory === cat.id ? 'active' : ''}"
                  onclick="dashboard.filterCategory('${cat.id}')">
            ${cat.name} (${cat.tools.length})
          </button>
        `).join('')}
      </div>
    `;
  }

  renderToolsGrid() {
    const tools = this.getFilteredTools();
    return `
      <div class="tools-grid">
        ${tools.map(tool => `
          <div class="tool-card" onclick="dashboard.viewTool('${tool.name}')">
            <h4>${this.escapeHtml(tool.name)}</h4>
            <p>${this.escapeHtml(tool.category)}</p>
          </div>
        `).join('')}
      </div>
    `;
  }

  getFilteredTools() {
    if (!this.toolsData?.categories) return [];
    let tools = [];
    const categories = this.state.selectedCategory === 'all'
      ? this.toolsData.categories
      : this.toolsData.categories.filter(c => c.id === this.state.selectedCategory);
    categories.forEach(cat => {
      cat.tools.forEach(toolName => {
        if (!this.state.searchQuery || toolName.toLowerCase().includes(this.state.searchQuery.toLowerCase())) {
          tools.push({ name: toolName, category: cat.name });
        }
      });
    });
    return tools;
  }

  filterCategory(categoryId) {
    this.state.selectedCategory = categoryId;
    this.render();
    this.attachEventListeners();
  }

  viewTool(toolName) {
    if (typeof window.toolLauncher !== 'undefined') {
      window.toolLauncher.launchTool(toolName, { name: toolName });
    }
  }

  attachEventListeners() {
    const searchInput = document.getElementById('tool-search');
    if (searchInput) {
      searchInput.addEventListener('input', (e) => {
        this.state.searchQuery = e.target.value;
        this.render();
        this.attachEventListeners();
      });
    }
  }

  escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
}

let dashboard;
function initDashboard() {
  dashboard = new MarketingToolsDashboard();
  dashboard.init();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initDashboard);
} else {
  initDashboard();
}
