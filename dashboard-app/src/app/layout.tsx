import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "MarketingTool.Pro - Your Marketing Command Center",
  description: "Access 418+ marketing tools, generate content, optimize campaigns, and scale your business with intelligent automation.",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" className="dark">
      <body className="antialiased dark:bg-gray-900 dark:text-gray-100">
        {children}
      </body>
    </html>
  );
}
