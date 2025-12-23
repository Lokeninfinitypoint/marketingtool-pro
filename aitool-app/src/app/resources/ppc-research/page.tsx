import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import AnimatedScreen from '@/components/AnimatedScreen';
import { BookOpen, TrendingUp, BarChart3, CheckCircle, ArrowRight } from 'lucide-react';

export default function PPCResearchPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center z-10">
            <div className="mb-4 phone:mb-6">
              <BookOpen className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
            </div>
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">PPC Research &</span>{' '}
              <span className="text-gradient-vibrant">Insights</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Latest industry research and data-driven insights to help you optimize your campaigns
            </p>
            <AnimatedButton href="/free-trial" size="large">
              Access Research
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
                <h2 className="text-3xl phone:text-4xl font-bold mb-6 text-white">Original Research</h2>
                <p className="text-lg text-gray-300 mb-4 leading-relaxed">
                  Our team conducts original research on PPC trends, performance metrics, and optimization strategies. 
                  We analyze millions of data points to uncover insights you can't find anywhere else.
                </p>
                <p className="text-base text-gray-400 mb-6 leading-relaxed">
                  Access exclusive insights and data that can help you improve your campaign performance. 
                  Our research covers Meta Ads, Google Ads, and cross-platform optimization strategies.
                </p>
                <ul className="space-y-3">
                  {['Industry benchmarks', 'Performance trends', 'Optimization strategies', 'Best practices'].map((item, idx) => (
                    <li key={idx} className="flex items-start gap-3">
                      <CheckCircle className="w-5 h-5 text-purple-400 flex-shrink-0 mt-0.5" />
                      <span className="text-gray-300">{item}</span>
                    </li>
                  ))}
                </ul>
              </div>
            </div>

            <div className="card-premium card-3d">
              <h3 className="text-2xl phone:text-3xl font-bold mb-6 text-white">Research Topics</h3>
              <div className="grid grid-cols-1 tablet:grid-cols-2 gap-6">
                {[
                  { title: 'ROAS Optimization', desc: 'Data-driven strategies to maximize return on ad spend' },
                  { title: 'Bid Management', desc: 'Best practices for intelligent bid optimization' },
                  { title: 'Ad Creative Performance', desc: 'What makes ads convert and scale' },
                  { title: 'Audience Targeting', desc: 'Advanced targeting strategies and insights' },
                ].map((topic, idx) => (
                  <div key={idx} className="p-4 bg-gray-800/50 rounded-lg">
                    <h4 className="text-lg font-semibold text-white mb-2">{topic.title}</h4>
                    <p className="text-gray-400 text-sm">{topic.desc}</p>
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
                Get Access to Exclusive Research
              </h2>
              <p className="text-lg phone:text-xl text-gray-300 mb-8 phone:mb-10">
                Start your free trial to access our complete research library
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
