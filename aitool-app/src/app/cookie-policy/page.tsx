import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import Separator from '@/components/Separator';
import { Cookie } from 'lucide-react';

export default function CookiePolicyPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive z-10">
            <div className="text-center mb-8 phone:mb-12">
              <Cookie className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
              <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
                <span className="text-white">Cookie</span>{' '}
                <span className="text-gradient-vibrant">Policy</span>
              </h1>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="max-w-4xl mx-auto rich-content">
              <div className="card-premium card-3d p-8">
                <h2 className="text-2xl font-bold text-white mb-4">Cookie Policy</h2>
                <p className="text-gray-300 mb-4">
                  This Cookie Policy explains how AITool.Software uses cookies and similar technologies.
                </p>
                <h3 className="text-xl font-bold text-white mt-6 mb-3">What Are Cookies?</h3>
                <p className="text-gray-300 mb-4">
                  Cookies are small text files that are placed on your device when you visit our website.
                </p>
                <h3 className="text-xl font-bold text-white mt-6 mb-3">How We Use Cookies</h3>
                <p className="text-gray-300 mb-4">
                  We use cookies to improve your experience, analyze site usage, and assist in marketing efforts.
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
