import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Users, Target, Zap, Award, CheckCircle, ArrowRight, Star, Shield, TrendingUp } from 'lucide-react';

export default function AboutPage() {
  const values = [
    {
      icon: Target,
      title: 'Innovation First',
      description: 'We push the boundaries of AI technology to deliver cutting-edge solutions that transform how businesses manage PPC campaigns.',
    },
    {
      icon: Shield,
      title: 'Security & Trust',
      description: 'Enterprise-grade security ensures your data and credentials are protected with bank-level encryption and comprehensive audit trails.',
    },
    {
      icon: TrendingUp,
      title: 'Results Driven',
      description: 'Every feature we build is designed to maximize your ROAS and save you time, backed by data and real-world performance metrics.',
    },
    {
      icon: Users,
      title: 'Customer Focus',
      description: 'Your success is our success. We listen, learn, and continuously improve based on feedback from thousands of users worldwide.',
    },
  ];

  const teamStats = [
    { value: '50+', label: 'Team Members' },
    { value: '2000+', label: 'Active Users' },
    { value: '99.9%', label: 'Uptime' },
    { value: '24/7', label: 'Support' },
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        {/* Hero Section - Rich Content */}
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="absolute top-1/4 left-1/4 w-64 phone:w-96 h-64 phone:h-96 bg-purple-500/10 rounded-full blur-3xl animate-float-3d"></div>
          
          <div className="relative container-responsive text-center z-10">
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">About</span>{' '}
              <span className="text-gradient-vibrant">AITool.Software</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Meet the team behind the AI-powered PPC platform revolutionizing campaign management
            </p>
            
            <div className="rich-content text-left max-w-3xl mx-auto px-4">
              <p className="text-gray-400 leading-relaxed mb-4">
                AITool.Software was founded with a simple mission: to make enterprise-grade PPC 
                management tools accessible to businesses of all sizes. We combine cutting-edge 
                artificial intelligence with deep industry expertise to deliver solutions that 
                transform how you manage your advertising campaigns.
              </p>
              <p className="text-gray-400 leading-relaxed">
                Our platform empowers marketers to generate hundreds of ad variations, optimize 
                campaigns automatically, and scale winners—all while maintaining full control 
                and transparency. With over 2000 active users and 99.9% uptime, we're trusted 
                by businesses worldwide to maximize their return on ad spend.
              </p>
            </div>
          </div>
        </section>

        {/* Mission Section - Rich Content with 3D */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="grid grid-cols-1 desktop:grid-cols-2 gap-8 phone:gap-12 items-center">
              <div className="rich-content">
                <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4 phone:mb-6 text-white">
                  Our Mission
                </h2>
                <p className="text-lg phone:text-xl text-gray-300 mb-4 phone:mb-6 leading-relaxed">
                  At AITool.Software, we're revolutionizing PPC campaign management through 
                  artificial intelligence. Our mission is to help businesses maximize their 
                  ROAS with intelligent automation, smart ad generation, and data-driven optimization.
                </p>
                <p className="text-base phone:text-lg text-gray-400 mb-4 phone:mb-6 leading-relaxed">
                  We believe that every business deserves access to enterprise-grade PPC tools 
                  that can scale their campaigns efficiently and effectively. Whether you're a 
                  small startup or a large enterprise, our platform adapts to your needs.
                </p>
                <p className="text-base phone:text-lg text-gray-400 mb-6 phone:mb-8 leading-relaxed">
                  With our AI-powered platform, you can generate hundreds of ads, optimize 
                  campaigns automatically, and scale winners—all while maintaining full control 
                  and transparency. We're committed to continuous innovation and customer success.
                </p>
                <Link
                  href="/services"
                  className="inline-flex items-center gap-2 px-6 phone:px-8 py-3 phone:py-4 bg-gradient-to-r from-purple-600 via-pink-600 to-purple-600 rounded-lg text-white font-semibold hover:shadow-lg hover:shadow-purple-500/50 transition-all hover:scale-105 card-3d"
                >
                  Explore Our Services <ArrowRight className="w-5 h-5" />
                </Link>
              </div>
              
              <div className="card-premium card-3d">
                <h3 className="text-2xl phone:text-3xl font-bold mb-6 phone:mb-8 text-white">Key Features</h3>
                <div className="space-y-4 phone:space-y-6">
                  {[
                    { icon: Zap, title: 'AI-Powered Optimization', desc: 'Intelligent algorithms that optimize your campaigns automatically' },
                    { icon: Target, title: 'Smart Ad Generation', desc: 'Generate 20-100 ads in seconds with systematic variation' },
                    { icon: Award, title: 'Enterprise Security', desc: 'Bank-level security with encrypted credentials and audit logs' },
                    { icon: TrendingUp, title: 'Real-Time Analytics', desc: 'Comprehensive dashboards with real-time performance metrics' },
                  ].map((item, idx) => {
                    const Icon = item.icon;
                    return (
                      <div key={idx} className="flex items-start gap-4">
                        <div className="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0 animate-float-3d">
                          <Icon className="w-6 h-6 text-white" />
                        </div>
                        <div>
                          <h4 className="font-semibold text-white mb-1 phone:mb-2 text-base phone:text-lg">{item.title}</h4>
                          <p className="text-gray-400 text-sm phone:text-base">{item.desc}</p>
                        </div>
                      </div>
                    );
                  })}
                </div>
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Values Section - Rich Content */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-gray-900/50 to-gray-900 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold text-center mb-4 phone:mb-6">
              Our <span className="text-gradient-vibrant">Values</span>
            </h2>
            <p className="text-lg phone:text-xl text-gray-400 text-center mb-12 phone:mb-16 max-w-2xl mx-auto px-4">
              The principles that guide everything we do
            </p>
            
            <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-4 gap-6 phone:gap-8">
              {values.map((value, idx) => {
                const Icon = value.icon;
                return (
                  <div key={idx} className="card-premium card-3d text-center">
                    <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-4 phone:mb-6 animate-float-3d">
                      <Icon className="w-8 h-8 text-white" />
                    </div>
                    <h3 className="text-xl phone:text-2xl font-bold mb-3 text-white">{value.title}</h3>
                    <p className="text-gray-400 leading-relaxed text-sm phone:text-base">{value.description}</p>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        {/* Stats Section */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="grid grid-cols-2 desktop:grid-cols-4 gap-6 phone:gap-8">
              {teamStats.map((stat, idx) => (
                <div key={idx} className="card-premium card-3d text-center">
                  <div className="text-4xl phone:text-5xl tablet:text-6xl font-bold text-gradient-vibrant mb-2 phone:mb-4">
                    {stat.value}
                  </div>
                  <div className="text-gray-400 text-sm phone:text-base">{stat.label}</div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Pricing Section - Rich Content */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-gray-900/50 to-gray-900 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold text-center mb-4 phone:mb-6">
              Simple, Transparent <span className="text-gradient-vibrant">Pricing</span>
            </h2>
            <p className="text-lg phone:text-xl text-gray-400 text-center mb-12 phone:mb-16 max-w-2xl mx-auto px-4">
              Choose the plan that works best for your business
            </p>
            
            <div className="grid grid-cols-1 phone:grid-cols-2 desktop:grid-cols-4 gap-6 phone:gap-8">
              {[
                { name: 'Free Trial', price: '$0', period: '7 days', features: ['3 tools', 'Limited ads', 'Basic support'], popular: false },
                { name: 'Single Tool', price: '$40', period: 'One-time', features: ['1 tool forever', 'Unlimited use', 'Email support'], popular: false },
                { name: 'Single Tool Lifetime', price: '$99', period: 'Lifetime', features: ['1 tool + updates', 'Unlimited use', 'Priority support'], popular: true },
                { name: 'Full App', price: '$499', period: 'One-time', features: ['All tools', 'Unlimited ads', 'API access', 'Priority support'], popular: false },
              ].map((plan, idx) => (
                <div
                  key={idx}
                  className={`card-premium card-3d relative ${plan.popular ? 'border-purple-500/50 shadow-3d' : ''}`}
                >
                  {plan.popular && (
                    <div className="absolute top-4 right-4 px-3 py-1 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full text-xs phone:text-sm text-white font-medium">
                      Popular
                    </div>
                  )}
                  <h3 className="text-xl phone:text-2xl font-bold mb-2 phone:mb-4 text-white">{plan.name}</h3>
                  <div className="text-3xl phone:text-4xl font-bold mb-2 phone:mb-4 text-gradient-vibrant">
                    {plan.price}
                  </div>
                  <p className="text-gray-400 text-sm phone:text-base mb-4 phone:mb-6">{plan.period}</p>
                  <ul className="space-y-2 phone:space-y-3 mb-6 phone:mb-8">
                    {plan.features.map((feature, fidx) => (
                      <li key={fidx} className="flex items-start gap-2 text-sm phone:text-base text-gray-300">
                        <CheckCircle className="w-5 h-5 text-purple-400 flex-shrink-0 mt-0.5" />
                        <span>{feature}</span>
                      </li>
                    ))}
                  </ul>
                  {plan.popular ? (
                    <AnimatedButton href="/free-trial" className="w-full text-center">
                      Get Started
                    </AnimatedButton>
                  ) : (
                <Link
                  href="/signup"
                  className="block w-full text-center px-4 phone:px-6 py-3 phone:py-4 bg-gray-800 border border-purple-500/30 rounded-lg text-white font-semibold hover:bg-gray-700 transition-all card-3d"
                >
                  Get Started
                </Link>
                  )}
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Sign In / Sign Up Section - 3D */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center">
            <div className="card-premium card-3d max-w-4xl mx-auto px-6 phone:px-8 py-12 phone:py-16">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4 phone:mb-6 text-white">
                Ready to Get Started?
              </h2>
              <p className="text-lg phone:text-xl text-gray-300 mb-8 phone:mb-10">
                Join thousands of businesses using AITool.Software to optimize their PPC campaigns
              </p>
              <div className="flex flex-col phone:flex-row gap-4 justify-center">
                <Link
                  href="/sign-in"
                  className="px-8 py-4 bg-gray-800 border border-purple-500/30 rounded-lg text-white font-semibold hover:bg-gray-700 transition-all card-3d"
                >
                  Sign In
                </Link>
                <AnimatedButton href="/free-trial" size="large">
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
