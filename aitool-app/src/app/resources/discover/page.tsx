import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Search, BookOpen, ClipboardCheck, BarChart3, TrendingUp, Wrench, ArrowRight } from 'lucide-react';

export default function DiscoverPage() {
  const resources = [
    { icon: BookOpen, title: 'PPC Research', href: '/resources/ppc-research', desc: 'Latest industry research and insights', color: 'from-purple-500 to-pink-500' },
    { icon: ClipboardCheck, title: 'Account Audit', href: '/resources/audit-report', desc: 'Comprehensive account analysis', color: 'from-blue-500 to-purple-500' },
    { icon: BarChart3, title: 'Free Audit', href: '/resources/free-audit', desc: 'Free performance assessment', color: 'from-green-500 to-emerald-500' },
    { icon: TrendingUp, title: 'Free Reporting', href: '/resources/free-reporting', desc: 'Try our reporting tools', color: 'from-pink-500 to-rose-500' },
    { icon: Wrench, title: 'Free Tools', href: '/resources/tools', desc: 'Access free tool suite', color: 'from-orange-500 to-red-500' },
    { icon: Search, title: 'Blog', href: '/blog', desc: 'Read our latest articles', color: 'from-cyan-500 to-blue-500' },
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center z-10">
            <Search className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">Discover</span>{' '}
              <span className="text-gradient-vibrant">All Resources</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Explore all our resources, tools, and insights to optimize your PPC campaigns
            </p>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-3 gap-6 phone:gap-8">
              {resources.map((resource, idx) => {
                const Icon = resource.icon;
                return (
                  <Link
                    key={idx}
                    href={resource.href}
                    className="card-premium card-3d group"
                  >
                    <div className={`w-14 h-14 bg-gradient-to-br ${resource.color} rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform animate-float-3d`}>
                      <Icon className="w-7 h-7 text-white" />
                    </div>
                    <h3 className="text-xl phone:text-2xl font-bold mb-2 text-white group-hover:text-purple-300 transition-colors">{resource.title}</h3>
                    <p className="text-gray-400 text-sm phone:text-base mb-4">{resource.desc}</p>
                    <div className="flex items-center gap-2 text-purple-400 font-medium text-sm group-hover:gap-3 transition-all">
                      Explore <ArrowRight className="w-4 h-4" />
                    </div>
                  </Link>
                );
              })}
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center">
            <div className="card-premium card-3d max-w-3xl mx-auto px-6 phone:px-8 py-12 phone:py-16">
              <h2 className="text-3xl phone:text-4xl font-bold mb-4 phone:mb-6 text-white">
                Ready to Get Started?
              </h2>
              <p className="text-lg phone:text-xl text-gray-300 mb-8 phone:mb-10">
                Access all resources and tools with a free trial
              </p>
              <AnimatedButton href="/free-trial" size="large">
                Start Free Trial
              </AnimatedButton>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
