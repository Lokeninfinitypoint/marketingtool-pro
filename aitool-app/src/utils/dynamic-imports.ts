/**
 * Utility functions for dynamic imports to reduce initial bundle size
 */

import dynamic from 'next/dynamic';

// Lazy load Navigation component
export const Navigation = dynamic(() => import('@/components/Navigation'), {
  ssr: true,
  loading: () => (
    <nav className="fixed top-0 left-0 right-0 z-[999] bg-gradient-to-b from-gray-900/95 via-purple-900/95 to-gray-900/95 backdrop-blur-[20px] border-b border-purple-500/20">
      <div className="container-responsive">
        <div className="flex items-center justify-between h-16 phone:h-20">
          <div className="h-8 w-40 bg-gray-800 rounded animate-pulse" />
          <div className="h-10 w-24 bg-gray-800 rounded animate-pulse" />
        </div>
      </div>
    </nav>
  ),
});

// Lazy load Footer component
export const Footer = dynamic(() => import('@/components/Footer'), {
  ssr: true,
  loading: () => (
    <footer className="bg-gray-900 border-t border-purple-500/20">
      <div className="container-responsive py-12">
        <div className="h-32 bg-gray-800 rounded animate-pulse" />
      </div>
    </footer>
  ),
});

// Lazy load AnimatedScreen component (only when needed)
export const AnimatedScreen = dynamic(() => import('@/components/AnimatedScreen'), {
  ssr: true,
});

// Lazy load AnimatedButton component (only when needed)
export const AnimatedButton = dynamic(() => import('@/components/AnimatedButton'), {
  ssr: true,
});
