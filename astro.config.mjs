// @ts-check
import { defineConfig } from 'astro/config';
import react from '@astrojs/react';
import tailwindcss from '@tailwindcss/vite';

// https://astro.build/config
export default defineConfig({
  integrations: [react()],

  vite: {
    plugins: [tailwindcss()],
    
    // Build optimizations
    build: {
      // Enable minification
      minify: 'esbuild',
      
      // Target modern browsers for smaller bundles
      target: 'esnext',
      
      // CSS code splitting
      cssCodeSplit: true,
      
      // Reduce chunk size warnings threshold
      chunkSizeWarningLimit: 500,
      
      // Rollup options for better chunking
      rollupOptions: {
        output: {
          // Manual chunks for better caching
          manualChunks: {
            'react-vendor': ['react', 'react-dom'],
          },
          // Asset file naming for better caching
          assetFileNames: 'assets/[name].[hash][extname]',
          chunkFileNames: 'chunks/[name].[hash].js',
          entryFileNames: 'entries/[name].[hash].js',
        },
      },
    },
    
    // Optimize dependencies
    optimizeDeps: {
      // Pre-bundle these dependencies for faster dev server
      include: ['react', 'react-dom'],
    },
    
    // Enable CSS optimizations
    css: {
      devSourcemap: false,
    },
  },

  // Build output configuration
  build: {
    // Inline small assets
    inlineStylesheets: 'auto',
  },
  
  // Compression is handled by the server
  compressHTML: true,
  
  // Prefetch configuration for better navigation
  prefetch: {
    prefetchAll: false,
    defaultStrategy: 'viewport',
  },
  
  // Output configuration
  output: 'static',
});
