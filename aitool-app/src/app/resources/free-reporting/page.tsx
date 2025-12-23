import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import AnimatedScreen from '@/components/AnimatedScreen';
import { TrendingUp, CheckCircle, BarChart3, Zap } from 'lucide-react';

export default function FreeReportingPage() {
  const reportingFeatures = [
    'Real-time performance metrics',
    'Cross-platform analytics',
    'Automated insights',
    'Customizable dashboards',
    'Export reports',
    'Performance alerts',
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center z-10">
            <TrendingUp className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">Test-Drive Our</span>{' '}
              <span className="text-gradient-vibrant">Free Reporting</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Try our reporting tools and see your campaign performance in real-time
            </p>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="grid grid-cols-1 desktop:grid-cols-2 gap-8 phone:gap-12 items-center mb-12 phone:mb-16">
              <AnimatedScreen>
                <div className="card-premium rounded-xl overflow-hidden aspect-video bg-gradient-to-br from-purple-900 to-gray-900 flex items-center justify-center">
                  <BarChart3 className="w-20 h-20 text-purple-400 opacity-50" />
                </div>
              </AnimatedScreen>
              
              <div className="rich-content">
                <h2 className="text-3xl phone:text-4xl font-bold mb-6 text-white">Free Reporting Features</h2>
                <p className="text-lg text-gray-300 mb-4 leading-relaxed">
                  Experience our powerful reporting dashboard with real-time metrics, performance insights, and automated optimizations. 
                  See exactly how your campaigns are performing across all platforms.
                </p>
                <p className="text-base text-gray-400 mb-6 leading-relaxed">
                  Our reporting tools give you complete visibility into your PPC performance with beautiful, 
                  easy-to-understand visualizations and actionable insights.
                </p>
                <ul className="space-y-3">
                  {reportingFeatures.map((item, idx) => (
                    <li key={idx} className="flex items-start gap-3">
                      <CheckCircle className="w-5 h-5 text-purple-400 flex-shrink-0 mt-0.5" />
                      <span className="text-gray-300">{item}</span>
                    </li>
                  ))}
                </ul>
              </div>
            </div>

            <div className="card-premium card-3d max-w-3xl mx-auto text-center">
              <h3 className="text-2xl phone:text-3xl font-bold mb-6 text-white">Start Your Free Trial</h3>
              <p className="text-gray-300 mb-8 phone:mb-10">
                Get instant access to our reporting dashboard and see your campaign performance in real-time
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
