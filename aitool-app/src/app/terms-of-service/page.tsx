import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import Separator from '@/components/Separator';
import { FileText } from 'lucide-react';

export default function TermsOfServicePage() {
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
                <span className="text-white">Terms of</span>{' '}
                <span className="text-gradient-vibrant">Service</span>
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
                <h2 className="text-2xl font-bold text-white mb-4">Terms of Service</h2>
                <p className="text-gray-300 mb-4">
                  These Terms of Service govern your use of AITool.Software. By using our service, you agree to these terms.
                </p>
                <h3 className="text-xl font-bold text-white mt-6 mb-3">Acceptance of Terms</h3>
                <p className="text-gray-300 mb-4">
                  By accessing or using AITool.Software, you agree to be bound by these Terms of Service.
                </p>
                <h3 className="text-xl font-bold text-white mt-6 mb-3">Use of Service</h3>
                <p className="text-gray-300 mb-4">
                  You may use our service for lawful purposes only and in accordance with these Terms.
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
