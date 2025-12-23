import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Mail, ArrowLeft, Shield, CheckCircle } from 'lucide-react';

export default function ForgotPasswordPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20 flex items-center justify-center py-12 phone:py-20">
        <div className="container-responsive max-w-md w-full">
          <div className="card-premium card-3d">
            <div className="text-center mb-8">
              <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-4 animate-float-3d">
                <Mail className="w-8 h-8 text-white" />
              </div>
              <h1 className="text-3xl phone:text-4xl font-bold mb-2 text-white">
                Forgot Password?
              </h1>
              <p className="text-gray-400">
                No worries! Enter your email and we'll send you reset instructions.
              </p>
            </div>
            
            <form className="space-y-6">
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
              
              <AnimatedButton href="#" className="w-full flex items-center justify-center">
                Send Reset Link
              </AnimatedButton>
            </form>
            
            <div className="mt-6 pt-6 border-t border-purple-500/20">
              <div className="flex items-center gap-2 text-sm text-gray-400 mb-4">
                <Shield className="w-4 h-4 text-purple-400" />
                <span>Secure password reset via email</span>
              </div>
              <div className="space-y-2 text-xs text-gray-500">
                <div className="flex items-center gap-2">
                  <CheckCircle className="w-3 h-3 text-green-400" />
                  <span>Reset link expires in 1 hour</span>
                </div>
                <div className="flex items-center gap-2">
                  <CheckCircle className="w-3 h-3 text-green-400" />
                  <span>Check your spam folder if needed</span>
                </div>
              </div>
            </div>
            
            <div className="mt-6 text-center">
              <Link
                href="/sign-in"
                className="inline-flex items-center gap-2 text-purple-400 hover:text-purple-300 font-medium text-sm"
              >
                <ArrowLeft className="w-4 h-4" />
                Back to Sign In
              </Link>
            </div>
          </div>
        </div>
      </main>
      
      <Footer />
    </div>
  );
}
