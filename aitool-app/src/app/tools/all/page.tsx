import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { 
  Grid3x3, Search, Filter, ArrowRight, Zap, Target, Sparkles, BarChart3, 
  TrendingUp, Brain, Layers, Workflow, Activity, Wand2, RotateCcw, 
  AlertTriangle, Eye, Rocket, Settings, Image, MessageSquare, 
  ShoppingCart, Instagram, Facebook, DollarSign, TrendingDown,
  CheckCircle, Star, Users, Shield, Clock
} from 'lucide-react';

export default function AllToolsPage() {
  // Core Capabilities (7 tools)
  const coreCapabilities = [
    { name: 'Ads Launcher', icon: Rocket, href: '/tools/ads-launcher', category: 'Core Capabilities' },
    { name: 'Ads Rotation Agent', icon: RotateCcw, href: '/tools/ads-rotation-agent', category: 'Core Capabilities' },
    { name: 'Creative Refresh Agent', icon: Sparkles, href: '/tools/creative-refresh-agent', category: 'Core Capabilities' },
    { name: 'AI Ads Generator', icon: Wand2, href: '/tools/ai-ads-generator', category: 'Core Capabilities' },
    { name: 'Ad Fatigue Detector', icon: AlertTriangle, href: '/tools/ad-fatigue-detector', category: 'Core Capabilities' },
    { name: 'Ad Analyzer', icon: Eye, href: '/tools/ad-analyzer', category: 'Core Capabilities' },
    { name: 'Automated Ad Launch Tool', icon: Zap, href: '/tools/automated-ad-launch', category: 'Core Capabilities' },
  ];

  // Ad Management Tools (50+ tools)
  const adManagementTools = [
    { name: 'Campaign Manager', icon: Settings, href: '/tools/campaign-manager' },
    { name: 'Ad Set Optimizer', icon: Target, href: '/tools/ad-set-optimizer' },
    { name: 'Budget Allocator', icon: DollarSign, href: '/tools/budget-allocator' },
    { name: 'Bid Optimizer', icon: TrendingUp, href: '/tools/bid-optimizer' },
    { name: 'Creative Library', icon: Image, href: '/tools/creative-library' },
    { name: 'Ad Performance Tracker', icon: BarChart3, href: '/tools/ad-performance-tracker' },
    { name: 'A/B Test Manager', icon: CheckCircle, href: '/tools/ab-test-manager' },
    { name: 'Ad Scheduling Tool', icon: Clock, href: '/tools/ad-scheduling' },
    { name: 'Audience Builder', icon: Users, href: '/tools/audience-builder' },
    { name: 'Placement Optimizer', icon: Target, href: '/tools/placement-optimizer' },
    // Add 40+ more ad management tools
    ...Array.from({ length: 40 }, (_, i) => ({
      name: `Ad Management Tool ${i + 11}`,
      icon: Settings,
      href: `/tools/ad-management-${i + 11}`
    }))
  ];

  // AI Tools for Advertising (75+ tools)
  const aiTools = [
    { name: 'AI Creative Director', icon: Brain, href: '/tools/ai-creative-director' },
    { name: 'AI Performance Marketer', icon: TrendingUp, href: '/tools/ai-performance-marketer' },
    { name: 'AI Campaign Manager', icon: Settings, href: '/tools/ai-campaign-manager' },
    { name: 'Autonomous Marketing Manager', icon: Zap, href: '/tools/autonomous-marketing-manager' },
    { name: 'AI Paid Social Manager', icon: MessageSquare, href: '/tools/ai-paid-social-manager' },
    { name: 'Meta AI Comment Responder', icon: MessageSquare, href: '/tools/meta-ai-comment-responder' },
    { name: 'Meta AI Comment Manager', icon: MessageSquare, href: '/tools/meta-ai-comment-manager' },
    { name: 'Marketing AI Agents', icon: Brain, href: '/tools/marketing-ai-agents' },
    { name: 'AI Media Buyer', icon: ShoppingCart, href: '/tools/ai-media-buyer' },
    { name: 'Creative Intelligence Platform', icon: Sparkles, href: '/tools/creative-intelligence-platform' },
    // Add 65+ more AI tools
    ...Array.from({ length: 65 }, (_, i) => ({
      name: `AI Tool ${i + 11}`,
      icon: Brain,
      href: `/tools/ai-tool-${i + 11}`
    }))
  ];

  // Facebook Ads Tools (60+ tools)
  const facebookAdsTools = [
    { name: 'Facebook Ads Orchestrator', icon: Facebook, href: '/tools/facebook-ads-orchestrator' },
    { name: 'Facebook Performance Dashboard', icon: BarChart3, href: '/tools/facebook-performance-dashboard' },
    { name: 'Facebook Marketing Tools', icon: Facebook, href: '/tools/facebook-marketing-tools' },
    { name: 'Facebook Campaign Optimizer', icon: Target, href: '/tools/facebook-campaign-optimizer' },
    { name: 'Facebook Creative Analyzer', icon: Eye, href: '/tools/facebook-creative-analyzer' },
    // Add 55+ more Facebook tools
    ...Array.from({ length: 55 }, (_, i) => ({
      name: `Facebook Tool ${i + 6}`,
      icon: Facebook,
      href: `/tools/facebook-tool-${i + 6}`
    }))
  ];

  // Instagram Tools (40+ tools)
  const instagramTools = [
    { name: 'Instagram Marketing Platform', icon: Instagram, href: '/tools/instagram-marketing-platform' },
    { name: 'Instagram AI Automation', icon: Zap, href: '/tools/instagram-ai-automation' },
    { name: 'Instagram Management Tools', icon: Settings, href: '/tools/instagram-management-tools' },
    { name: 'Instagram Ad Optimizer', icon: Target, href: '/tools/instagram-ad-optimizer' },
    // Add 36+ more Instagram tools
    ...Array.from({ length: 36 }, (_, i) => ({
      name: `Instagram Tool ${i + 5}`,
      icon: Instagram,
      href: `/tools/instagram-tool-${i + 5}`
    }))
  ];

  // Performance Intelligence Tools (50+ tools)
  const performanceTools = [
    { name: 'ROAS Prediction Platform', icon: TrendingUp, href: '/tools/roas-prediction-platform' },
    { name: 'Campaign Optimization Engine', icon: Zap, href: '/tools/campaign-optimization-engine' },
    { name: 'Real-Time Meta Ads Optimizer', icon: Target, href: '/tools/real-time-meta-ads-optimizer' },
    { name: 'Ad Intelligence Tools', icon: Eye, href: '/tools/ad-intelligence-tools' },
    { name: 'Competitive Benchmarking AI', icon: BarChart3, href: '/tools/competitive-benchmarking-ai' },
    { name: 'AI Advertising Platform', icon: Brain, href: '/tools/ai-advertising-platform' },
    { name: 'Intelligent Automation Platform', icon: Zap, href: '/tools/intelligent-automation-platform' },
    // Add 43+ more performance tools
    ...Array.from({ length: 43 }, (_, i) => ({
      name: `Performance Tool ${i + 8}`,
      icon: BarChart3,
      href: `/tools/performance-tool-${i + 8}`
    }))
  ];

  // E-commerce & Shopify Tools (30+ tools)
  const ecommerceTools = [
    { name: 'E-commerce Ad Platform', icon: ShoppingCart, href: '/tools/ecommerce-ad-platform' },
    { name: 'Shopify Marketing Tools', icon: ShoppingCart, href: '/tools/shopify-marketing-tools' },
    { name: 'E-commerce Campaign Manager', icon: Settings, href: '/tools/ecommerce-campaign-manager' },
    // Add 27+ more e-commerce tools
    ...Array.from({ length: 27 }, (_, i) => ({
      name: `E-commerce Tool ${i + 4}`,
      icon: ShoppingCart,
      href: `/tools/ecommerce-tool-${i + 4}`
    }))
  ];

  // Analytics & Reporting Tools (40+ tools)
  const analyticsTools = [
    { name: 'Analytics Platform', icon: BarChart3, href: '/tools/analytics-platform' },
    { name: 'Cross-Channel Analytics', icon: BarChart3, href: '/tools/cross-channel-analytics' },
    { name: 'Performance Dashboard', icon: BarChart3, href: '/tools/performance-dashboard' },
    // Add 37+ more analytics tools
    ...Array.from({ length: 37 }, (_, i) => ({
      name: `Analytics Tool ${i + 4}`,
      icon: BarChart3,
      href: `/tools/analytics-tool-${i + 4}`
    }))
  ];

  // Optimization Tools (35+ tools)
  const optimizationTools = [
    { name: 'Optimization Software', icon: Zap, href: '/tools/optimization-software' },
    { name: 'Campaign Optimizer', icon: Target, href: '/tools/campaign-optimizer' },
    { name: 'Budget Optimizer', icon: DollarSign, href: '/tools/budget-optimizer' },
    // Add 32+ more optimization tools
    ...Array.from({ length: 32 }, (_, i) => ({
      name: `Optimization Tool ${i + 4}`,
      icon: Zap,
      href: `/tools/optimization-tool-${i + 4}`
    }))
  ];

  // Marketing Software Tools (38+ tools)
  const marketingSoftware = [
    { name: 'Marketing Software', icon: Settings, href: '/tools/marketing-software' },
    { name: 'Marketing Efficiency Software', icon: TrendingUp, href: '/tools/marketing-efficiency-software' },
    { name: 'Campaign Tools', icon: Target, href: '/tools/campaign-tools' },
    // Add 35+ more marketing software tools
    ...Array.from({ length: 35 }, (_, i) => ({
      name: `Marketing Tool ${i + 4}`,
      icon: Settings,
      href: `/tools/marketing-tool-${i + 4}`
    }))
  ];

  const allTools = [
    ...coreCapabilities,
    ...adManagementTools,
    ...aiTools,
    ...facebookAdsTools,
    ...instagramTools,
    ...performanceTools,
    ...ecommerceTools,
    ...analyticsTools,
    ...optimizationTools,
    ...marketingSoftware,
  ];

  const categories = [
    { name: 'Core Capabilities', tools: coreCapabilities, icon: Zap, color: 'from-purple-500 to-pink-500' },
    { name: 'Ad Management Tools', tools: adManagementTools, icon: Settings, color: 'from-blue-500 to-blue-600' },
    { name: 'AI Tools for Advertising', tools: aiTools, icon: Brain, color: 'from-purple-500 to-purple-600' },
    { name: 'Facebook Ads Tools', tools: facebookAdsTools, icon: Facebook, color: 'from-blue-600 to-blue-700' },
    { name: 'Instagram Tools', tools: instagramTools, icon: Instagram, color: 'from-pink-500 to-purple-500' },
    { name: 'Performance Intelligence', tools: performanceTools, icon: BarChart3, color: 'from-green-500 to-green-600' },
    { name: 'E-commerce & Shopify', tools: ecommerceTools, icon: ShoppingCart, color: 'from-orange-500 to-orange-600' },
    { name: 'Analytics & Reporting', tools: analyticsTools, icon: BarChart3, color: 'from-indigo-500 to-indigo-600' },
    { name: 'Optimization Tools', tools: optimizationTools, icon: Zap, color: 'from-yellow-500 to-yellow-600' },
    { name: 'Marketing Software', tools: marketingSoftware, icon: Settings, color: 'from-gray-500 to-gray-600' },
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        {/* Hero Section */}
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive z-10">
            <div className="text-center mb-8 phone:mb-12">
              <Grid3x3 className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
              <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
                <span className="text-white">All</span>{' '}
                <span className="text-gradient-vibrant">425+ Tools</span>
              </h1>
              <p className="text-lg phone:text-xl text-gray-300 max-w-3xl mx-auto px-4 mb-8">
                Complete suite of AI-powered marketing tools for Facebook Ads, Google Ads, Instagram, and more
              </p>
              <div className="flex items-center justify-center gap-4 mb-8">
                <div className="flex items-center gap-2 px-4 py-2 bg-purple-500/20 rounded-full border border-purple-500/30">
                  <Grid3x3 className="w-5 h-5 text-purple-400" />
                  <span className="text-purple-400 font-semibold">{allTools.length}+ Tools</span>
                </div>
                <div className="flex items-center gap-2 px-4 py-2 bg-purple-500/20 rounded-full border border-purple-500/30">
                  <Layers className="w-5 h-5 text-purple-400" />
                  <span className="text-purple-400 font-semibold">{categories.length} Categories</span>
                </div>
              </div>
              <div className="flex flex-col phone:flex-row gap-4 justify-center">
                <AnimatedButton href="/app/dashboard" size="large">
                  Access All Tools
                </AnimatedButton>
                <Link
                  href="/free-trial"
                  className="px-6 phone:px-8 py-3 phone:py-4 bg-gray-800/80 border border-purple-500/30 rounded-lg text-white font-semibold hover:bg-gray-700/80 transition-all backdrop-blur-sm text-base phone:text-lg card-3d"
                >
                  Start Free Trial
                </Link>
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        {/* Search & Filter */}
        <section className="py-8 bg-gray-900/50 sticky top-16 phone:top-20 z-40 border-b border-purple-500/20">
          <div className="container-responsive">
            <div className="flex flex-col phone:flex-row gap-4 items-center">
              <div className="relative flex-1 w-full">
                <Search className="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                <input
                  type="text"
                  placeholder="Search tools..."
                  className="w-full pl-12 pr-4 py-3 bg-gray-800/50 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors"
                />
              </div>
              <button className="flex items-center gap-2 px-4 py-3 bg-gray-800/50 border border-purple-500/20 rounded-lg text-white hover:border-purple-500/50 transition-colors">
                <Filter className="w-5 h-5" />
                Filter
              </button>
            </div>
          </div>
        </section>

        {/* Categories & Tools */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            {categories.map((category, catIdx) => {
              const CategoryIcon = category.icon;
              return (
                <div key={catIdx} className="mb-12 phone:mb-16">
                  <div className="flex items-center gap-4 mb-6">
                    <div className={`w-12 h-12 bg-gradient-to-br ${category.color} rounded-xl flex items-center justify-center`}>
                      <CategoryIcon className="w-6 h-6 text-white" />
                    </div>
                    <div>
                      <h2 className="text-2xl phone:text-3xl font-bold text-white">{category.name}</h2>
                      <p className="text-gray-400 text-sm">{category.tools.length} tools</p>
                    </div>
                  </div>
                  
                  <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-3 gap-4 phone:gap-6">
                    {category.tools.map((tool, toolIdx) => {
                      const ToolIcon = tool.icon;
                      return (
                        <Link
                          key={toolIdx}
                          href={tool.href}
                          className="card-premium card-3d group p-4 phone:p-6 hover:border-purple-500/50 transition-all"
                        >
                          <div className="flex items-start gap-4">
                            <div className={`w-10 h-10 bg-gradient-to-br ${category.color} rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform`}>
                              <ToolIcon className="w-5 h-5 text-white" />
                            </div>
                            <div className="flex-1 min-w-0">
                              <h3 className="text-lg font-bold text-white group-hover:text-purple-300 transition-colors mb-1">
                                {tool.name}
                              </h3>
                              <div className="flex items-center gap-2 text-purple-400 text-sm mt-2 group-hover:gap-3 transition-all">
                                View Tool <ArrowRight className="w-4 h-4" />
                              </div>
                            </div>
                          </div>
                        </Link>
                      );
                    })}
                  </div>
                </div>
              );
            })}
          </div>
        </section>

        {/* CTA Section */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="card-premium card-3d max-w-4xl mx-auto text-center">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4 text-white">
                Access All 425+ Tools Today
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                Start optimizing your campaigns with our complete suite of AI-powered marketing tools
              </p>
              <div className="flex flex-col phone:flex-row gap-4 justify-center">
                <AnimatedButton href="/app/dashboard" size="large">
                  Go to Dashboard
                </AnimatedButton>
                <AnimatedButton href="/free-trial" variant="secondary" size="large">
                  Start Free Trial
                </AnimatedButton>
              </div>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
