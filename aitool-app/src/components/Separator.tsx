'use client';

import React from 'react';

interface SeparatorProps {
  opacity?: number;
  className?: string;
}

export default function Separator({ opacity = 1, className = '' }: SeparatorProps) {
  return (
    <div className={`separator_component ${className}`}>
      {/* Using CSS-based separator since we don't have the image yet */}
      <div 
        className="separator_img w-full h-px bg-gradient-to-r from-transparent via-purple-500/30 to-transparent"
        style={{ opacity }}
      ></div>
    </div>
  );
}
