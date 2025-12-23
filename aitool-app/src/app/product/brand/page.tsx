import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import Separator from '@/components/Separator';
import { Palette, Sparkles } from 'lucide-react';

export default function BrandPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive z-10">
            <div className="text-center mb-8 phone:mb-12">
              <Palette className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
              <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
                <span className="text-white">Brand</span>
              </h1>
              <p className="text-lg phone:text-xl text-gray-300 max-w-3xl mx-auto px-4 mb-8">
                Our brand identity and guidelines
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
                Brand Guidelines
              </h2>
              <div className="card-premium card-3d p-8">
                <p className="text-gray-300 mb-6">
                  AITool.Software represents innovation, intelligence, and excellence in AI-powered marketing.
                </p>
                <div className="space-y-4">
                  <div>
                    <h3 className="text-xl font-bold text-white mb-2">Brand Colors</h3>
                    <p className="text-gray-400">Purple and Pink gradients representing AI and innovation</p>
                  </div>
                  <div>
                    <h3 className="text-xl font-bold text-white mb-2">Brand Values</h3>
                    <p className="text-gray-400">Innovation, Intelligence, Excellence, Trust</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
