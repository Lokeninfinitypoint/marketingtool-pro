import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import AnimatedScreen from '@/components/AnimatedScreen';
import Link from 'next/link';
import { UserPlus, Mail, Lock, User, Shield, CheckCircle, ArrowRight } from 'lucide-react';

export default function SignUpPage() {
  const benefits = [
    'Access to all AI-powered tools',
    'Generate unlimited ad variations',
    'Connect multiple ad accounts',
    'Advanced reporting and analytics',
    'Priority support',
    'API access',
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20 flex items-center justify-center py-12 phone:py-20">
        <div className="container-responsive max-w-5xl w-full">
          <div className="grid grid-cols-1 desktop:grid-cols-2 gap-8 phone:gap-12 items-center">
            {/* Left Side - Form */}
            <div className="card-premium card-3d">
              <div className="text-center mb-8">
                <UserPlus className="w-12 h-12 text-purple-400 mx-auto mb-4 animate-float-3d" />
                <h1 className="text-3xl phone:text-4xl font-bold mb-2 text-white">
                  Create Account
                </h1>
                <p className="text-gray-400">
                  Join thousands of businesses optimizing their PPC campaigns
                </p>
              </div>
              
              <form className="space-y-6">
                <div>
                  <label htmlFor="name" className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                    Full Name
                  </label>
                  <div className="relative">
                    <User className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <input
                      type="text"
                      id="name"
                      name="name"
                      required
                      className="w-full pl-10 pr-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors text-sm phone:text-base"
                      placeholder="John Doe"
                    />
                  </div>
                </div>

                <div>
                  <label htmlFor="email" className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                    Email Address
                  </label>
                  <div className="relative">
                    <Mail className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <input
                      type="email"
                      id="email"
                      name="email"
                      required
                      className="w-full pl-10 pr-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors text-sm phone:text-base"
                      placeholder="your@email.com"
                    />
                  </div>
                </div>
                
                <div>
                  <label htmlFor="password" className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                    Password
                  </label>
                  <div className="relative">
                    <Lock className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <input
                      type="password"
                      id="password"
                      name="password"
                      required
                      className="w-full pl-10 pr-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors text-sm phone:text-base"
                      placeholder="••••••••"
                    />
                  </div>
                </div>

                <div>
                  <label htmlFor="confirm-password" className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                    Confirm Password
                  </label>
                  <div className="relative">
                    <Lock className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                    <input
                      type="password"
                      id="confirm-password"
                      name="confirm-password"
                      required
                      className="w-full pl-10 pr-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors text-sm phone:text-base"
                      placeholder="••••••••"
                    />
                  </div>
                </div>
                
                <div className="flex items-start gap-2">
                  <input type="checkbox" id="terms" className="w-4 h-4 bg-gray-900 border-purple-500/20 rounded mt-1" required />
                  <label htmlFor="terms" className="text-sm text-gray-300">
                    I agree to the{' '}
                    <Link href="/terms-of-service" className="text-purple-400 hover:text-purple-300">
                      Terms of Service
                    </Link>
                    {' '}and{' '}
                    <Link href="/privacy-policy" className="text-purple-400 hover:text-purple-300">
                      Privacy Policy
                    </Link>
                  </label>
                </div>
                
                <a
                  href="https://app.adnexai.app/auth/signup"
                  className="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 via-pink-600 to-purple-600 rounded-lg text-white font-semibold hover:shadow-lg hover:shadow-purple-500/50 transition-all hover:scale-105 relative overflow-hidden group"
                >
                  <span className="relative z-10">Create Account</span>
                  <ArrowRight className="w-5 h-5 relative z-10" />
                </a>
              </form>
              
              <div className="mt-6 pt-6 border-t border-purple-500/20">
                <div className="flex items-center gap-2 text-sm text-gray-400 mb-4">
                  <Shield className="w-4 h-4 text-purple-400" />
                  <span>Enterprise-grade security</span>
                </div>
                <div className="space-y-2 text-xs text-gray-500">
                  <div className="flex items-center gap-2">
                    <CheckCircle className="w-3 h-3 text-green-400" />
                    <span>256-bit encryption</span>
                  </div>
                  <div className="flex items-center gap-2">
                    <CheckCircle className="w-3 h-3 text-green-400" />
                    <span>Two-factor authentication</span>
                  </div>
                </div>
              </div>
              
              <div className="mt-6 text-center">
                <p className="text-gray-400 text-sm">
                  Already have an account?{' '}
                  <Link href="/sign-in" className="text-purple-400 hover:text-purple-300 font-medium">
                    Sign in
                  </Link>
                </p>
              </div>
            </div>

            {/* Right Side - Benefits */}
            <div className="hidden desktop:block">
              <AnimatedScreen>
                <div className="card-premium rounded-xl overflow-hidden aspect-square bg-gradient-to-br from-purple-900 to-gray-900 flex flex-col items-center justify-center p-8">
                  <div className="text-center mb-8">
                    <h2 className="text-3xl font-bold mb-4 text-white">
                      Why Join <span className="text-gradient-vibrant">AITool.Software</span>?
                    </h2>
                  </div>
                  <ul className="space-y-4 w-full">
                    {benefits.map((benefit, idx) => (
                      <li key={idx} className="flex items-start gap-3">
                        <CheckCircle className="w-5 h-5 text-purple-400 flex-shrink-0 mt-0.5" />
                        <span className="text-gray-300">{benefit}</span>
                      </li>
                    ))}
                  </ul>
                </div>
              </AnimatedScreen>
            </div>
          </div>
        </div>
      </main>
      
      <Footer />
    </div>
  );
}
