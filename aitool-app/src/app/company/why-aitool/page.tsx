import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import { Sparkles, CheckCircle, TrendingUp, Shield } from 'lucide-react';

export default function WhyAIToolPage() {
  const reasons = [
    'AI-powered optimization that works 24/7',
    '425+ integrated tools in one platform',
    'Increase ROAS by up to 3.2x',
    'Save 20+ hours per week on campaign management',
    'Enterprise-grade security and compliance',
    'Real-time performance monitoring',
    'Multi-platform support (Meta & Google)',
    'Automated creative generation and testing',
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive z-10">
            <div className="text-center mb-8 phone:mb-12">
              <Sparkles className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
              <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
                Why <span className="text-gradient-vibrant">AITool.Software</span>?
              </h1>
              <p className="text-lg phone:text-xl text-gray-300 max-w-3xl mx-auto px-4 mb-8">
                The most advanced AI-powered PPC platform that helps you maximize results and save time
              </p>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="max-w-4xl mx-auto">
              <h2 className="text-3xl phone:text-4xl font-bold mb-8 text-white text-center">
                Why Choose Us?
              </h2>
              <div className="grid grid-cols-1 tablet:grid-cols-2 gap-6">
                {reasons.map((reason, idx) => (
                  <div key={idx} className="card-premium card-3d flex items-start gap-4">
                    <CheckCircle className="w-6 h-6 text-green-400 flex-shrink-0 mt-0.5" />
                    <p className="text-white font-medium">{reason}</p>
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
