import type { Metadata, Viewport } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "AITool.Software - AI-Powered PPC Platform",
  description: "Intelligent bid management, smart ad copy generation, and automated optimization. Maximize your ROAS with the power of AI.",
  // SEO optimizations
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      'max-video-preview': -1,
      'max-image-preview': 'large',
      'max-snippet': -1,
    },
  },
  // Open Graph for social sharing
  openGraph: {
    title: 'AITool.Software - AI-Powered PPC Platform',
    description: 'Intelligent bid management, smart ad copy generation, and automated optimization. Maximize your ROAS with the power of AI.',
    type: 'website',
    locale: 'en_US',
  },
};

// Viewport configuration for performance
export const viewport: Viewport = {
  width: 'device-width',
  initialScale: 1,
  themeColor: '#9333ea',
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" className="dark">
      <head>
        {/* DNS prefetch for external resources */}
        <link rel="dns-prefetch" href="//fonts.googleapis.com" />
        <link rel="dns-prefetch" href="//fonts.gstatic.com" />
        
        {/* Preconnect for faster font loading */}
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
        
        {/* Optimized Inter font with display swap for faster LCP */}
        <link
          rel="preload"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
          as="style"
        />
        <link
          rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
          media="print"
          // @ts-expect-error onLoad handler for stylesheet
          onLoad="this.media='all'"
        />
        <noscript>
          <link
            rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
          />
        </noscript>
      </head>
      <body className="antialiased bg-gray-900 text-gray-100">
        {children}
      </body>
    </html>
  );
}
