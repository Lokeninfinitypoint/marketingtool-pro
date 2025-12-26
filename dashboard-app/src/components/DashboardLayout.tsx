'use client';

import React, { memo, Suspense } from 'react';
import dynamic from 'next/dynamic';

// Dynamically import Navigation to reduce initial bundle size
const Navigation = dynamic(() => import('./Navigation'), {
  ssr: true,
  loading: () => (
    <nav className="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center">
        <div className="h-8 w-32 bg-gray-200 dark:bg-gray-800 rounded animate-pulse" />
      </div>
    </nav>
  ),
});

interface DashboardLayoutProps {
  children: React.ReactNode;
}

const DashboardLayout = memo(function DashboardLayout({ children }: DashboardLayoutProps) {
  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors">
      <Suspense fallback={null}>
        <Navigation />
      </Suspense>
      <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pt-28">
        {children}
      </main>
    </div>
  );
});

export default DashboardLayout;
