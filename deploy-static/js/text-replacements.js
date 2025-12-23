/**
 * MarketingTool.pro Text Replacements
 * Production-grade replacement map for 425 tools
 */

window.TEXT_REPLACEMENTS = {
  // Core Branding
  Taskade: "MarketingTool.pro",
  "taskade.com": "marketingtool.pro",
  taskade: "marketingtool.pro",
  "Powered by Taskade": "",
  "Powered by": "",

  // Navigation & Structure
  Projects: "Ad Systems",
  Spaces: "Ad Systems",
  Workspaces: "Ad Systems",
  Workspace: "Ad Workspace",
  Automations: "Growth Pipelines",
  Automation: "Growth Pipeline",
  "AI Agents": "Marketing AI Engines",
  Agents: "Marketing AI Engines",
  "AI Agent": "Marketing AI Engine",
  Billing: "Plans",
  Upgrade: "View Pricing",
  "Get Started": "Launch Platform",
  Loading: "Initializing AI Ad Optimization Platform...",
  Templates: "Optimization Blueprints",
  Template: "Optimization Blueprint",
  Settings: "Platform Settings",
  Account: "Profile",
  Organization: "Company",
  Team: "Marketing Team",

  // Actions & Features
  "Create a project": "Launch Ad System",
  "Create project": "Launch Ad System",
  "New project": "New Ad System",
  "Add project": "Add Ad System",
  "New task": "New Campaign",
  "New automation": "New Growth Pipeline",
  "Ask AI": "Run Marketing AI",
  Tasks: "Campaign Executions",
  Task: "Campaign Execution",
  Calendar: "Campaign Calendar",

  // System Messages
  "No projects yet":
    "Launch your first ad system to optimize Google & Facebook campaigns.",
  "Create your first project": "Launch Your First Ad System",
  "Getting started": "Getting Started with AI Ad Optimization",
  Welcome: "Welcome to MarketingTool.pro",

  // Plans & Subscriptions
  Plans: "Pricing",
  Plan: "Subscription",
  "Manage Billing": "Manage Plans",
  Subscription: "Subscription",
  "Free Trial": "Free Trial",

  // Feature Labels
  "Unlimited apps": "425+ Marketing Tools",
  "Unlimited automations": "Unlimited Growth Pipelines",
  "Custom AI Agents": "Custom Marketing AI Engines",
  Runs: "Execution Logs",
};

// Context-specific replacements
window.CONTEXT_REPLACEMENTS = {
  title: {
    Taskade: "MarketingTool.pro",
    One: "AI Ad Optimization Platform",
  },
  url: {
    "taskade.com": "marketingtool.pro",
    "/taskade/": "/",
  },
};

// Export helper function (optimized)
export function replaceText(text, replacements = window.TEXT_REPLACEMENTS) {
  if (!text || typeof text !== "string") return text;

  let result = text;
  Object.entries(replacements).forEach(([find, replace]) => {
    if (find && replace !== undefined) {
      try {
        const regex = new RegExp(
          find.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"),
          "gi"
        );
        result = result.replace(regex, replace);
      } catch (e) {
        console.warn("Replacement error:", find, e);
      }
    }
  });
  return result;
}

// Make available globally
if (typeof window !== "undefined") {
  window.marketingToolTextReplacements = window.TEXT_REPLACEMENTS;
  window.marketingToolReplaceText = replaceText;
}

 * Production-grade replacement map for 425 tools
 */

window.TEXT_REPLACEMENTS = {
  // Core Branding
  Taskade: "MarketingTool.pro",
  "taskade.com": "marketingtool.pro",
  taskade: "marketingtool.pro",
  "Powered by Taskade": "",
  "Powered by": "",

  // Navigation & Structure
  Projects: "Ad Systems",
  Spaces: "Ad Systems",
  Workspaces: "Ad Systems",
  Workspace: "Ad Workspace",
  Automations: "Growth Pipelines",
  Automation: "Growth Pipeline",
  "AI Agents": "Marketing AI Engines",
  Agents: "Marketing AI Engines",
  "AI Agent": "Marketing AI Engine",
  Billing: "Plans",
  Upgrade: "View Pricing",
  "Get Started": "Launch Platform",
  Loading: "Initializing AI Ad Optimization Platform...",
  Templates: "Optimization Blueprints",
  Template: "Optimization Blueprint",
  Settings: "Platform Settings",
  Account: "Profile",
  Organization: "Company",
  Team: "Marketing Team",

  // Actions & Features
  "Create a project": "Launch Ad System",
  "Create project": "Launch Ad System",
  "New project": "New Ad System",
  "Add project": "Add Ad System",
  "New task": "New Campaign",
  "New automation": "New Growth Pipeline",
  "Ask AI": "Run Marketing AI",
  Tasks: "Campaign Executions",
  Task: "Campaign Execution",
  Calendar: "Campaign Calendar",

  // System Messages
  "No projects yet":
    "Launch your first ad system to optimize Google & Facebook campaigns.",
  "Create your first project": "Launch Your First Ad System",
  "Getting started": "Getting Started with AI Ad Optimization",
  Welcome: "Welcome to MarketingTool.pro",

  // Plans & Subscriptions
  Plans: "Pricing",
  Plan: "Subscription",
  "Manage Billing": "Manage Plans",
  Subscription: "Subscription",
  "Free Trial": "Free Trial",

  // Feature Labels
  "Unlimited apps": "425+ Marketing Tools",
  "Unlimited automations": "Unlimited Growth Pipelines",
  "Custom AI Agents": "Custom Marketing AI Engines",
  Runs: "Execution Logs",
};

// Context-specific replacements
window.CONTEXT_REPLACEMENTS = {
  title: {
    Taskade: "MarketingTool.pro",
    One: "AI Ad Optimization Platform",
  },
  url: {
    "taskade.com": "marketingtool.pro",
    "/taskade/": "/",
  },
};

// Export helper function (optimized)
export function replaceText(text, replacements = window.TEXT_REPLACEMENTS) {
  if (!text || typeof text !== "string") return text;

  let result = text;
  Object.entries(replacements).forEach(([find, replace]) => {
    if (find && replace !== undefined) {
      try {
        const regex = new RegExp(
          find.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"),
          "gi"
        );
        result = result.replace(regex, replace);
      } catch (e) {
        console.warn("Replacement error:", find, e);
      }
    }
  });
  return result;
}

// Make available globally
if (typeof window !== "undefined") {
  window.marketingToolTextReplacements = window.TEXT_REPLACEMENTS;
  window.marketingToolReplaceText = replaceText;
}
