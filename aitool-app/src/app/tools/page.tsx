import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { HelpCircle, ArrowRight, Shield, Zap, BarChart3, Target, TrendingUp, Sparkles, Lock, Layers, Workflow, Brain, Activity, CreditCard, Grid3x3, CheckCircle } from 'lucide-react';

export default function ToolsPage() {
  const tools = [
    {
      category: 'Google Ads',
      subcategory: 'Performance & Intent',
      icon: Target,
      features: [
        'Bidding intelligence',
        'Search intent analysis',
        'Budget allocation',
      ],
      href: 'https://app.adnexai.app/google-ads',
    },
    {
      category: 'Facebook Ads',
      subcategory: 'Creative & Scaling',
      icon: Sparkles,
      features: [
        'Creative scoring',
        'Hook analysis',
        'Fatigue detection',
      ],
      href: 'https://app.adnexai.app/facebook-ads',
    },
    {
      category: 'Analytics & Attribution',
      icon: BarChart3,
      features: [
        'Cross-channel insights',
        'Performance decay signals',
        'ROI prediction',
      ],
      href: 'https://app.adnexai.app/analytics',
    },
    {
      category: 'Automation & Forecasting',
      icon: Zap,
      features: [
        'Budget rules',
        'Creative rotation',
        'Campaign lifecycle logic',
      ],
      href: 'https://app.adnexai.app/automation',
    },
    {
      category: 'AI Strategy & Forecasting',
      icon: TrendingUp,
      features: [
        'Spend forecasting',
        'Outcome simulation',
        'Growth recommendations',
      ],
      href: 'https://app.adnexai.app/ai-strategy',
    },
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center z-10">
            <HelpCircle className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">Help Center &</span>{' '}
              <span className="text-gradient-vibrant">Tools</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-4 phone:mb-6">
              Access your dashboard and powerful AI-powered tools to optimize your PPC campaigns
            </p>
            <div className="flex items-center justify-center gap-4 mb-8 phone:mb-12">
              <Link
                href="/tools/all"
                className="flex items-center gap-2 px-4 py-2 bg-purple-500/20 rounded-full border border-purple-500/30 hover:border-purple-500/50 transition-all"
              >
                <Grid3x3 className="w-5 h-5 text-purple-400" />
                <span className="text-purple-400 font-semibold text-sm phone:text-base">
                  View All 425+ Tools
                </span>
              </Link>
            </div>
            <div className="flex flex-col phone:flex-row gap-4 justify-center">
              <AnimatedButton href="https://app.adnexai.app" size="large" target="_blank">
                Access Dashboard
              </AnimatedButton>
              <Link
                href="/free-trial"
                className="px-6 phone:px-8 py-3 phone:py-4 bg-gray-800/80 border border-purple-500/30 rounded-lg text-white font-semibold hover:bg-gray-700/80 transition-all backdrop-blur-sm text-base phone:text-lg card-3d"
              >
                Start Free Trial
              </Link>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        {/* Tools Grid */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4">
                Available <span className="text-gradient-vibrant">Tools</span>
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 max-w-2xl mx-auto px-4">
                Connect your accounts and start optimizing with our AI-powered tools
              </p>
            </div>

            <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-3 gap-6 phone:gap-8 mb-12 phone:mb-16">
              {tools.map((tool, idx) => {
                const Icon = tool.icon;
                return (
                  <div key={idx} className="card-premium card-3d group">
                    <div className="w-14 phone:w-16 h-14 phone:h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-4 phone:mb-6 group-hover:scale-110 transition-transform animate-float-3d">
                      <Icon className="w-7 phone:w-8 h-7 phone:h-8 text-white" />
                    </div>
                    
                    <div className="mb-2">
                      <h3 className="text-xl phone:text-2xl font-bold text-white group-hover:text-purple-300 transition-colors">
                        {tool.category}
                      </h3>
                      {tool.subcategory && (
                        <p className="text-sm text-purple-300 mt-1">{tool.subcategory}</p>
                      )}
                    </div>
                    
                    <ul className="space-y-2 mb-6">
                      {tool.features.map((feature, fidx) => (
                        <li key={fidx} className="flex items-start gap-2 text-sm text-gray-300">
                          <span className="text-purple-400 mt-0.5">•</span>
                          <span>{feature}</span>
                        </li>
                      ))}
                    </ul>
                    
                    <a
                      href={tool.href}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="inline-flex items-center gap-2 text-purple-400 font-medium text-sm group-hover:gap-3 transition-all"
                    >
                      Access Tool <ArrowRight className="w-4 h-4" />
                    </a>
                  </div>
                );
              })}
            </div>

            {/* Connect App Section */}
            <div className="card-premium card-3d max-w-3xl mx-auto">
              <div className="text-center mb-8">
                <Lock className="w-16 h-16 text-purple-400 mx-auto mb-4 animate-float-3d" />
                <h3 className="text-2xl phone:text-3xl font-bold mb-4 text-white">
                  Connect Your Apps
                </h3>
                <p className="text-gray-400">
                  First login creates your account. Then connect Google Ads, Facebook Ads, and more.
                </p>
              </div>
              
              <div className="grid grid-cols-1 phone:grid-cols-2 gap-4 mb-8">
                <div className="p-4 bg-gray-800/50 rounded-lg">
                  <div className="flex items-center gap-3 mb-2">
                    <div className="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                      <span className="text-white font-bold">G</span>
                    </div>
                    <span className="text-white font-semibold">Google Ads</span>
                  </div>
                  <p className="text-gray-400 text-sm">Connect via Google OAuth</p>
                </div>
                
                <div className="p-4 bg-gray-800/50 rounded-lg">
                  <div className="flex items-center gap-3 mb-2">
                    <div className="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                      <span className="text-white font-bold">f</span>
                    </div>
                    <span className="text-white font-semibold">Facebook Ads</span>
                  </div>
                  <p className="text-gray-400 text-sm">Connect via Meta OAuth</p>
                </div>
              </div>

              <div className="text-center">
                <AnimatedButton href="https://app.adnexai.app" size="large" target="_blank">
                  Go to Dashboard
                </AnimatedButton>
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Systems Model Section */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12">
              <Grid3x3 className="w-16 h-16 text-purple-400 mx-auto mb-4 animate-float-3d" />
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4">
                <span className="text-white">Systems</span>{' '}
                <span className="text-gradient-vibrant">Model</span>
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 max-w-3xl mx-auto px-4">
                425+ integrated tools organized into powerful systems for complete marketing automation
              </p>
            </div>

            <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-4 gap-6 phone:gap-8 mb-12">
              {[
                {
                  icon: Layers,
                  title: 'Ad Systems',
                  desc: 'Complete ad management across Google Ads, Facebook Ads, and more',
                  color: 'from-blue-500 to-blue-600',
                },
                {
                  icon: Workflow,
                  title: 'Growth Pipelines',
                  desc: 'Automated workflows for scaling campaigns and optimizing performance',
                  color: 'from-purple-500 to-purple-600',
                },
                {
                  icon: Brain,
                  title: 'Marketing AI Engines',
                  desc: 'AI-powered intelligence for creative, bidding, and strategy optimization',
                  color: 'from-pink-500 to-pink-600',
                },
                {
                  icon: Activity,
                  title: 'Usage Intelligence',
                  desc: 'Real-time analytics and insights across all your marketing channels',
                  color: 'from-green-500 to-green-600',
                },
              ].map((system, idx) => {
                const Icon = system.icon;
                return (
                  <div key={idx} className="card-premium card-3d group">
                    <div className={`w-14 h-14 bg-gradient-to-br ${system.color} rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform`}>
                      <Icon className="w-7 h-7 text-white" />
                    </div>
                    <h3 className="text-xl font-bold text-white mb-2 group-hover:text-purple-300 transition-colors">
                      {system.title}
                    </h3>
                    <p className="text-gray-400 text-sm">{system.desc}</p>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Plans Section */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12">
              <CreditCard className="w-16 h-16 text-purple-400 mx-auto mb-4 animate-float-3d" />
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4">
                Choose Your <span className="text-gradient-vibrant">Plan</span>
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 max-w-2xl mx-auto px-4">
                Access all 425+ tools with flexible pricing plans
              </p>
            </div>

            <div className="grid grid-cols-1 tablet:grid-cols-3 gap-6 phone:gap-8 max-w-5xl mx-auto">
              {[
                {
                  name: 'Starter',
                  price: '$99',
                  period: '/month',
                  features: ['5 Ad Systems', 'Basic Growth Pipelines', 'Core AI Engines', 'Usage Analytics'],
                  popular: false,
                },
                {
                  name: 'Professional',
                  price: '$299',
                  period: '/month',
                  features: ['All Ad Systems', 'Advanced Pipelines', 'Full AI Engines', 'Usage Intelligence', 'Priority Support'],
                  popular: true,
                },
                {
                  name: 'Enterprise',
                  price: 'Custom',
                  period: '',
                  features: ['425+ Tools', 'Custom Systems', 'Dedicated Support', 'API Access', 'Custom Integrations'],
                  popular: false,
                },
              ].map((plan, idx) => (
                <div
                  key={idx}
                  className={`card-premium card-3d ${plan.popular ? 'ring-2 ring-purple-500' : ''}`}
                >
                  {plan.popular && (
                    <div className="absolute -top-4 left-1/2 transform -translate-x-1/2 px-4 py-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full text-white text-xs font-semibold">
                      Most Popular
                    </div>
                  )}
                  <h3 className="text-2xl font-bold text-white mb-2">{plan.name}</h3>
                  <div className="mb-6">
                    <span className="text-4xl font-bold text-gradient-vibrant">{plan.price}</span>
                    <span className="text-gray-400">{plan.period}</span>
                  </div>
                  <ul className="space-y-3 mb-8">
                    {plan.features.map((feature, fidx) => (
                      <li key={fidx} className="flex items-start gap-2 text-sm text-gray-300">
                        <CheckCircle className="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" />
                        <span>{feature}</span>
                      </li>
                    ))}
                  </ul>
                  <AnimatedButton href="/free-trial" className="w-full">
                    Get Started
                  </AnimatedButton>
                </div>
              ))}
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Getting Started Section */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="card-premium card-3d max-w-4xl mx-auto">
              <h2 className="text-3xl phone:text-4xl font-bold mb-6 text-white text-center">
                Getting Started
              </h2>
              <div className="grid grid-cols-1 tablet:grid-cols-3 gap-6 phone:gap-8">
                {[
                  {
                    step: '01',
                    title: 'Create Account',
                    desc: 'First login automatically creates your account. Use Google sign-in or email.',
                  },
                  {
                    step: '02',
                    title: 'Connect Apps',
                    desc: 'Connect Google Ads and Facebook Ads accounts securely via OAuth.',
                  },
                  {
                    step: '03',
                    title: 'Start Optimizing',
                    desc: 'Access your dashboard and start using AI-powered tools immediately.',
                  },
                ].map((item, idx) => (
                  <div key={idx} className="text-center">
                    <div className="text-5xl phone:text-6xl font-bold text-purple-500/20 mb-4">{item.step}</div>
                    <h3 className="text-xl phone:text-2xl font-bold mb-3 text-white">{item.title}</h3>
                    <p className="text-gray-400 text-sm phone:text-base">{item.desc}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
