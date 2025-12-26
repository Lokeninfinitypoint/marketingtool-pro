import type { Metadata } from "next";
import { Inter } from 'next/font/google';
import "./globals.css";

// Optimize font loading
const inter = Inter({ 
  subsets: ['latin'],
  display: 'swap',
  variable: '--font-inter',
  preload: true,
  fallback: ['system-ui', 'arial'],
});

export const metadata: Metadata = {
  title: "MarketingTool.Pro - Your Marketing Command Center",
  description: "Access 418+ marketing tools, generate content, optimize campaigns, and scale your business with intelligent automation.",
  metadataBase: new URL('https://marketingtool.pro'),
  openGraph: {
    title: "MarketingTool.Pro - Your Marketing Command Center",
    description: "Access 418+ marketing tools, generate content, optimize campaigns, and scale your business.",
    type: 'website',
  },
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" className={`dark ${inter.variable}`}>
      <head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
        <link rel="dns-prefetch" href="https://fonts.googleapis.com" />
      </head>
      <body className="antialiased dark:bg-gray-900 dark:text-gray-100 font-sans">
        {children}
      </body>
    </html>
  );
}
