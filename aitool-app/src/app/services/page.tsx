import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import AnimatedScreen from '@/components/AnimatedScreen';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Sparkles, TrendingUp, Target, Zap, BarChart3, Shield, CheckCircle, ArrowRight, Star, Check } from 'lucide-react';

export default function ServicesPage() {
  const services = [
    {
      icon: Sparkles,
      title: 'AI-Powered Ad Generation',
      description: 'Generate 20-100 structured ads automatically with systematic variation. No free text, only structured templates.',
      features: ['Structured JSON output', 'Systematic variation', 'Meta & Google Ads support', 'Generate from winners'],
      details: 'Our AI engine analyzes your best-performing ads and generates new variations using proven templates. Each ad is structured, compliant, and optimized for performance.',
      benefits: ['Save 10+ hours per week', 'Generate 100+ ad variations', 'Maintain brand consistency', 'Improve ad performance'],
    },
    {
      icon: TrendingUp,
      title: 'Campaign Optimization',
      description: 'Automated optimization with rules engine. Pause losers, scale winners, maximize ROAS.',
      features: ['Rules-based optimization', 'CPA protection', 'ROAS maximization', 'Budget redistribution'],
      details: 'Set up intelligent rules that automatically pause underperforming campaigns and scale winners. Our AI continuously monitors performance and adjusts strategies in real-time.',
      benefits: ['Increase ROAS by 3.2x', 'Reduce wasted ad spend', 'Automate manual tasks', 'Scale successful campaigns'],
    },
    {
      icon: Target,
      title: 'Intelligent Bid Management',
      description: 'AI-powered bid optimization for maximum performance. Optimize bids based on real-time data.',
      features: ['AI bid optimization', 'Real-time adjustments', 'CPA/ROAS targeting', 'Learning phase protection'],
      details: 'Machine learning algorithms continuously analyze performance data and adjust bids to maximize your return on ad spend while protecting your CPA targets.',
      benefits: ['Optimize bids automatically', 'Maximize conversion value', 'Protect CPA targets', 'Reduce manual work'],
    },
    {
      icon: BarChart3,
      title: 'Performance Analytics',
      description: 'Real-time analytics and insights. Track performance, identify trends, make data-driven decisions.',
      features: ['Real-time metrics', 'ROAS tracking', 'Performance decay signals', 'Cross-channel insights'],
      details: 'Comprehensive dashboards provide real-time insights from both Meta Ads and Google Ads. Identify trends, spot opportunities, and make informed decisions.',
      benefits: ['Unified dashboard', 'Real-time insights', 'Performance alerts', 'Data-driven decisions'],
    },
    {
      icon: Zap,
      title: 'Automation & Scaling',
      description: 'Automate campaign management and scale winners automatically. Save time and maximize results.',
      features: ['Automated scaling', 'Creative rotation', 'Campaign lifecycle logic', 'Feedback loop system'],
      details: 'Automatically duplicate winning campaigns, adjust budgets, and scale successful ad sets. Our feedback loop system continuously improves performance.',
      benefits: ['Scale winners automatically', 'Save 20+ hours weekly', 'Increase campaign reach', 'Improve efficiency'],
    },
    {
      icon: Shield,
      title: 'Enterprise Security',
      description: 'Bank-level security with encrypted credentials, audit logs, and manual override options.',
      features: ['Encrypted credentials', 'Audit logs', 'Manual override', 'RBAC support'],
      details: 'Your ad account credentials are encrypted at rest and in transit. Full audit trails and role-based access control ensure complete security.',
      benefits: ['Bank-level encryption', 'Complete audit trails', 'Role-based access', 'Manual override options'],
    },
  ];

  const testimonials = [
    {
      name: 'Sarah Johnson',
      role: 'Marketing Director',
      company: 'TechCorp Inc.',
      quote: 'AITool.Software transformed our PPC campaigns. We saw a 3.2x increase in ROAS within the first month.',
      rating: 5,
    },
    {
      name: 'Michael Chen',
      role: 'E-commerce Manager',
      company: 'ShopSmart',
      quote: 'The automation features save us 20+ hours per week. The AI-generated ads perform better than our manual ones.',
      rating: 5,
    },
    {
      name: 'Emily Rodriguez',
      role: 'Growth Marketer',
      company: 'StartupXYZ',
      quote: 'Best investment we made. The platform pays for itself with the increased performance and time savings.',
      rating: 5,
    },
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        {/* Hero Section - Competitor Style */}
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="absolute top-1/4 left-1/4 w-64 phone:w-96 h-64 phone:h-96 bg-purple-500/10 rounded-full blur-3xl animate-float-3d"></div>
          
          <div className="relative container-responsive text-center z-10">
            <div className="mb-4 phone:mb-6">
              <span className="inline-block px-3 phone:px-4 py-1.5 phone:py-2 bg-purple-500/20 border border-purple-500/30 rounded-full text-purple-300 text-xs phone:text-sm font-medium">
                Services
              </span>
            </div>
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">Our</span>{' '}
              <span className="text-gradient-vibrant">Services</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Complete AI-powered PPC platform for Meta Ads and Google Ads management. 
              Transform your campaigns with intelligent automation and optimization.
            </p>
            
            <div className="flex flex-col phone:flex-row gap-4 justify-center">
              <AnimatedButton href="/free-trial" size="large">
                Start Free Trial
              </AnimatedButton>
              <Link
                href="/contact"
                className="px-6 phone:px-8 py-3 phone:py-4 bg-gray-800/80 border border-purple-500/30 rounded-lg text-white font-semibold hover:bg-gray-700/80 transition-all backdrop-blur-sm text-base phone:text-lg card-3d"
              >
                Contact Sales
              </Link>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        {/* Services Grid - Rich Content with 3D */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12 tablet:mb-16">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4">
                Complete <span className="text-gradient-vibrant">Solution</span>
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 max-w-2xl mx-auto px-4">
                Everything you need to manage and optimize your PPC campaigns
              </p>
            </div>
            
            <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-3 gap-6 phone:gap-8">
              {services.map((service, index) => {
                const Icon = service.icon;
                return (
                  <div
                    key={index}
                    className="card-premium card-3d group"
                  >
                    <div className="w-14 phone:w-16 h-14 phone:h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-4 phone:mb-6 group-hover:scale-110 transition-transform shadow-lg shadow-purple-500/30 animate-float-3d">
                      <Icon className="w-7 phone:w-8 h-7 phone:h-8 text-white" />
                    </div>
                    
                    <h3 className="text-xl phone:text-2xl font-bold mb-2 phone:mb-3 text-white group-hover:text-purple-300 transition-colors">
                      {service.title}
                    </h3>
                    
                    <p className="text-gray-400 mb-4 phone:mb-6 text-sm phone:text-base">
                      {service.description}
                    </p>
                    
                    <div className="rich-content mb-4 phone:mb-6">
                      <p className="text-gray-500 text-xs phone:text-sm mb-4 leading-relaxed">
                        {service.details}
                      </p>
                      
                      <div className="mb-4">
                        <h4 className="text-sm font-semibold text-purple-300 mb-2">Key Features:</h4>
                        <ul className="space-y-2">
                          {service.features.map((feature, idx) => (
                            <li key={idx} className="flex items-start gap-2 text-xs phone:text-sm text-gray-300">
                              <CheckCircle className="w-4 h-4 text-purple-400 flex-shrink-0 mt-0.5" />
                              <span>{feature}</span>
                            </li>
                          ))}
                        </ul>
                      </div>
                      
                      <div>
                        <h4 className="text-sm font-semibold text-purple-300 mb-2">Benefits:</h4>
                        <ul className="space-y-1.5">
                          {service.benefits.map((benefit, idx) => (
                            <li key={idx} className="flex items-start gap-2 text-xs phone:text-sm text-gray-400">
                              <Star className="w-3 h-3 text-pink-400 flex-shrink-0 mt-1" />
                              <span>{benefit}</span>
                            </li>
                          ))}
                        </ul>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Feature Section with Video - Competitor Style */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12">
              <div className="mb-4">
                <span className="inline-block px-3 phone:px-4 py-1.5 phone:py-2 bg-purple-500/20 border border-purple-500/30 rounded-full text-purple-300 text-xs phone:text-sm font-medium">
                  Ads Manager 2.0
                </span>
              </div>
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4 phone:mb-6">
                Upgrade to the New-Gen <span className="text-gradient-vibrant">Ad Optimization Machine</span>
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 max-w-2xl mx-auto px-4">
                Want to optimize your ads yourself? Do it easily with smart filters, bulk actions, and exclusive AI Bidding capabilities.
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
                    <BarChart3 className="w-20 h-20 text-purple-400 mx-auto mb-4 opacity-50" />
                    <p className="text-gray-400 text-sm">Ads Manager 2.0 Dashboard</p>
                  </div>
                </div>
              </AnimatedScreen>

              <div className="features-screen_features space-y-6 phone:space-y-8">
                {[
                  { title: 'Increase/Decrease budgets in bulk', icon: Check },
                  { title: 'Optimize targeting without going back to the learning phase', icon: Check },
                  { title: 'Easily monitor the latest changes to your ad sets', icon: Check },
                ].map((feature, idx) => {
                  const Icon = feature.icon;
                  return (
                    <div key={idx} className="features-screen_feature">
                      <div className="features-screen_wrap">
                        <div className="flex items-center gap-3 mb-2">
                          <Icon className="w-5 h-5 text-purple-400 flex-shrink-0" />
                          <h3 className="features-screen_title text-lg phone:text-xl font-bold text-white">{feature.title}</h3>
                        </div>
                      </div>
                      <div className="features-screen_line-wrap">
                        <div className="features-screen_line w-0.5 h-full bg-gradient-to-b from-purple-500 to-transparent"></div>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>

            {/* Badge */}
            <div className="mt-8 phone:mt-12 flex justify-center">
              <div className="px-6 py-3 bg-gradient-to-r from-purple-600/20 to-pink-600/20 border border-purple-500/30 rounded-lg">
                <p className="text-purple-300 font-semibold text-sm phone:text-base">
                  AI Bidding - available exclusively on AITool.Software!
                </p>
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Testimonials Section - Rich Content */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-gray-900/50 to-gray-900 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold text-center mb-4 phone:mb-6">
              Trusted by <span className="text-gradient-vibrant">Industry Leaders</span>
            </h2>
            <p className="text-lg phone:text-xl text-gray-400 text-center mb-12 phone:mb-16 max-w-2xl mx-auto px-4">
              See what our customers are saying about AITool.Software
            </p>
            
            <div className="grid grid-cols-1 tablet:grid-cols-3 gap-6 phone:gap-8">
              {testimonials.map((testimonial, idx) => (
                <div key={idx} className="card-premium card-3d">
                  <div className="flex items-center gap-1 mb-4">
                    {[...Array(testimonial.rating)].map((_, i) => (
                      <Star key={i} className="w-5 h-5 text-yellow-400 fill-yellow-400" />
                    ))}
                  </div>
                  <p className="text-gray-300 mb-6 leading-relaxed italic">
                    "{testimonial.quote}"
                  </p>
                  <div>
                    <div className="font-semibold text-white">{testimonial.name}</div>
                    <div className="text-sm text-gray-400">{testimonial.role}, {testimonial.company}</div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* CTA Section - 3D */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center">
            <div className="card-premium card-3d max-w-4xl mx-auto px-6 phone:px-8 py-12 phone:py-16">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4 phone:mb-6 text-white">
                Ready to Transform Your PPC Campaigns?
              </h2>
              <p className="text-lg phone:text-xl text-gray-300 mb-8 phone:mb-10">
                Start your free trial and experience the power of AI-driven optimization
              </p>
              <div className="flex flex-col phone:flex-row gap-4 justify-center">
                <AnimatedButton href="/free-trial" size="large">
                  Start Free Trial
                </AnimatedButton>
                <Link
                  href="/contact"
                  className="px-8 phone:px-10 py-4 phone:py-5 bg-gray-800/80 border border-purple-500/30 rounded-lg text-white font-semibold text-lg phone:text-xl hover:bg-gray-700/80 transition-all backdrop-blur-sm card-3d"
                >
                  Contact Sales
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
