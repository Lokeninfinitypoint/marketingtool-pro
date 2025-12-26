'use client';

import React, { memo } from 'react';
import Link from 'next/link';

interface AnimatedButtonProps {
  href: string;
  children: React.ReactNode;
  className?: string;
  variant?: 'primary' | 'secondary';
  size?: 'default' | 'large';
  target?: string;
}

const AnimatedButton = memo(function AnimatedButton({ 
  href, 
  children, 
  className = '',
  variant = 'primary',
  size = 'default',
  target
}: AnimatedButtonProps) {
  const baseClasses = 'animated-button relative inline-block';
  const sizeClasses = size === 'large' ? 'px-8 py-4 text-lg phone:px-10 phone:py-5 phone:text-xl' : 'px-6 py-3 text-base phone:px-8 phone:py-4 phone:text-lg';
  const variantClasses = variant === 'primary' 
    ? 'bg-gradient-to-r from-purple-600 via-pink-600 to-purple-600 text-white font-semibold'
    : 'bg-gray-800 border border-purple-500/30 text-white font-semibold hover:bg-gray-700';

  return (
    <div className={baseClasses}>
      <Link
        href={href}
        target={target}
        className={`${sizeClasses} ${variantClasses} ${className} relative z-10 rounded-lg transition-all hover:scale-105`}
      >
        {children}
      </Link>
    </div>
  );
});

export default AnimatedButton;
