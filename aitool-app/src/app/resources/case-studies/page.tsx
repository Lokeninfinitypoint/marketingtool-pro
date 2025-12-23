import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { FileText, TrendingUp, CheckCircle } from 'lucide-react';

export default function CaseStudiesPage() {
  const studies = [
    { company: 'TechCorp Inc.', result: '3.2x ROAS increase', metric: '+250% revenue' },
    { company: 'ShopSmart', result: '45% cost reduction', metric: '+180% conversions' },
    { company: 'StartupXYZ', result: '20+ hours saved/week', metric: '+320% efficiency' },
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive z-10">
            <div className="text-center mb-8 phone:mb-12">
              <FileText className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
              <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
                <span className="text-white">Case</span>{' '}
                <span className="text-gradient-vibrant">Studies</span>
              </h1>
              <p className="text-lg phone:text-xl text-gray-300 max-w-3xl mx-auto px-4 mb-8">
                Real success stories from businesses using AITool.Software
              </p>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="max-w-5xl mx-auto">
              <h2 className="text-3xl phone:text-4xl font-bold mb-8 text-white text-center">
                Success Stories
              </h2>
              <div className="grid grid-cols-1 tablet:grid-cols-3 gap-6">
                {studies.map((study, idx) => (
                  <div key={idx} className="card-premium card-3d">
                    <h3 className="text-xl font-bold text-white mb-4">{study.company}</h3>
                    <div className="space-y-2 mb-4">
                      <div className="flex items-center gap-2 text-green-400">
                        <TrendingUp className="w-5 h-5" />
                        <span className="font-semibold">{study.result}</span>
                      </div>
                      <p className="text-gray-400 text-sm">{study.metric}</p>
                    </div>
                    <AnimatedButton href="/app/dashboard" className="w-full">
                      Read Full Case Study
                    </AnimatedButton>
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
