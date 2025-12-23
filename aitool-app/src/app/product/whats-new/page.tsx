import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Sparkles, Calendar, CheckCircle } from 'lucide-react';

export default function WhatsNewPage() {
  const updates = [
    { date: '2025-01-15', title: 'New AI Ads Generator', description: 'Generate 100+ ad variations automatically' },
    { date: '2025-01-10', title: 'Facebook Ads MCP', description: 'Chat with your Meta ads data' },
    { date: '2025-01-05', title: '425+ Tools Available', description: 'Complete suite of marketing tools' },
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
                What's <span className="text-gradient-vibrant">New</span>?
              </h1>
              <p className="text-lg phone:text-xl text-gray-300 max-w-3xl mx-auto px-4 mb-8">
                Latest updates and features
              </p>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="max-w-4xl mx-auto">
              <div className="space-y-6">
                {updates.map((update, idx) => (
                  <div key={idx} className="card-premium card-3d">
                    <div className="flex items-start gap-4">
                      <Calendar className="w-6 h-6 text-purple-400 flex-shrink-0 mt-1" />
                      <div className="flex-1">
                        <div className="flex items-center gap-2 mb-2">
                          <span className="text-sm text-gray-400">{update.date}</span>
                          <CheckCircle className="w-4 h-4 text-green-400" />
                        </div>
                        <h3 className="text-xl font-bold text-white mb-2">{update.title}</h3>
                        <p className="text-gray-400">{update.description}</p>
                      </div>
                    </div>
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
