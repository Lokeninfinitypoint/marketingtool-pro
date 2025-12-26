/**
 * Performance monitoring utilities
 * Use these to track and optimize performance metrics
 */

// Report Web Vitals
export function reportWebVitals(metric: any) {
  if (process.env.NODE_ENV === 'production') {
    // Log to console in production (can be replaced with analytics service)
    console.log(metric);
    
    // Example: Send to analytics
    // sendToAnalytics(metric);
  }
}

// Track component render time
export function measureComponentRender(componentName: string, callback: () => void) {
  const startTime = performance.now();
  callback();
  const endTime = performance.now();
  
  if (process.env.NODE_ENV === 'development') {
    console.log(`${componentName} rendered in ${(endTime - startTime).toFixed(2)}ms`);
  }
}

// Lazy load components
export async function lazyLoadComponent<T>(
  importFunc: () => Promise<{ default: T }>
): Promise<T> {
  const module = await importFunc();
  return module.default;
}

// Preload critical resources
export function preloadResource(href: string, as: string) {
  if (typeof window !== 'undefined') {
    const link = document.createElement('link');
    link.rel = 'preload';
    link.href = href;
    link.as = as;
    document.head.appendChild(link);
  }
}

// Check if IntersectionObserver is supported
export const supportsIntersectionObserver = 
  typeof window !== 'undefined' && 'IntersectionObserver' in window;

// Debounce function for performance
export function debounce<T extends (...args: any[]) => any>(
  func: T,
  wait: number
): (...args: Parameters<T>) => void {
  let timeout: NodeJS.Timeout | null = null;
  
  return function executedFunction(...args: Parameters<T>) {
    const later = () => {
      timeout = null;
      func(...args);
    };
    
    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Throttle function for performance
export function throttle<T extends (...args: any[]) => any>(
  func: T,
  limit: number
): (...args: Parameters<T>) => void {
  let inThrottle: boolean;
  
  return function executedFunction(...args: Parameters<T>) {
    if (!inThrottle) {
      func(...args);
      inThrottle = true;
      setTimeout(() => (inThrottle = false), limit);
    }
  };
}
