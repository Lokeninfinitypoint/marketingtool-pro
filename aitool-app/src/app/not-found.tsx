import Link from 'next/link';
import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import { Home, ArrowLeft } from 'lucide-react';

export default function NotFound() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive z-10">
            <div className="text-center mb-8 phone:mb-12">
              <div className="mb-6">
                <h1 className="text-8xl phone:text-9xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 mb-4">
                  404
                </h1>
                <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold text-white mb-4">
                  Page Not Found
                </h2>
                <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8">
                  The page you're looking for doesn't exist or has been moved.
                </p>
              </div>
              
              <div className="flex flex-col phone:flex-row gap-4 justify-center items-center">
                <Link 
                  href="/"
                  className="px-6 phone:px-8 py-3 phone:py-4 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg text-white font-semibold hover:from-purple-700 hover:to-pink-700 transition-all backdrop-blur-sm text-base phone:text-lg flex items-center gap-2 card-3d"
                >
                  <Home className="w-5 h-5" />
                  Go Home
                </Link>
                
                <Link
                  href="/tools/all"
                  className="px-6 phone:px-8 py-3 phone:py-4 bg-gray-800/80 border border-purple-500/30 rounded-lg text-white font-semibold hover:bg-gray-700/80 transition-all backdrop-blur-sm text-base phone:text-lg flex items-center gap-2 card-3d"
                >
                  <ArrowLeft className="w-5 h-5" />
                  Browse Tools
                </Link>
              </div>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}

