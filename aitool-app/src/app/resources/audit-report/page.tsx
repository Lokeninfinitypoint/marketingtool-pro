import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import AnimatedScreen from '@/components/AnimatedScreen';
import { ClipboardCheck, CheckCircle, BarChart3, TrendingUp } from 'lucide-react';

export default function AuditReportPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center z-10">
            <ClipboardCheck className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">Account</span>{' '}
              <span className="text-gradient-vibrant">Audit Report</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Comprehensive account analysis and recommendations to optimize your PPC performance
            </p>
            <AnimatedButton href="/resources/free-audit" size="large">
              Request Free Audit
            </AnimatedButton>
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
                <h2 className="text-3xl phone:text-4xl font-bold mb-6 text-white">Get Your Audit Report</h2>
                <p className="text-lg text-gray-300 mb-4 leading-relaxed">
                  Our comprehensive audit analyzes your PPC account structure, performance, and optimization opportunities. 
                  We examine every aspect of your campaigns to identify areas for improvement.
                </p>
                <p className="text-base text-gray-400 mb-6 leading-relaxed">
                  Get actionable insights and recommendations tailored to your specific account. 
                  Our audit covers Meta Ads, Google Ads, and cross-platform optimization strategies.
                </p>
                <ul className="space-y-3">
                  {['Account structure analysis', 'Performance metrics review', 'Optimization opportunities', 'Budget recommendations'].map((item, idx) => (
                    <li key={idx} className="flex items-start gap-3">
                      <CheckCircle className="w-5 h-5 text-purple-400 flex-shrink-0 mt-0.5" />
                      <span className="text-gray-300">{item}</span>
                    </li>
                  ))}
                </ul>
              </div>
            </div>

            <div className="card-premium card-3d max-w-3xl mx-auto">
              <h3 className="text-2xl phone:text-3xl font-bold mb-6 text-white text-center">What You'll Get</h3>
              <div className="grid grid-cols-1 tablet:grid-cols-2 gap-6">
                {[
                  { title: 'Performance Analysis', desc: 'Detailed review of your campaign metrics' },
                  { title: 'Structure Review', desc: 'Account organization and hierarchy analysis' },
                  { title: 'Optimization Plan', desc: 'Actionable recommendations for improvement' },
                  { title: 'Budget Allocation', desc: 'Smart budget distribution strategies' },
                ].map((item, idx) => (
                  <div key={idx} className="p-4 bg-gray-800/50 rounded-lg">
                    <h4 className="text-lg font-semibold text-white mb-2">{item.title}</h4>
                    <p className="text-gray-400 text-sm">{item.desc}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center">
            <div className="card-premium card-3d max-w-3xl mx-auto px-6 phone:px-8 py-12 phone:py-16">
              <h2 className="text-3xl phone:text-4xl font-bold mb-4 phone:mb-6 text-white">
                Ready for Your Free Audit?
              </h2>
              <p className="text-lg phone:text-xl text-gray-300 mb-8 phone:mb-10">
                Get comprehensive insights into your account performance
              </p>
              <AnimatedButton href="/resources/free-audit" size="large">
                Request Free Audit
              </AnimatedButton>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
