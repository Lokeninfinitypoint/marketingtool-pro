import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import { Calendar, Sparkles, CheckCircle, Zap, Shield, TrendingUp, Star } from 'lucide-react';

export default function ChangelogPage() {
  const updates = [
    {
      date: 'December 21, 2025',
      version: 'v2.0.0',
      title: 'Major Platform Update',
      type: 'major',
      changes: [
        { type: 'feature', text: 'New AI-powered ad generation engine with improved templates' },
        { type: 'feature', text: 'Enhanced dashboard with real-time analytics and insights' },
        { type: 'improvement', text: 'Improved campaign optimization algorithms for better ROAS' },
        { type: 'feature', text: 'New tool: Creative Scoring for ad performance prediction' },
        { type: 'improvement', text: 'Performance improvements across the platform (40% faster)' },
        { type: 'security', text: 'Enhanced security with additional encryption layers' },
      ],
      highlights: [
        'Generate 100+ ad variations in seconds',
        'Real-time performance tracking',
        'Improved AI accuracy by 25%',
      ],
    },
    {
      date: 'December 15, 2025',
      version: 'v1.9.0',
      title: 'New Features & Improvements',
      type: 'feature',
      changes: [
        { type: 'feature', text: 'Added Google Ads API integration for unified management' },
        { type: 'feature', text: 'New budget allocation tool with smart recommendations' },
        { type: 'improvement', text: 'Enhanced reporting dashboard with custom date ranges' },
        { type: 'improvement', text: 'Improved mobile responsiveness across all pages' },
        { type: 'fix', text: 'Fixed issue with campaign synchronization delays' },
      ],
      highlights: [
        'Full Google Ads support',
        'Better budget management',
        'Mobile-optimized interface',
      ],
    },
    {
      date: 'December 10, 2025',
      version: 'v1.8.0',
      title: 'Security & Performance Updates',
      type: 'security',
      changes: [
        { type: 'security', text: 'Enhanced security with encrypted credentials at rest' },
        { type: 'improvement', text: 'Improved API response times by 30%' },
        { type: 'feature', text: 'New audit log features for compliance tracking' },
        { type: 'fix', text: 'Bug fixes and stability improvements' },
        { type: 'improvement', text: 'Optimized database queries for faster loading' },
      ],
      highlights: [
        'Bank-level encryption',
        'Faster performance',
        'Complete audit trails',
      ],
    },
    {
      date: 'December 5, 2025',
      version: 'v1.7.0',
      title: 'Automation Enhancements',
      type: 'feature',
      changes: [
        { type: 'feature', text: 'New rules engine for automated campaign management' },
        { type: 'feature', text: 'Smart scaling algorithms for winning campaigns' },
        { type: 'improvement', text: 'Improved bid management accuracy' },
        { type: 'feature', text: 'Email notifications for campaign milestones' },
      ],
      highlights: [
        'Advanced automation',
        'Smarter scaling',
        'Better notifications',
      ],
    },
  ];

  const getChangeIcon = (type: string) => {
    switch (type) {
      case 'feature':
        return <Sparkles className="w-4 h-4 text-purple-400" />;
      case 'improvement':
        return <TrendingUp className="w-4 h-4 text-blue-400" />;
      case 'security':
        return <Shield className="w-4 h-4 text-green-400" />;
      case 'fix':
        return <CheckCircle className="w-4 h-4 text-yellow-400" />;
      default:
        return <Star className="w-4 h-4 text-purple-400" />;
    }
  };

  const getTypeColor = (type: string) => {
    switch (type) {
      case 'major':
        return 'from-purple-600 to-pink-600';
      case 'feature':
        return 'from-blue-600 to-purple-600';
      case 'security':
        return 'from-green-600 to-emerald-600';
      default:
        return 'from-gray-600 to-gray-700';
    }
  };

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
              <span className="text-white">What's New in</span>{' '}
              <span className="text-gradient-vibrant">Our Platform</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Stay updated with the latest features, improvements, and updates. 
              We're constantly improving our platform based on your feedback.
            </p>
            
            <div className="rich-content text-left max-w-3xl mx-auto px-4">
              <p className="text-gray-400 leading-relaxed">
                Our development team works tirelessly to bring you new features, performance 
                improvements, and security enhancements. Check back regularly to see what's new 
                and how we're making AITool.Software even better.
              </p>
            </div>
          </div>
        </section>

        {/* Changelog Entries - Rich Content with 3D */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="space-y-6 phone:space-y-8">
              {updates.map((update, index) => (
                <div
                  key={index}
                  className="card-premium card-3d hover:border-purple-500/50 transition-all"
                >
                  <div className="flex flex-col phone:flex-row phone:items-start phone:justify-between gap-4 phone:gap-6 mb-4 phone:mb-6">
                    <div className="flex-1">
                      <div className="flex flex-wrap items-center gap-3 mb-3 phone:mb-4">
                        <Sparkles className="w-5 phone:w-6 h-5 phone:h-6 text-purple-400" />
                        <span className={`px-3 phone:px-4 py-1.5 phone:py-2 bg-gradient-to-r ${getTypeColor(update.type)} border border-purple-500/30 rounded-full text-xs phone:text-sm font-medium text-white`}>
                          {update.version}
                        </span>
                        <span className="px-3 py-1 bg-gray-800 border border-gray-700 rounded-full text-xs text-gray-400 capitalize">
                          {update.type}
                        </span>
                      </div>
                      <h2 className="text-2xl phone:text-3xl font-bold text-white mb-2 phone:mb-3">{update.title}</h2>
                      {update.highlights && (
                        <div className="flex flex-wrap gap-2 mt-3 phone:mt-4">
                          {update.highlights.map((highlight, idx) => (
                            <span key={idx} className="px-3 py-1 bg-purple-500/20 border border-purple-500/30 rounded-full text-xs phone:text-sm text-purple-300">
                              {highlight}
                            </span>
                          ))}
                        </div>
                      )}
                    </div>
                    <div className="flex items-center gap-2 text-gray-400 text-sm phone:text-base">
                      <Calendar className="w-4 h-4 phone:w-5 phone:h-5" />
                      <span>{update.date}</span>
                    </div>
                  </div>
                  
                  <div className="rich-content">
                    <ul className="space-y-3 phone:space-y-4">
                      {update.changes.map((change, idx) => (
                        <li key={idx} className="flex items-start gap-3 phone:gap-4">
                          <div className="flex-shrink-0 mt-0.5">
                            {getChangeIcon(change.type)}
                          </div>
                          <div className="flex-1">
                            <span className="text-gray-300 text-sm phone:text-base leading-relaxed">{change.text}</span>
                            <span className="ml-2 px-2 py-0.5 bg-gray-800 border border-gray-700 rounded text-xs text-gray-500 capitalize">
                              {change.type}
                            </span>
                          </div>
                        </li>
                      ))}
                    </ul>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Subscribe Section */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="card-premium card-3d max-w-3xl mx-auto px-6 phone:px-8 py-12 phone:py-16 text-center">
              <Zap className="w-16 h-16 text-purple-400 mx-auto mb-6 animate-float-3d" />
              <h2 className="text-3xl phone:text-4xl font-bold mb-4 phone:mb-6 text-white">
                Stay Updated
              </h2>
              <p className="text-lg phone:text-xl text-gray-300 mb-8 phone:mb-10">
                Get notified about new features and updates. Subscribe to our changelog newsletter.
              </p>
              <div className="flex flex-col phone:flex-row gap-4 max-w-md mx-auto">
                <input
                  type="email"
                  placeholder="Enter your email"
                  className="flex-1 px-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 text-sm phone:text-base"
                />
                <AnimatedButton href="/free-trial">
                  Subscribe
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
