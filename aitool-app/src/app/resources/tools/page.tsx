import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Wrench, Calculator, TrendingUp, DollarSign, Target, FileText, Sparkles, ArrowRight } from 'lucide-react';

export default function FreeToolsPage() {
  const tools = [
    { icon: Calculator, title: 'PPC Calculator', desc: 'Calculate your PPC metrics and ROI', href: '/tools/ppc-calculator' },
    { icon: TrendingUp, title: 'ROAS Calculator', desc: 'Calculate return on ad spend', href: '/tools/roas-calculator' },
    { icon: DollarSign, title: 'CPA Calculator', desc: 'Calculate cost per acquisition', href: '/tools/cpa-calculator' },
    { icon: Target, title: 'Budget Planner', desc: 'Plan and optimize your ad budget', href: '/tools/budget-planner' },
    { icon: FileText, title: 'Keyword Research Tool', desc: 'Find the best keywords for your campaigns', href: '/tools/keyword-research' },
    { icon: Sparkles, title: 'Ad Copy Generator', desc: 'Generate high-converting ad copy', href: '/tools/ad-copy-generator' },
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center z-10">
            <Wrench className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">Free</span>{' '}
              <span className="text-gradient-vibrant">Tools</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Access our comprehensive suite of free PPC tools to optimize your campaigns
            </p>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-3 gap-6 phone:gap-8">
              {tools.map((tool, idx) => {
                const Icon = tool.icon;
                return (
                  <Link
                    key={idx}
                    href={tool.href}
                    className="card-premium card-3d group"
                  >
                    <div className="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform animate-float-3d">
                      <Icon className="w-6 h-6 text-white" />
                    </div>
                    <h3 className="text-xl phone:text-2xl font-bold mb-2 text-white group-hover:text-purple-300 transition-colors">{tool.title}</h3>
                    <p className="text-gray-400 text-sm phone:text-base mb-4">{tool.desc}</p>
                    <div className="flex items-center gap-2 text-purple-400 font-medium text-sm group-hover:gap-3 transition-all">
                      Use Tool <ArrowRight className="w-4 h-4" />
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
                Need More Advanced Tools?
              </h2>
              <p className="text-lg phone:text-xl text-gray-300 mb-8 phone:mb-10">
                Upgrade to get access to all premium tools and features
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
