/**
 * Tool Launcher - Like Madgicx.com
 * Opens tools in dedicated views/apps
 */

class ToolLauncher {
  constructor() {
    this.currentTool = null;
    this.toolHistory = [];
  }

  /**
   * Launch a tool - Like Madgicx.com opens tools in app view
   */
  launchTool(toolName, toolData) {
    console.log('🚀 Launching tool:', toolName);
    
    // Add to history
    this.toolHistory.push({
      name: toolName,
      timestamp: Date.now()
    });
    
    // Store current tool
    this.currentTool = {
      name: toolName,
      data: toolData,
      category: toolData?.category || 'General'
    };
    
    // Show tool app view
    this.showToolApp(toolName, toolData);
  }

  /**
   * Show tool app view - Full screen tool interface
   */
  showToolApp(toolName, toolData) {
    // Create tool app container
    const toolApp = document.createElement('div');
    toolApp.id = 'tool-app-container';
    toolApp.className = 'tool-app-container';
    toolApp.innerHTML = this.renderToolApp(toolName, toolData);
    
    // Add to body
    document.body.appendChild(toolApp);
    
    // Animate in
    setTimeout(() => {
      toolApp.classList.add('active');
    }, 10);
    
    // Add close handler
    const closeBtn = toolApp.querySelector('.tool-app-close');
    if (closeBtn) {
      closeBtn.addEventListener('click', () => this.closeToolApp());
    }
    
    // ESC key to close
    const escHandler = (e) => {
      if (e.key === 'Escape') {
        this.closeToolApp();
        document.removeEventListener('keydown', escHandler);
      }
    };
    document.addEventListener('keydown', escHandler);
  }

  /**
   * Render tool app interface
   */
  renderToolApp(toolName, toolData) {
    const category = toolData?.category || 'General';
    const icon = this.getToolIcon(category);
    
    return `
      <div class="tool-app-overlay"></div>
      <div class="tool-app-content">
        <div class="tool-app-header">
          <div class="tool-app-header-left">
            <div class="tool-app-icon">${icon}</div>
            <div class="tool-app-title-group">
              <h2 class="tool-app-title">${this.escapeHtml(toolName)}</h2>
              <p class="tool-app-category">${this.escapeHtml(category)}</p>
            </div>
          </div>
          <button class="tool-app-close" aria-label="Close tool">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </div>
        
        <div class="tool-app-body">
          <div class="tool-app-loading">
            <div class="loading-spinner"></div>
            <p>Loading ${this.escapeHtml(toolName)}...</p>
          </div>
          
          <div class="tool-app-iframe-container" style="display: none;">
            <iframe 
              id="tool-iframe"
              src="${this.getToolUrl(toolName, toolData)}"
              frameborder="0"
              allowfullscreen
              class="tool-iframe"
            ></iframe>
          </div>
          
          <div class="tool-app-placeholder" style="display: none;">
            <div class="tool-app-placeholder-content">
              <div class="tool-app-placeholder-icon">${icon}</div>
              <h3>${this.escapeHtml(toolName)}</h3>
              <p>This tool is ready to use. Integration coming soon.</p>
              <div class="tool-app-actions">
                <button class="btn-primary" onclick="toolLauncher.openToolSettings('${this.escapeHtml(toolName)}')">
                  Configure Tool
                </button>
                <button class="btn-outline" onclick="toolLauncher.closeToolApp()">
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  /**
   * Get tool URL - Determines where to load tool from
   */
  getToolUrl(toolName, toolData) {
    // Check if tool has custom URL
    if (toolData?.url) {
      return toolData.url;
    }
    
    // Check if tool exists as standalone app
    const toolSlug = toolName.toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-|-$/g, '');
    
    // Try different URL patterns
    const possibleUrls = [
      `/tools/${toolSlug}/`,
      `/app/${toolSlug}/`,
      `/tools/${toolSlug}/index.html`,
      `#tool-${toolSlug}`
    ];
    
    // For now, return placeholder
    return 'about:blank';
  }

  /**
   * Close tool app
   */
  closeToolApp() {
    const toolApp = document.getElementById('tool-app-container');
    if (toolApp) {
      toolApp.classList.remove('active');
      setTimeout(() => {
        toolApp.remove();
      }, 300);
    }
    this.currentTool = null;
  }

  /**
   * Open tool settings
   */
  openToolSettings(toolName) {
    console.log('⚙️ Opening settings for:', toolName);
    // Show settings modal
    this.showToast(`Settings for ${toolName} - Coming soon`);
  }

  /**
   * Get tool icon by category
   */
  getToolIcon(category) {
    const icons = {
      'Advertising & PPC': '🎯',
      'SEO': '🔍',
      'Content Creation': '✍️',
      'Analytics': '📊',
      'Social Media': '📱',
      'Email Marketing': '📧',
      'Automation': '⚙️',
      'Design': '🎨',
      'Development': '💻',
      'General': '🛠️'
    };
    return icons[category] || '🛠️';
  }

  /**
   * Show toast notification
   */
  showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'tool-toast';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
      toast.classList.add('show');
    }, 10);
    
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
}

// Global tool launcher instance
window.toolLauncher = new ToolLauncher();
