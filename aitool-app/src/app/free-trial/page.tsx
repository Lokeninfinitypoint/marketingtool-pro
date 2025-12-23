import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import AnimatedScreen from '@/components/AnimatedScreen';
import Link from 'next/link';
import { Sparkles, CheckCircle, ArrowRight, Zap, Shield, BarChart3 } from 'lucide-react';

export default function FreeTrialPage() {
  const features = [
    'Access to 3 tools',
    'Generate up to 50 ads',
    'Connect 1 ad account',
    'Basic reporting',
    'Email support',
    '7 days full access',
  ];

  const benefits = [
    { icon: Zap, title: 'Instant Access', desc: 'Start optimizing immediately' },
    { icon: Shield, title: 'No Credit Card', desc: '100% free trial' },
    { icon: BarChart3, title: 'Full Features', desc: 'Experience all capabilities' },
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="absolute top-1/4 left-1/4 w-64 phone:w-96 h-64 phone:h-96 bg-purple-500/10 rounded-full blur-3xl animate-float-3d"></div>
          
          <div className="relative container-responsive text-center z-10">
            <div className="mb-4 phone:mb-6">
              <Sparkles className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
            </div>
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">Start Your</span>{' '}
              <span className="text-gradient-vibrant">Free Trial</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              7 days free trial. No credit card required. Experience the power of AI-driven PPC optimization.
            </p>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="grid grid-cols-1 desktop:grid-cols-2 gap-8 phone:gap-12 items-center mb-12 phone:mb-16">
              <div className="card-premium card-3d">
                <h2 className="text-3xl phone:text-4xl font-bold mb-6 text-white text-center phone:text-left">
                  What's Included in Free Trial
                </h2>
                <div className="grid grid-cols-1 phone:grid-cols-2 gap-4">
                  {features.map((feature, idx) => (
                    <div key={idx} className="flex items-center gap-3">
                      <CheckCircle className="w-5 h-5 text-purple-400 flex-shrink-0" />
                      <span className="text-gray-300 text-sm phone:text-base">{feature}</span>
                    </div>
                  ))}
                </div>
              </div>

              <AnimatedScreen>
                <div className="card-premium rounded-xl overflow-hidden aspect-video bg-gradient-to-br from-purple-900 to-gray-900 flex items-center justify-center">
                  <div className="text-center">
                    <Sparkles className="w-20 h-20 text-purple-400 mx-auto mb-4 opacity-50" />
                    <p className="text-gray-400 text-sm">Dashboard Preview</p>
                  </div>
                </div>
              </AnimatedScreen>
            </div>

            <div className="grid grid-cols-1 tablet:grid-cols-3 gap-6 phone:gap-8 mb-12 phone:mb-16">
              {benefits.map((benefit, idx) => {
                const Icon = benefit.icon;
                return (
                  <div key={idx} className="card-premium card-3d text-center">
                    <div className="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-4 animate-float-3d">
                      <Icon className="w-6 h-6 text-white" />
                    </div>
                    <h3 className="text-lg font-bold text-white mb-2">{benefit.title}</h3>
                    <p className="text-gray-400 text-sm">{benefit.desc}</p>
                  </div>
                );
              })}
            </div>

            <div className="card-premium card-3d max-w-2xl mx-auto">
              <h2 className="text-3xl phone:text-4xl font-bold mb-6 text-white text-center">
                Get Started Today
              </h2>
              <form className="space-y-6">
                <div>
                  <label className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                    Email Address
                  </label>
                  <input
                    type="email"
                    required
                    className="w-full px-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 text-sm phone:text-base"
                    placeholder="your@email.com"
                  />
                </div>
                <div>
                  <label className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                    Create Password
                  </label>
                  <input
                    type="password"
                    required
                    className="w-full px-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 text-sm phone:text-base"
                    placeholder="••••••••"
                  />
                </div>
                <AnimatedButton href="/app/dashboard" className="w-full flex items-center justify-center gap-2">
                  Start Free Trial <ArrowRight className="w-5 h-5" />
                </AnimatedButton>
              </form>
              
              <div className="mt-6 text-center">
                <p className="text-gray-400 text-sm">
                  Already have an account?{' '}
                  <Link href="/sign-in" className="text-purple-400 hover:text-purple-300 font-medium">
                    Sign in
                  </Link>
                </p>
              </div>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
