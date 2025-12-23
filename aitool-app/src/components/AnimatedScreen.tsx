'use client';

import React from 'react';

interface AnimatedScreenProps {
  children: React.ReactNode;
  className?: string;
}

export default function AnimatedScreen({ children, className = '' }: AnimatedScreenProps) {
  return (
    <div className={`animated-screen relative ${className}`}>
      <div className="relative z-10">
        {children}
      </div>
    </div>
  );
}
