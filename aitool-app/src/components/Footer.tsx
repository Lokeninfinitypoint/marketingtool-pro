'use client';

import React from 'react';
import Link from 'next/link';
import { Phone, MapPin, FileText, Mail, Youtube, Linkedin, Twitter, Facebook, Instagram } from 'lucide-react';

export default function Footer() {
  return (
    <footer className="bg-gray-900 border-t border-purple-500/20">
      <div className="container-responsive py-12 phone:py-16">
        <div className="grid grid-cols-1 phone:grid-cols-2 tablet:grid-cols-3 desktop:grid-cols-6 gap-8 phone:gap-12 mb-8 phone:mb-12">
          {/* Company Info */}
          <div className="col-span-1 phone:col-span-2">
            <h3 className="text-xl phone:text-2xl font-bold bg-gradient-to-r from-purple-400 via-pink-400 to-purple-400 bg-clip-text text-transparent mb-4">
              AITool.Software
            </h3>
            <p className="text-gray-400 text-sm phone:text-base mb-6">
              AI-powered PPC platform for Meta Ads and Google Ads management.
            </p>
            <div className="flex items-center gap-2 text-sm text-gray-400">
              <span>Meta</span>
              <span className="px-2 py-1 bg-purple-500/20 border border-purple-500/30 rounded text-xs">Business Partner</span>
            </div>
          </div>

          {/* Workflows */}
          <div>
            <h4 className="text-white font-semibold mb-4 text-sm phone:text-base">Workflows</h4>
            <ul className="space-y-2">
              <li><Link href="/workflows/optimization" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Optimization</Link></li>
              <li><Link href="/workflows/ai-ads" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">AI Ads</Link></li>
              <li><Link href="/workflows/analytics" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Analytics</Link></li>
              <li><Link href="/workflows/agentic-marketing" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Agentic Marketing</Link></li>
              <li><Link href="/workflows/facebook-ads-mcp" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Facebook Ads MCP</Link></li>
            </ul>
          </div>

          {/* Resources */}
          <div>
            <h4 className="text-white font-semibold mb-4 text-sm phone:text-base">Resources</h4>
            <ul className="space-y-2">
              <li><Link href="/blog" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Blog</Link></li>
              <li><Link href="/resources/academy" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Academy</Link></li>
              <li><Link href="/resources/case-studies" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Case Studies</Link></li>
              <li><Link href="/resources/ppc-research" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">PPC Research</Link></li>
            </ul>
          </div>

          {/* Company */}
          <div>
            <h4 className="text-white font-semibold mb-4 text-sm phone:text-base">Company</h4>
            <ul className="space-y-2">
              <li><Link href="/about" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">About Us</Link></li>
              <li><Link href="/company/why-aitool" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Why AITool.Software?</Link></li>
              <li><Link href="/careers" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Careers</Link></li>
              <li><Link href="/contact" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Contact Us</Link></li>
            </ul>
          </div>

          {/* Product */}
          <div>
            <h4 className="text-white font-semibold mb-4 text-sm phone:text-base">Product</h4>
            <ul className="space-y-2">
              <li><Link href="/product/pricing" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Pricing</Link></li>
              <li><Link href="/product/brand" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Brand</Link></li>
              <li><Link href="/product/whats-new" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">What's New?</Link></li>
              <li><Link href="/tools" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">Help Center</Link></li>
            </ul>
          </div>

          {/* Contact Info */}
          <div>
            <h4 className="text-white font-semibold mb-4 text-sm phone:text-base">Contact</h4>
            <ul className="space-y-3">
              <li className="flex items-start gap-3">
                <Phone className="w-5 h-5 text-purple-400 flex-shrink-0 mt-0.5" />
                <a href="tel:+18555742506" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">
                  +1-855-574-2506
                </a>
              </li>
              <li className="flex items-start gap-3">
                <MapPin className="w-5 h-5 text-purple-400 flex-shrink-0 mt-0.5" />
                <span className="text-gray-400 text-sm">
                  F-12 Govindam Tower<br />
                  Jaipur 302012
                </span>
              </li>
              <li className="flex items-start gap-3">
                <Mail className="w-5 h-5 text-purple-400 flex-shrink-0 mt-0.5" />
                <a href="mailto:Help@aitool.software" className="text-gray-400 hover:text-purple-400 transition-colors text-sm">
                  Help@aitool.software
                </a>
              </li>
            </ul>
          </div>
        </div>

        {/* Social Media & Registration Info */}
        <div className="pt-8 border-t border-purple-500/20">
          <div className="flex flex-col phone:flex-row justify-between items-center gap-6 phone:gap-8">
            <div className="flex items-center gap-3">
              <FileText className="w-5 h-5 text-purple-400" />
              <div className="text-gray-400 text-xs phone:text-sm">
                <div>Registration Certificate: <span className="text-white">RJ-17-0526261</span></div>
                <div>Registration Number: <span className="text-white">08AAUFC7776R1Z2</span></div>
              </div>
            </div>
            
            <div className="flex items-center gap-4">
              <a href="https://youtube.com" target="_blank" rel="nofollow" className="text-gray-400 hover:text-purple-400 transition-colors" aria-label="YouTube">
                <Youtube className="w-5 h-5" />
              </a>
              <a href="https://linkedin.com" target="_blank" rel="nofollow" className="text-gray-400 hover:text-purple-400 transition-colors" aria-label="LinkedIn">
                <Linkedin className="w-5 h-5" />
              </a>
              <a href="https://twitter.com" target="_blank" rel="nofollow" className="text-gray-400 hover:text-purple-400 transition-colors" aria-label="Twitter">
                <Twitter className="w-5 h-5" />
              </a>
              <a href="https://facebook.com" target="_blank" rel="nofollow" className="text-gray-400 hover:text-purple-400 transition-colors" aria-label="Facebook">
                <Facebook className="w-5 h-5" />
              </a>
              <a href="https://instagram.com" target="_blank" rel="nofollow" className="text-gray-400 hover:text-purple-400 transition-colors" aria-label="Instagram">
                <Instagram className="w-5 h-5" />
              </a>
            </div>
          </div>
          
          <div className="mt-6 pt-6 border-t border-purple-500/20 flex flex-col phone:flex-row justify-between items-center gap-4">
            <div className="text-gray-400 text-xs phone:text-sm">
              © {new Date().getFullYear()} AITool.Software. All rights reserved.
            </div>
            <div className="flex flex-wrap gap-4 text-xs phone:text-sm">
              <Link href="/cookie-policy" className="text-gray-400 hover:text-purple-400 transition-colors">Cookie Policy</Link>
              <Link href="/privacy-policy" className="text-gray-400 hover:text-purple-400 transition-colors">Privacy Policy</Link>
              <Link href="/terms-of-service" className="text-gray-400 hover:text-purple-400 transition-colors">Terms of Service</Link>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}
