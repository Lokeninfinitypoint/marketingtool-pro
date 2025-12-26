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
  title: "AITool.Software - AI-Powered PPC Platform",
  description: "Intelligent bid management, smart ad copy generation, and automated optimization. Maximize your ROAS with the power of AI.",
  metadataBase: new URL('https://aitool.software'),
  openGraph: {
    title: "AITool.Software - AI-Powered PPC Platform",
    description: "Intelligent bid management, smart ad copy generation, and automated optimization.",
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
      <body className="antialiased bg-gray-900 text-gray-100 font-sans">
        {children}
      </body>
    </html>
  );
}
