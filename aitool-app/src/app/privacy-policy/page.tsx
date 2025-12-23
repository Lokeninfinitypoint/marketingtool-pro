import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import Separator from '@/components/Separator';
import { Shield } from 'lucide-react';

export default function PrivacyPolicyPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive z-10">
            <div className="text-center mb-8 phone:mb-12">
              <Shield className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
              <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
                <span className="text-white">Privacy</span>{' '}
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
                <h2 className="text-2xl font-bold text-white mb-4">Privacy Policy</h2>
                <p className="text-gray-300 mb-4">
                  Your privacy is important to us. This Privacy Policy explains how we collect, use, and protect your information.
                </p>
                <h3 className="text-xl font-bold text-white mt-6 mb-3">Information We Collect</h3>
                <p className="text-gray-300 mb-4">
                  We collect information you provide directly, usage data, and information from third-party services.
                </p>
                <h3 className="text-xl font-bold text-white mt-6 mb-3">How We Use Your Information</h3>
                <p className="text-gray-300 mb-4">
                  We use your information to provide services, improve our platform, and communicate with you.
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
