'use client';

import React, { useState, useRef, useEffect, useCallback, memo } from 'react';
import Link from 'next/link';
import { 
  ChevronDown, 
  Menu, 
  X, 
  ClipboardCheck, 
  BarChart3, 
  FileCheck,
  Wrench, 
  Search,
  HelpCircle,
  FileText,
  Gift,
  Mail,
  Briefcase,
  Info,
  Eye
} from '@/components/icons';

export default function Navigation() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [activeDropdown, setActiveDropdown] = useState<string | null>(null);
  const dropdownRefs = {
    resources: useRef<HTMLDivElement>(null),
    company: useRef<HTMLDivElement>(null),
  };

  // Close dropdowns when clicking outside - memoized handler
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      Object.values(dropdownRefs).forEach(ref => {
        if (ref.current && !ref.current.contains(event.target as Node)) {
          setActiveDropdown(null);
        }
      });
    };

    document.addEventListener('mousedown', handleClickOutside, { passive: true });
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  // Memoized toggle function to prevent unnecessary re-renders
  const toggleDropdown = useCallback((dropdown: string) => {
    setActiveDropdown(prev => prev === dropdown ? null : dropdown);
  }, []);

  // Memoized handlers for dropdown hover
  const handleMouseEnter = useCallback((dropdown: string) => {
    setActiveDropdown(dropdown);
  }, []);

  const handleMouseLeave = useCallback(() => {
    setActiveDropdown(null);
  }, []);


  const companyItems = [
    { icon: HelpCircle, label: 'Help Center', href: '/tools', description: 'Access tools & dashboard', button: true },
    { icon: FileText, label: 'Changelog', href: '/changelog', description: "What's new in our platform" },
    { icon: Gift, label: 'Affiliate Program', href: '/affiliate', description: 'Become an affiliate and earn' },
    { icon: Mail, label: 'Contact Us', href: '/contact', description: 'Get in touch' },
    { icon: Briefcase, label: 'Careers', href: '/careers', description: 'Join the team' },
    { icon: Info, label: 'About Us', href: '/about', description: 'Meet the team behind AITool.Software' },
  ];

  const resourcesItems = [
    { icon: Search, label: 'Read original PPC research and insights', href: '/resources/ppc-research', description: 'Original research and insights' },
    { icon: FileCheck, label: 'Account audit report', href: '/resources/audit-report', description: 'Detailed audit reports' },
    { icon: ClipboardCheck, label: 'Get your free PPC audit', href: '/resources/free-audit', description: 'Get your free audit' },
    { icon: BarChart3, label: 'Test-drive our free reporting', href: '/resources/free-reporting', description: 'Try our reporting tools' },
    { icon: Wrench, label: 'Free tools', href: '/resources/tools', description: 'Access all tools' },
    { icon: Eye, label: 'Discover', href: '/resources/discover', description: 'Discover our resources' },
  ];

  return (
    <nav className="fixed top-0 left-0 right-0 z-[999] bg-gradient-to-b from-gray-900/95 via-purple-900/95 to-gray-900/95 backdrop-blur-[20px] border-b border-purple-500/20 shadow-lg shadow-purple-900/20">
      {/* Premium starry effect background */}
      <div className="absolute inset-0 starry-bg opacity-30"></div>
      
      <div className="relative container-responsive">
        <div className="flex items-center justify-between h-16 phone:h-20">
          {/* Logo */}
          <div className="flex-shrink-0">
            <Link href="/" className="text-xl phone:text-2xl font-bold bg-gradient-to-r from-purple-400 via-pink-400 to-purple-400 bg-clip-text text-transparent hover:scale-105 transition-transform">
              AITool.Software
            </Link>
          </div>

          {/* Desktop Navigation - Show from tablet (900px) */}
          <div className="hidden tablet:flex tablet:items-center tablet:space-x-1">
            {/* Home */}
            <Link
              href="/"
              className="px-4 py-[1.6875rem] text-base font-medium text-gray-200 hover:text-purple-400 transition-colors capitalize"
            >
              Home
            </Link>

            {/* Services */}
            <Link
              href="/services"
              className="px-4 py-[1.6875rem] text-base font-medium text-gray-200 hover:text-purple-400 transition-colors capitalize"
            >
              Services
            </Link>

            {/* Resources Dropdown */}
            <div 
              className="relative group" 
              ref={dropdownRefs.resources}
              onMouseEnter={() => setActiveDropdown('resources')}
              onMouseLeave={() => setActiveDropdown(null)}
            >
              <button
                onClick={() => toggleDropdown('resources')}
                className="px-4 py-[1.6875rem] text-base font-medium text-gray-200 hover:text-purple-400 transition-colors capitalize flex items-center gap-[0.3125rem]"
              >
                Resources
                <ChevronDown className={`w-3 h-3 transition-transform ${activeDropdown === 'resources' ? 'rotate-180' : ''}`} />
              </button>
              {(activeDropdown === 'resources') && (
                <div className="absolute top-full left-0 mt-2 w-[90vw] tablet:w-[500px] desktop:w-[600px] bg-gray-900/98 backdrop-blur-xl rounded-xl shadow-3d border border-purple-500/20 py-4 tablet:py-6 px-4 tablet:px-6 z-50 card-3d">
                  <div className="grid grid-cols-1 tablet:grid-cols-2 gap-3 tablet:gap-4">
                    {resourcesItems.map((item, index) => {
                      const Icon = item.icon;
                      return (
                        <Link
                          key={index}
                          href={item.href}
                          className="flex items-start gap-4 p-4 rounded-lg hover:bg-purple-900/30 transition-all duration-300 group"
                          onClick={() => setActiveDropdown(null)}
                        >
                          <div className="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                            <Icon className="w-6 h-6 text-white" />
                          </div>
                          <div className="flex-1 min-w-0">
                            <div className="font-semibold text-white text-sm mb-1 group-hover:text-purple-300 transition-colors">
                              {item.label}
                            </div>
                            <div className="text-xs text-gray-400">{item.description}</div>
                          </div>
                        </Link>
                      );
                    })}
                  </div>
                </div>
              )}
            </div>

            {/* Blog - NO Dropdown, just link */}
            <Link
              href="/blog"
              className="px-4 py-[1.6875rem] text-base font-medium text-gray-200 hover:text-purple-400 transition-colors capitalize"
            >
              Blog
            </Link>

            {/* Company Dropdown */}
            <div 
              className="relative group" 
              ref={dropdownRefs.company}
              onMouseEnter={() => setActiveDropdown('company')}
              onMouseLeave={() => setActiveDropdown(null)}
            >
              <button
                onClick={() => toggleDropdown('company')}
                className="px-4 py-[1.6875rem] text-base font-medium text-gray-200 hover:text-purple-400 transition-colors capitalize flex items-center gap-[0.3125rem]"
              >
                Company
                <ChevronDown className={`w-3 h-3 transition-transform ${activeDropdown === 'company' ? 'rotate-180' : ''}`} />
              </button>
              {(activeDropdown === 'company') && (
                <div className="absolute top-full left-0 mt-2 w-[90vw] tablet:w-[500px] desktop:w-[600px] bg-gray-900/98 backdrop-blur-xl rounded-xl shadow-3d border border-purple-500/20 py-4 tablet:py-6 px-4 tablet:px-6 z-50 card-3d">
                  <div className="grid grid-cols-1 tablet:grid-cols-2 gap-3 tablet:gap-4">
                    {companyItems.map((item, index) => {
                      const Icon = item.icon;
                      return (
                        <Link
                          key={index}
                          href={item.href}
                          className={`flex items-start gap-4 p-4 rounded-lg hover:bg-purple-900/30 transition-all duration-300 group ${
                            item.button ? 'bg-purple-900/20 border border-purple-500/30' : ''
                          }`}
                          onClick={() => setActiveDropdown(null)}
                        >
                          <div className={`w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform ${
                            item.button 
                              ? 'bg-gradient-to-br from-purple-500 to-pink-500' 
                              : 'bg-gradient-to-br from-purple-600 to-purple-800'
                          }`}>
                            <Icon className="w-6 h-6 text-white" />
                          </div>
                          <div className="flex-1 min-w-0">
                            <div className={`font-semibold text-white text-sm mb-1 group-hover:text-purple-300 transition-colors ${
                              item.button ? 'text-purple-300' : ''
                            }`}>
                              {item.label}
                            </div>
                            <div className="text-xs text-gray-400">{item.description}</div>
                          </div>
                        </Link>
                      );
                    })}
                  </div>
                </div>
              )}
            </div>

            {/* Sign In / Free Trial Buttons */}
            <div className="flex items-center gap-4 ml-4">
              <Link
                href="/sign-in"
                className="px-4 py-2 text-base font-medium text-gray-200 hover:text-purple-400 transition-colors"
              >
                Sign In
              </Link>
              <Link
                href="/free-trial"
                className="px-6 py-3 text-base font-semibold text-white bg-gradient-to-r from-purple-600 via-pink-600 to-purple-600 rounded-lg hover:shadow-lg hover:shadow-purple-500/50 transition-all hover:scale-105"
              >
                Free Trial
              </Link>
            </div>
          </div>

          {/* Mobile menu button - Show below tablet (900px) */}
          <div className="tablet:hidden">
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="p-2 text-gray-200 hover:text-purple-400 transition-colors hover:scale-110"
              aria-label="Toggle menu"
            >
              {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>
      </div>

      {/* Mobile Navigation - Show below tablet (900px) */}
      {mobileMenuOpen && (
        <div className="tablet:hidden border-t border-purple-500/20 bg-gray-900/98 backdrop-blur-xl">
          <div className="px-4 py-4 space-y-1">
            <Link href="/" className="block px-4 py-3 text-base font-medium text-gray-200 hover:text-purple-400 transition-colors capitalize" onClick={() => setMobileMenuOpen(false)}>
              Home
            </Link>
            <Link href="/services" className="block px-4 py-3 text-base font-medium text-gray-200 hover:text-purple-400 transition-colors capitalize" onClick={() => setMobileMenuOpen(false)}>
              Services
            </Link>
            
            {/* Mobile Resources */}
            <div className="px-4 py-3">
              <button onClick={() => toggleDropdown('mobile-resources')} className="flex items-center justify-between w-full text-base font-medium text-gray-200 hover:text-purple-400 capitalize">
                Resources
                <ChevronDown className={`w-5 h-5 transition-transform ${activeDropdown === 'mobile-resources' ? 'rotate-180' : ''}`} />
              </button>
              {activeDropdown === 'mobile-resources' && (
                <div className="mt-2 ml-4 space-y-1">
                  {resourcesItems.map((item, index) => {
                    const Icon = item.icon;
                    return (
                      <Link key={index} href={item.href} className="flex items-start gap-3 px-4 py-2 text-sm text-gray-300 hover:text-purple-400 transition-colors" onClick={() => { setActiveDropdown(null); setMobileMenuOpen(false); }}>
                        <Icon className="w-4 h-4 text-purple-400 mt-0.5 flex-shrink-0" />
                        <span>{item.label}</span>
                      </Link>
                    );
                  })}
                </div>
              )}
            </div>

            <Link href="/blog" className="block px-4 py-3 text-base font-medium text-gray-200 hover:text-purple-400 transition-colors capitalize" onClick={() => setMobileMenuOpen(false)}>
              Blog
            </Link>

            {/* Mobile Company */}
            <div className="px-4 py-3">
              <button onClick={() => toggleDropdown('mobile-company')} className="flex items-center justify-between w-full text-base font-medium text-gray-200 hover:text-purple-400 capitalize">
                Company
                <ChevronDown className={`w-5 h-5 transition-transform ${activeDropdown === 'mobile-company' ? 'rotate-180' : ''}`} />
              </button>
              {activeDropdown === 'mobile-company' && (
                <div className="mt-2 ml-4 space-y-1">
                  {companyItems.map((item, index) => {
                    const Icon = item.icon;
                    return (
                      <Link key={index} href={item.href} className={`flex items-start gap-3 px-4 py-2 text-sm text-gray-300 hover:text-purple-400 transition-colors ${item.button ? 'font-semibold' : ''}`} onClick={() => { setActiveDropdown(null); setMobileMenuOpen(false); }}>
                        <Icon className="w-4 h-4 text-purple-400 flex-shrink-0 mt-0.5" />
                        <div>
                          <div>{item.label}</div>
                          <div className="text-xs text-gray-400">{item.description}</div>
                        </div>
                      </Link>
                    );
                  })}
                </div>
              )}
            </div>

            {/* Mobile Sign In / Free Trial */}
            <div className="px-4 pt-4 space-y-2 border-t border-purple-500/20">
              <Link href="/sign-in" className="block px-4 py-3 text-base font-medium text-gray-200 hover:text-purple-400 transition-colors text-center" onClick={() => setMobileMenuOpen(false)}>
                Sign In
              </Link>
              <Link href="/free-trial" className="block px-6 py-3 text-base font-semibold text-white bg-gradient-to-r from-purple-600 via-pink-600 to-purple-600 rounded-lg hover:shadow-lg transition-all text-center" onClick={() => setMobileMenuOpen(false)}>
                Free Trial
              </Link>
            </div>
          </div>
        </div>
      )}
    </nav>
  );
}
