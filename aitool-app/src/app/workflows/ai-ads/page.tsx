import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Wand2, Sparkles, CheckCircle } from 'lucide-react';

export default function AIAdsWorkflowPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive z-10">
            <div className="text-center mb-8 phone:mb-12">
              <Wand2 className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
              <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
                <span className="text-white">AI Ads</span>{' '}
                <span className="text-gradient-vibrant">Workflow</span>
              </h1>
              <p className="text-lg phone:text-xl text-gray-300 max-w-3xl mx-auto px-4 mb-8">
                Generate, optimize, and launch AI-powered ad creatives with automated workflows
              </p>
              <div className="flex flex-col phone:flex-row gap-4 justify-center">
                <AnimatedButton href="/ai-ads" size="large">
                  View AI Ads
                </AnimatedButton>
                <Link href="/app/dashboard" className="px-6 phone:px-8 py-3 phone:py-4 bg-gray-800/80 border border-purple-500/30 rounded-lg text-white font-semibold hover:bg-gray-700/80 transition-all backdrop-blur-sm text-base phone:text-lg card-3d">
                  Access Dashboard
                </Link>
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="max-w-4xl mx-auto">
              <h2 className="text-3xl phone:text-4xl font-bold mb-8 text-white text-center">
                AI Ads Workflow Features
              </h2>
              <div className="grid grid-cols-1 tablet:grid-cols-2 gap-6">
                {[
                  'AI ad generation',
                  'Creative library management',
                  'Automated ad testing',
                  'Performance-based optimization',
                  'Multi-platform deployment',
                  'Creative refresh automation',
                ].map((feature, idx) => (
                  <div key={idx} className="card-premium card-3d flex items-start gap-4">
                    <CheckCircle className="w-6 h-6 text-green-400 flex-shrink-0 mt-0.5" />
                    <p className="text-white font-medium">{feature}</p>
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
