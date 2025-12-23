import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import { DollarSign, TrendingUp, Users, Gift } from 'lucide-react';

export default function AffiliatePage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-20">
        {/* Hero Section */}
        <section className="relative py-20 bg-gradient-to-b from-purple-900/20 to-gray-900">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 className="text-5xl md:text-6xl font-bold mb-4">
              <span className="text-white">Become an</span>{' '}
              <span className="text-gradient-vibrant">Affiliate</span>
            </h1>
            <p className="text-xl text-gray-300 max-w-2xl mx-auto">
              Earn by referring customers and help businesses transform their PPC campaigns
            </p>
          </div>
        </section>

        {/* Benefits Section */}
        <section className="py-20">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
              <div className="bg-gray-800/50 border border-purple-500/20 rounded-xl p-8 text-center">
                <DollarSign className="w-12 h-12 text-purple-400 mx-auto mb-4" />
                <h3 className="text-xl font-bold mb-2 text-white">Generous Commissions</h3>
                <p className="text-gray-400">Earn competitive commissions on every referral</p>
              </div>
              
              <div className="bg-gray-800/50 border border-purple-500/20 rounded-xl p-8 text-center">
                <TrendingUp className="w-12 h-12 text-purple-400 mx-auto mb-4" />
                <h3 className="text-xl font-bold mb-2 text-white">Recurring Revenue</h3>
                <p className="text-gray-400">Earn ongoing commissions from lifetime subscriptions</p>
              </div>
              
              <div className="bg-gray-800/50 border border-purple-500/20 rounded-xl p-8 text-center">
                <Users className="w-12 h-12 text-purple-400 mx-auto mb-4" />
                <h3 className="text-xl font-bold mb-2 text-white">Dedicated Support</h3>
                <p className="text-gray-400">Get dedicated support from our affiliate team</p>
              </div>
            </div>

            {/* How It Works */}
            <div className="bg-gray-800/50 border border-purple-500/20 rounded-xl p-8">
              <h2 className="text-3xl font-bold mb-8 text-white text-center">How It Works</h2>
              
              <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div className="text-center">
                  <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-white">
                    1
                  </div>
                  <h3 className="font-semibold text-white mb-2">Sign Up</h3>
                  <p className="text-gray-400 text-sm">Join our affiliate program</p>
                </div>
                
                <div className="text-center">
                  <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-white">
                    2
                  </div>
                  <h3 className="font-semibold text-white mb-2">Get Your Link</h3>
                  <p className="text-gray-400 text-sm">Receive your unique referral link</p>
                </div>
                
                <div className="text-center">
                  <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-white">
                    3
                  </div>
                  <h3 className="font-semibold text-white mb-2">Share</h3>
                  <p className="text-gray-400 text-sm">Share with your audience</p>
                </div>
                
                <div className="text-center">
                  <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-white">
                    4
                  </div>
                  <h3 className="font-semibold text-white mb-2">Earn</h3>
                  <p className="text-gray-400 text-sm">Get paid for every conversion</p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* CTA Section */}
        <section className="py-20 bg-gray-900/50">
          <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <Gift className="w-16 h-16 text-purple-400 mx-auto mb-6" />
            <h2 className="text-4xl font-bold mb-4 text-white">
              Ready to Start Earning?
            </h2>
            <p className="text-xl text-gray-300 mb-8">
              Join our affiliate program and start earning commissions today
            </p>
            <AnimatedButton href="/affiliate/signup" size="large">
              Join Affiliate Program
            </AnimatedButton>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
