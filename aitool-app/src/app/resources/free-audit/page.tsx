import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import AnimatedScreen from '@/components/AnimatedScreen';
import { BarChart3, CheckCircle, Shield, TrendingUp } from 'lucide-react';

export default function FreeAuditPage() {
  const auditFeatures = [
    'Account structure analysis',
    'Performance metrics review',
    'Optimization opportunities',
    'Budget allocation recommendations',
    'Ad copy and creative analysis',
    'Competitor benchmarking',
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center z-10">
            <BarChart3 className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">Get Your Free</span>{' '}
              <span className="text-gradient-vibrant">PPC Audit</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Free performance assessment and optimization recommendations from our expert team
            </p>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="grid grid-cols-1 desktop:grid-cols-2 gap-8 phone:gap-12 items-center mb-12 phone:mb-16">
              <div className="card-premium card-3d">
                <h2 className="text-3xl phone:text-4xl font-bold mb-6 text-white">What's Included</h2>
                <div className="space-y-4">
                  {auditFeatures.map((item, idx) => (
                    <div key={idx} className="flex items-center gap-3">
                      <CheckCircle className="w-5 h-5 text-purple-400 flex-shrink-0" />
                      <span className="text-gray-300 text-sm phone:text-base">{item}</span>
                    </div>
                  ))}
                </div>
              </div>

              <AnimatedScreen>
                <div className="card-premium rounded-xl overflow-hidden aspect-video bg-gradient-to-br from-purple-900 to-gray-900 flex items-center justify-center">
                  <TrendingUp className="w-20 h-20 text-purple-400 opacity-50" />
                </div>
              </AnimatedScreen>
            </div>

            <div className="card-premium card-3d max-w-2xl mx-auto">
              <h2 className="text-3xl phone:text-4xl font-bold mb-6 text-white text-center">
                Request Your Free Audit
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
                    Ad Account URL (Meta Ads Manager or Google Ads)
                  </label>
                  <input
                    type="url"
                    className="w-full px-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 text-sm phone:text-base"
                    placeholder="https://..."
                  />
                </div>
                <AnimatedButton href="#" className="w-full flex items-center justify-center">
                  Get Free Audit
                </AnimatedButton>
              </form>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
