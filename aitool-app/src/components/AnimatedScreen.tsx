'use client';

import React, { memo } from 'react';

interface AnimatedScreenProps {
  children: React.ReactNode;
  className?: string;
}

const AnimatedScreen = memo(function AnimatedScreen({ children, className = '' }: AnimatedScreenProps) {
  return (
    <div className={`animated-screen relative ${className}`}>
      <div className="relative z-10">
        {children}
      </div>
    </div>
  );
});

export default AnimatedScreen;
