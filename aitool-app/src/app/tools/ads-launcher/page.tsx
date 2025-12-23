import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import AnimatedScreen from '@/components/AnimatedScreen';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Rocket, ArrowRight, Zap, Target, BarChart3, CheckCircle, TrendingUp } from 'lucide-react';

export default function AdsLauncherPage() {
  const features = [
    'Launch ads across multiple platforms simultaneously',
    'Automated ad creation and deployment',
    'Real-time performance monitoring',
    'Smart budget allocation',
    'A/B testing automation',
    'Campaign scheduling and optimization',
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
              <Rocket className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
              <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
                <span className="text-white">Ads</span>{' '}
                <span className="text-gradient-vibrant">Launcher</span>
              </h1>
              <p className="text-lg phone:text-xl text-gray-300 max-w-3xl mx-auto px-4 mb-8">
                Launch and deploy ads across Facebook, Instagram, and Google Ads with one click. Automated ad creation, deployment, and optimization.
              </p>
              <div className="flex flex-col phone:flex-row gap-4 justify-center">
                <AnimatedButton href="/app/dashboard" size="large">
                  Launch Ads Now
                </AnimatedButton>
                <Link
                  href="/tools/all"
                  className="px-6 phone:px-8 py-3 phone:py-4 bg-gray-800/80 border border-purple-500/30 rounded-lg text-white font-semibold hover:bg-gray-700/80 transition-all backdrop-blur-sm text-base phone:text-lg card-3d"
                >
                  View All Tools
                </Link>
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        {/* Features Section */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4">
                Powerful <span className="text-gradient-vibrant">Features</span>
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 max-w-2xl mx-auto px-4">
                Everything you need to launch and manage ads efficiently
              </p>
            </div>

            <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-3 gap-6 phone:gap-8 mb-12">
              {features.map((feature, idx) => (
                <div key={idx} className="card-premium card-3d">
                  <CheckCircle className="w-6 h-6 text-green-400 mb-3" />
                  <p className="text-white font-medium">{feature}</p>
                </div>
              ))}
            </div>

            <AnimatedScreen>
              <div className="card-premium rounded-xl overflow-hidden aspect-video bg-gradient-to-br from-purple-900 to-gray-900 flex items-center justify-center">
                <div className="text-center">
                  <Rocket className="w-20 h-20 text-purple-400 mx-auto mb-4 opacity-50" />
                  <p className="text-gray-400 text-sm">Ads Launcher Dashboard</p>
                </div>
              </div>
            </AnimatedScreen>
          </div>
        </section>

        {/* CTA Section */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="card-premium card-3d max-w-4xl mx-auto text-center">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4 text-white">
                Ready to Launch Your Ads?
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                Start launching ads across all platforms with AI-powered optimization
              </p>
              <AnimatedButton href="/app/dashboard" size="large">
                Get Started
              </AnimatedButton>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
