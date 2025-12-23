import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Gift, User, Mail, Building, CheckCircle, ArrowRight } from 'lucide-react';

export default function AffiliateSignUpPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive text-center z-10">
            <Gift className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              Join Our <span className="text-gradient-vibrant">Affiliate Program</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Earn commissions by referring customers to AITool.Software
            </p>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="max-w-2xl mx-auto">
              <div className="card-premium card-3d mb-8 phone:mb-12">
                <h2 className="text-3xl phone:text-4xl font-bold mb-6 text-white text-center">
                  Affiliate Sign Up
                </h2>
                <form className="space-y-6">
                  <div>
                    <label className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                      Full Name
                    </label>
                    <div className="relative">
                      <User className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                      <input
                        type="text"
                        required
                        className="w-full pl-10 pr-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 text-sm phone:text-base"
                        placeholder="John Doe"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                      Email Address
                    </label>
                    <div className="relative">
                      <Mail className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                      <input
                        type="email"
                        required
                        className="w-full pl-10 pr-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 text-sm phone:text-base"
                        placeholder="your@email.com"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                      Company/Website (Optional)
                    </label>
                    <div className="relative">
                      <Building className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                      <input
                        type="text"
                        className="w-full pl-10 pr-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 text-sm phone:text-base"
                        placeholder="Your company or website"
                      />
                    </div>
                  </div>

                  <div className="flex items-start gap-2">
                    <input type="checkbox" id="affiliate-terms" className="w-4 h-4 bg-gray-900 border-purple-500/20 rounded mt-1" required />
                    <label htmlFor="affiliate-terms" className="text-sm text-gray-300">
                      I agree to the{' '}
                      <Link href="/affiliate/terms" className="text-purple-400 hover:text-purple-300">
                        Affiliate Terms
                      </Link>
                    </label>
                  </div>

                  <AnimatedButton href="#" className="w-full flex items-center justify-center gap-2">
                    Join Affiliate Program <ArrowRight className="w-5 h-5" />
                  </AnimatedButton>
                </form>
              </div>

              <div className="card-premium card-3d">
                <h3 className="text-2xl font-bold mb-6 text-white text-center">What Happens Next?</h3>
                <div className="space-y-4">
                  {[
                    'We\'ll review your application within 24-48 hours',
                    'You\'ll receive your unique affiliate link',
                    'Start sharing and earning commissions',
                    'Track your referrals and earnings in real-time',
                  ].map((step, idx) => (
                    <div key={idx} className="flex items-start gap-3">
                      <CheckCircle className="w-5 h-5 text-purple-400 flex-shrink-0 mt-0.5" />
                      <span className="text-gray-300">{step}</span>
                    </div>
                  ))}
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
