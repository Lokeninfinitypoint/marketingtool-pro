import { Navigation, Footer, AnimatedButton, AnimatedScreen } from '@/utils/dynamic-imports';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Sparkles, TrendingUp, Zap, Shield, BarChart3, Target, ArrowRight, CheckCircle, Users, Award, Clock, X, Check } from '@/utils/icons';
import { memo, useMemo } from 'react';

function Home() {
  const features = useMemo(() => [
    {
      icon: Sparkles,
      title: 'AI-Powered Ad Generation',
      description: 'Generate 20-100 structured ads automatically with systematic variation. No free text, only structured templates.',
      details: 'Our AI engine creates multiple ad variations based on proven templates, ensuring consistency and compliance while maximizing performance.',
    },
    {
      icon: TrendingUp,
      title: 'Campaign Optimization',
      description: 'Automated optimization with rules engine. Pause losers, scale winners, maximize ROAS.',
      details: 'Set up intelligent rules that automatically pause underperforming ads and scale winners, saving hours of manual work.',
    },
    {
      icon: Target,
      title: 'Intelligent Bid Management',
      description: 'AI-powered bid optimization for maximum performance and ROAS.',
      details: 'Machine learning algorithms continuously adjust bids based on real-time performance data to maximize your return on ad spend.',
    },
    {
      icon: BarChart3,
      title: 'Real-Time Analytics',
      description: 'Track performance, identify trends, and make data-driven decisions with real-time metrics.',
      details: 'Comprehensive dashboards with real-time data from Meta Ads and Google Ads, all in one unified view.',
    },
    {
      icon: Zap,
      title: 'Automation & Scaling',
      description: 'Automate campaign management and scale winners automatically. Save time and maximize results.',
      details: 'Automatically duplicate winning campaigns, adjust budgets, and scale successful ad sets without manual intervention.',
    },
    {
      icon: Shield,
      title: 'Enterprise Security',
      description: 'Bank-level security with encrypted credentials, audit logs, and manual override options.',
      details: 'Your ad account credentials are encrypted at rest and in transit, with full audit trails and manual override capabilities.',
    },
    {
      icon: Zap,
      title: 'Full API Access',
      description: '425+ tools accessible via API for custom integrations and automation.',
      details: 'Complete API access to all 425+ tools enables seamless integration with your existing workflow and custom automation solutions.',
    },
  ], []);

  const benefits = useMemo(() => [
    'Increase ROAS by up to 3.2x',
    'Save 20+ hours per week on campaign management',
    'Generate 100+ ad variations automatically',
    'Real-time performance monitoring',
    'The Only Platform: Meta + Google Ads',
    '425+ Tools with Full API Access',
    'Enterprise-grade security',
    '60x More Capabilities Than Competitors',
  ], []);

  const stats = useMemo(() => [
    { value: '2000+', label: 'Active Users', icon: Users },
    { value: '3.2x', label: 'Average ROAS', icon: TrendingUp },
    { value: '99.9%', label: 'Uptime', icon: Award },
    { value: '24/7', label: 'Support', icon: Clock },
  ], []);

  const problems = useMemo(() => [
    { text: 'Lower ROAS', icon: X },
    { text: 'Discrepancies between platforms', icon: X },
    { text: 'Struggle to scale', icon: X },
    { text: 'Limited Optimization by Meta AI', icon: X },
  ], []);

  const solutions = useMemo(() => [
    { text: 'Better and clear results', icon: Check },
    { text: 'Alignment between Meta and store', icon: Check },
    { text: 'Growth without guesswork', icon: Check },
    { text: 'Meta\'s brain works for you again', icon: Check },
  ], []);

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        {/* Hero Section - Competitor Style */}
        <section className="relative min-h-[85vh] phone:min-h-[90vh] flex items-center justify-center overflow-hidden">
          {/* Starry background */}
          <div className="absolute inset-0 starry-bg opacity-30"></div>
          
          {/* Gradient overlay */}
          <div className="absolute inset-0 bg-gradient-to-b from-purple-900/30 via-transparent to-gray-900"></div>
          
          {/* Background light effect */}
          <div className="absolute inset-0 bg-light_componentt">
            <div className="bg-light_img absolute inset-0 opacity-10"></div>
          </div>
          
          {/* Animated gradient orbs */}
          <div className="absolute top-1/4 left-1/4 w-64 phone:w-96 h-64 phone:h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse animate-float-3d"></div>
          <div className="absolute bottom-1/4 right-1/4 w-64 phone:w-96 h-64 phone:h-96 bg-pink-500/20 rounded-full blur-3xl animate-pulse delay-1000 animate-float-3d"></div>
          
          <div className="relative container-responsive py-12 phone:py-20 text-center z-10">
            <div className="mb-4 phone:mb-6 animate-fade-in-up">
              <span className="inline-block px-3 phone:px-4 py-1.5 phone:py-2 bg-purple-500/20 border border-purple-500/30 rounded-full text-purple-300 text-xs phone:text-sm font-medium backdrop-blur-sm">
                #1 AI Platform for Meta + Google Ads
              </span>
            </div>
            
            <h1 className="text-4xl phone:text-6xl tablet:text-7xl desktop:text-8xl font-bold mb-4 phone:mb-6 animate-fade-in-up">
              <span className="text-white">The Only AI Platform for</span>{' '}
              <span className="text-gradient-vibrant">Meta + Google Ads</span>
            </h1>
            
            <p className="text-lg phone:text-xl tablet:text-2xl text-gray-300 max-w-4xl mx-auto mb-4 phone:mb-6 px-4 animate-fade-in-up">
              425+ Working Tools with API. 60x More Capabilities Than Competitors. Built for Agencies & Marketers.
            </p>
            
            <div className="flex flex-wrap justify-center gap-3 phone:gap-4 mb-6 phone:mb-8 px-4 animate-fade-in-up">
              <span className="inline-flex items-center gap-2 px-4 py-2 bg-green-500/20 border border-green-500/30 rounded-full text-green-300 text-xs phone:text-sm font-medium">
                <span className="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                425+ Tools with API
              </span>
              <span className="inline-flex items-center gap-2 px-4 py-2 bg-purple-500/20 border border-purple-500/30 rounded-full text-purple-300 text-xs phone:text-sm font-medium">
                Meta + Google Ads
              </span>
              <span className="inline-flex items-center gap-2 px-4 py-2 bg-pink-500/20 border border-pink-500/30 rounded-full text-pink-300 text-xs phone:text-sm font-medium">
                60x More Capabilities
              </span>
            </div>
            
            <div className="flex flex-col phone:flex-row gap-3 phone:gap-4 justify-center px-4 animate-fade-in-up mb-8 phone:mb-12">
              <AnimatedButton href="/free-trial" size="large">
                Try Free ($0 Trial)
              </AnimatedButton>
              <Link
                href="/tools"
                className="px-6 phone:px-8 py-3 phone:py-4 bg-gray-800/80 border border-purple-500/30 rounded-lg text-white font-semibold hover:bg-gray-700/80 transition-all backdrop-blur-sm text-base phone:text-lg card-3d flex items-center justify-center gap-2"
              >
                <Sparkles className="w-5 h-5" />
                Explore 425+ Tools
              </Link>
            </div>

            {/* Three Screenshots - Competitor Style */}
            <div className="relative container-responsive px-4">
              <div className="grid grid-cols-1 tablet:grid-cols-3 gap-4 phone:gap-6">
                <AnimatedScreen className="col-span-1">
                  <div className="card-premium rounded-xl overflow-hidden">
                    <div className="aspect-video bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center">
                      <BarChart3 className="w-16 h-16 text-white opacity-50" />
                    </div>
                  </div>
                </AnimatedScreen>
                
                <AnimatedScreen className="col-span-1">
                  <div className="card-premium rounded-xl overflow-hidden">
                    <div className="aspect-video bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center">
                      <Sparkles className="w-16 h-16 text-white opacity-50" />
                    </div>
                  </div>
                </AnimatedScreen>
                
                <AnimatedScreen className="col-span-1">
                  <div className="card-premium rounded-xl overflow-hidden">
                    <div className="aspect-video bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center">
                      <Zap className="w-16 h-16 text-white opacity-50" />
                    </div>
                  </div>
                </AnimatedScreen>
              </div>
            </div>

            {/* Trust indicators */}
            <div className="mt-12 phone:mt-16 flex flex-wrap justify-center gap-4 phone:gap-8 text-gray-400 text-xs phone:text-sm px-4">
              {stats.map((stat, idx) => {
                const Icon = stat.icon;
                return (
                  <div key={idx} className="flex items-center gap-2">
                    <div className="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <span>{stat.value} {stat.label}</span>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        {/* Trust Logos Section - Competitor Style */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12">
              <div className="flex items-center justify-center gap-4 mb-6">
                <div className="text-sm phone:text-base text-gray-400">Trusted by</div>
                <div className="flex items-center gap-2 px-4 py-2 bg-purple-500/20 border border-purple-500/30 rounded-lg">
                  <span className="text-purple-300 font-semibold">Meta</span>
                  <span className="text-gray-400 text-xs">Business Partner</span>
                </div>
              </div>
              <div className="grid grid-cols-2 phone:grid-cols-4 tablet:grid-cols-6 gap-6 phone:gap-8 opacity-60">
                {[1, 2, 3, 4, 5, 6].map((i) => (
                  <div key={i} className="h-10 phone:h-12 bg-gray-800 rounded-lg flex items-center justify-center">
                    <span className="text-gray-600 text-xs">Logo {i}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* AI Marketer Section - Competitor Style */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12">
              <div className="mb-4">
                <span className="inline-block px-3 phone:px-4 py-1.5 phone:py-2 bg-purple-500/20 border border-purple-500/30 rounded-full text-purple-300 text-xs phone:text-sm font-medium">
                  AI Marketer
                </span>
              </div>
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4 phone:mb-6">
                Your Personal <span className="text-gradient-vibrant">AI Meta Ad Agent</span>
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 max-w-2xl mx-auto px-4">
                Get more from your Meta advertising budget with the first-ever AI ad agent that tells you exactly what to do to maximize results.
              </p>
            </div>

            <div className="flex justify-center mb-8 phone:mb-12">
              <AnimatedButton href="/free-trial" size="large">
                Try AITool.Software Free ($0 Trial)
              </AnimatedButton>
            </div>

            <div className="features-screen_component">
              <AnimatedScreen>
                <div className="card-premium rounded-xl overflow-hidden aspect-video bg-gradient-to-br from-purple-900 to-gray-900 flex items-center justify-center">
                  <div className="text-center">
                    <Sparkles className="w-20 h-20 text-purple-400 mx-auto mb-4 opacity-50" />
                    <p className="text-gray-400 text-sm">AI Marketer Dashboard</p>
                  </div>
                </div>
              </AnimatedScreen>

              <div className="features-screen_features space-y-6 phone:space-y-8">
                {[
                  { title: '✅ Meta Ads Made Simple', desc: 'Overwhelmed with Meta Ads Manager? The AI Marketer tells you exactly what to do and lets you optimize in one click.' },
                  { title: '✅ Meta Ads Made Profitable', desc: 'The AI Marketer audits your account daily to ensure you maximize the bang for your buck. 🤑' },
                  { title: '✅ Meta Ads Made Fun', desc: 'Discover growth opportunities with easily-digestible daily recommendations. 🍕' },
                ].map((feature, idx) => (
                  <div key={idx} className="features-screen_feature">
                    <div className="features-screen_wrap">
                      <h3 className="features-screen_title text-lg phone:text-xl font-bold text-white mb-2">{feature.title}</h3>
                      <p className="text-gray-400 text-sm phone:text-base">{feature.desc}</p>
                    </div>
                    <div className="features-screen_line-wrap">
                      <div className="features-screen_line w-0.5 h-full bg-gradient-to-b from-purple-500 to-transparent"></div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Problem/Solution Section - Competitor Style */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12">
              <div className="mb-4">
                <span className="inline-block px-3 phone:px-4 py-1.5 phone:py-2 bg-purple-500/20 border border-purple-500/30 rounded-full text-purple-300 text-xs phone:text-sm font-medium">
                  Tracking
                </span>
              </div>
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4 phone:mb-6">
                Do You Trust Your <span className="text-gradient-vibrant">Data?</span>
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 max-w-2xl mx-auto px-4">
                Since iOS17, Meta advertisers experience...
              </p>
            </div>

            {/* Problems */}
            <div className="mb-12 phone:mb-16">
              <div className="grid grid-cols-1 phone:grid-cols-2 gap-4 phone:gap-6">
                {problems.map((problem, idx) => {
                  const Icon = problem.icon;
                  return (
                    <div key={idx} className="info_component relative">
                      <Icon className="info_cancel w-6 h-6" />
                      <p className="z-index-1 text-white font-medium">{problem.text}</p>
                      <div className="info_line-wrap">
                        <div className="info_icon w-40 h-0.5 bg-gradient-to-r from-red-500 to-transparent"></div>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>

            {/* Solution Screenshot */}
            <div className="mb-12 phone:mb-16">
              <AnimatedScreen>
                <div className="card-premium rounded-xl overflow-hidden aspect-video bg-gradient-to-br from-purple-900 to-gray-900 flex items-center justify-center">
                  <div className="text-center">
                    <Shield className="w-20 h-20 text-green-400 mx-auto mb-4 opacity-50" />
                    <p className="text-gray-400 text-sm">Cloud Tracking Solution</p>
                  </div>
                </div>
              </AnimatedScreen>
            </div>

            {/* Solutions */}
            <div className="mb-8 phone:mb-12">
              <p className="text-center text-gray-400 mb-8 phone:mb-12 max-w-2xl mx-auto px-4">
                AITool.Software Cloud Tracking sends data from your server straight to Meta with first-party tracking, which means...
              </p>
              <div className="grid grid-cols-1 phone:grid-cols-2 gap-4 phone:gap-6">
                {solutions.map((solution, idx) => {
                  const Icon = solution.icon;
                  return (
                    <div key={idx} className="info_component relative">
                      <Icon className="info_check w-6 h-6" />
                      <p className="z-index-1 text-white font-medium">{solution.text}</p>
                      <div className="info_line-wrap">
                        <div className="info_icon w-40 h-0.5 bg-gradient-to-r from-green-500 to-transparent"></div>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>

            <div className="flex justify-center">
              <AnimatedButton href="/free-trial" size="large">
                Get started
              </AnimatedButton>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Features Section - Premium with 3D */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12 tablet:mb-16">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-3 phone:mb-4">
                Why Choose <span className="text-gradient-vibrant">AITool.Software</span>?
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 max-w-3xl mx-auto px-4 mb-6">
                The most advanced AI-powered PPC platform. <strong className="text-white">The Only Platform for Meta + Google Ads</strong> with 425+ integrated tools and full API access.
              </p>
              <div className="flex flex-wrap justify-center gap-4 mb-8">
                <div className="text-center">
                  <div className="text-4xl phone:text-5xl font-bold text-gradient-vibrant mb-2">425+</div>
                  <div className="text-sm text-gray-400">Working Tools</div>
                </div>
                <div className="text-center">
                  <div className="text-4xl phone:text-5xl font-bold text-gradient-vibrant mb-2">2</div>
                  <div className="text-sm text-gray-400">Platforms (Meta + Google)</div>
                </div>
                <div className="text-center">
                  <div className="text-4xl phone:text-5xl font-bold text-gradient-vibrant mb-2">60x</div>
                  <div className="text-sm text-gray-400">More Than Competitors</div>
                </div>
                <div className="text-center">
                  <div className="text-4xl phone:text-5xl font-bold text-gradient-vibrant mb-2">API</div>
                  <div className="text-sm text-gray-400">Full Access</div>
                </div>
              </div>
            </div>
            
            <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-3 gap-6 phone:gap-8">
              {features.map((feature, index) => {
                const Icon = feature.icon;
                return (
                  <div
                    key={index}
                    className="card-premium card-3d group"
                  >
                    <div className="w-14 phone:w-16 h-14 phone:h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-4 phone:mb-6 group-hover:scale-110 transition-transform shadow-lg shadow-purple-500/30 animate-float-3d">
                      <Icon className="w-7 phone:w-8 h-7 phone:h-8 text-white" />
                    </div>
                    
                    <h3 className="text-lg phone:text-xl font-bold mb-2 phone:mb-3 text-white group-hover:text-purple-300 transition-colors">
                      {feature.title}
                    </h3>
                    
                    <p className="text-gray-400 leading-relaxed mb-3 text-sm phone:text-base">
                      {feature.description}
                    </p>
                    
                    <p className="text-gray-500 text-xs phone:text-sm leading-relaxed">
                      {feature.details}
                    </p>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        {/* CTA Section - Competitor Style */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="card-premium card-3d max-w-4xl mx-auto px-6 phone:px-8 py-12 phone:py-16 text-center">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4 phone:mb-6 text-white">
                The Only AI Platform for Meta + Google Ads
              </h2>
              <p className="text-xl phone:text-2xl text-gray-300 mb-6 max-w-3xl mx-auto">
                425+ Working Tools with API. 60x More Capabilities. Built for Agencies & Marketers.
              </p>
              <p className="text-lg text-gray-400 mb-8 max-w-2xl mx-auto">
                While competitors offer 7 core capabilities, AITool.Software provides 425+ integrated tools in one platform with full API access.
              </p>
              <div className="flex flex-col phone:flex-row gap-4 justify-center mt-8 phone:mt-10">
                <AnimatedButton href="/free-trial" size="large">
                  Try for Free ($0 Trial)
                </AnimatedButton>
                <Link
                  href="/pricing"
                  className="px-8 py-4 phone:px-10 phone:py-5 bg-gray-800 border border-purple-500/30 rounded-lg text-white font-semibold text-lg phone:text-xl hover:bg-gray-700 transition-all card-3d"
                >
                  View Pricing
                </Link>
              </div>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}

export default memo(Home);
