import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "AITool.Software - AI-Powered PPC Platform",
  description: "Intelligent bid management, smart ad copy generation, and automated optimization. Maximize your ROAS with the power of AI.",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" className="dark">
      <body className="antialiased bg-gray-900 text-gray-100">
        {children}
      </body>
    </html>
  );
}
