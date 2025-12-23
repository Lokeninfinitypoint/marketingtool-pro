'use client';

import React, { useState, useRef, useEffect } from 'react';
import Link from 'next/link';
import { ChevronDown, Menu, X, HelpCircle, FileText, ClipboardCheck, BarChart3, Wrench, Search, BookOpen, TrendingUp, Gift, Mail, Briefcase, Info } from 'lucide-react';

export default function Navigation() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [activeDropdown, setActiveDropdown] = useState<string | null>(null);
  const dropdownRefs = {
    resources: useRef<HTMLDivElement>(null),
    blog: useRef<HTMLDivElement>(null),
    company: useRef<HTMLDivElement>(null),
  };

  // Close dropdowns when clicking outside
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      Object.values(dropdownRefs).forEach(ref => {
        if (ref.current && !ref.current.contains(event.target as Node)) {
          setActiveDropdown(null);
        }
      });
    };

    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  const toggleDropdown = (dropdown: string) => {
    setActiveDropdown(activeDropdown === dropdown ? null : dropdown);
  };

  const resourcesItems = [
    { icon: BookOpen, label: 'Read original PPC research and insights', href: '/resources/ppc-research', description: 'Latest industry research and data' },
    { icon: ClipboardCheck, label: 'Account audit report', href: '/resources/audit-report', description: 'Comprehensive account analysis' },
    { icon: BarChart3, label: 'Get your free PPC audit', href: '/resources/free-audit', description: 'Free performance assessment' },
    { icon: TrendingUp, label: 'Test-drive our free reporting', href: '/resources/free-reporting', description: 'Try our reporting tools' },
    { icon: Wrench, label: 'Free tools', href: '/resources/tools', description: 'Access our free tool suite' },
    { icon: Search, label: 'Discover', href: '/resources/discover', description: 'Explore all resources' },
  ];

  const blogItems = [
    { label: 'All Posts', href: '/blog' },
    { label: 'PPC', href: '/blog/category/ppc' },
    { label: 'SEO', href: '/blog/category/seo' },
    { label: 'Writing', href: '/blog/category/writing' },
    { label: 'Tools', href: '/blog/category/tools' },
  ];

  const companyItems = [
    { icon: HelpCircle, label: 'Help Center', href: '/tools', description: 'Get support and documentation', button: true },
    { icon: FileText, label: 'Changelog', href: '/changelog', description: 'Latest updates and features' },
    { icon: Gift, label: 'Affiliate Program', href: '/affiliate', description: 'Earn by referring customers' },
    { icon: Mail, label: 'Contact Us', href: '/contact', description: 'Get in touch with our team' },
    { icon: Briefcase, label: 'Careers', href: '/careers', description: 'Join our growing team' },
    { icon: Info, label: 'About Us', href: '/about', description: 'Learn more about our mission' },
  ];

  return (
    <nav className="fixed top-0 left-0 right-0 z-[999] bg-white/80 dark:bg-gray-900/80 backdrop-blur-[19.9px] border-b border-transparent dark:border-gray-800">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <div className="flex-shrink-0">
            <Link href="/" className="text-2xl font-bold text-gradient">
              MarketingTool.Pro
            </Link>
          </div>

          {/* Desktop Navigation */}
          <div className="hidden lg:flex lg:items-center lg:space-x-0">
            {/* Home */}
            <Link
              href="/"
              className="px-4 py-[1.6875rem] text-base font-medium text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400 transition-colors capitalize"
            >
              Home
            </Link>

            {/* Services */}
            <Link
              href="/services"
              className="px-4 py-[1.6875rem] text-base font-medium text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400 transition-colors capitalize"
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
                className="px-4 py-[1.6875rem] text-base font-medium text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400 transition-colors capitalize flex items-center gap-[0.3125rem]"
              >
                Resources
                <ChevronDown className={`w-2 h-2 transition-transform ${activeDropdown === 'resources' ? 'rotate-180' : ''}`} />
              </button>
              {(activeDropdown === 'resources') && (
                <div className="absolute top-full left-0 mt-0 w-80 bg-white dark:bg-gray-900 rounded-md shadow-[0_8px_40px_rgba(0,0,0,0.1)] dark:shadow-[0_8px_40px_rgba(0,0,0,0.5)] py-[0.9375rem] pr-[1.875rem] z-50 border-b-2 border-[#f8b638]">
                  {resourcesItems.map((item, index) => {
                    const Icon = item.icon;
                    return (
                      <Link
                        key={index}
                        href={item.href}
                        className="flex items-start gap-3 px-5 py-[0.75rem] text-base font-medium text-gray-700 dark:text-gray-300 hover:text-[#f46d6b] dark:hover:text-[#f46d6b] transition-all duration-300 ease-[cubic-bezier(0.455,0.03,0.515,0.955)] hover:translate-x-2.5 group"
                        onClick={() => setActiveDropdown(null)}
                      >
                        <Icon className="w-5 h-5 text-purple-600 dark:text-purple-400 mt-0.5 flex-shrink-0 group-hover:text-[#f46d6b]" />
                        <div className="flex-1">
                          <div className="font-medium">{item.label}</div>
                          {item.description && (
                            <div className="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{item.description}</div>
                          )}
                        </div>
                      </Link>
                    );
                  })}
                </div>
              )}
            </div>

            {/* Blog Dropdown */}
            <div 
              className="relative group" 
              ref={dropdownRefs.blog}
              onMouseEnter={() => setActiveDropdown('blog')}
              onMouseLeave={() => setActiveDropdown(null)}
            >
              <button
                onClick={() => toggleDropdown('blog')}
                className="px-4 py-[1.6875rem] text-base font-medium text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400 transition-colors capitalize flex items-center gap-[0.3125rem]"
              >
                Blog
                <ChevronDown className={`w-2 h-2 transition-transform ${activeDropdown === 'blog' ? 'rotate-180' : ''}`} />
              </button>
              {(activeDropdown === 'blog') && (
                <div className="absolute top-full left-0 mt-0 w-56 bg-white dark:bg-gray-900 rounded-md shadow-[0_8px_40px_rgba(0,0,0,0.1)] dark:shadow-[0_8px_40px_rgba(0,0,0,0.5)] py-[0.9375rem] pr-[1.875rem] z-50 border-b-2 border-[#f8b638]">
                  {blogItems.map((item, index) => (
                    <Link
                      key={index}
                      href={item.href}
                      className="block px-5 py-[0.375rem] text-base font-medium text-gray-700 dark:text-gray-300 hover:text-[#f46d6b] dark:hover:text-[#f46d6b] transition-all duration-300 ease-[cubic-bezier(0.455,0.03,0.515,0.955)] hover:translate-x-2.5"
                      onClick={() => setActiveDropdown(null)}
                    >
                      {item.label}
                    </Link>
                  ))}
                </div>
              )}
            </div>

            {/* Company Dropdown */}
            <div 
              className="relative group" 
              ref={dropdownRefs.company}
              onMouseEnter={() => setActiveDropdown('company')}
              onMouseLeave={() => setActiveDropdown(null)}
            >
              <button
                onClick={() => toggleDropdown('company')}
                className="px-4 py-[1.6875rem] text-base font-medium text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400 transition-colors capitalize flex items-center gap-[0.3125rem]"
              >
                Company
                <ChevronDown className={`w-2 h-2 transition-transform ${activeDropdown === 'company' ? 'rotate-180' : ''}`} />
              </button>
              {(activeDropdown === 'company') && (
                <div className="absolute top-full left-0 mt-0 w-80 bg-white dark:bg-gray-900 rounded-md shadow-[0_8px_40px_rgba(0,0,0,0.1)] dark:shadow-[0_8px_40px_rgba(0,0,0,0.5)] py-[0.9375rem] pr-[1.875rem] z-50 border-b-2 border-[#f8b638]">
                  {companyItems.map((item, index) => {
                    const Icon = item.icon;
                    return (
                      <Link
                        key={index}
                        href={item.href}
                        className={`flex items-start gap-3 px-5 py-[0.75rem] text-base font-medium text-gray-700 dark:text-gray-300 hover:text-[#f46d6b] dark:hover:text-[#f46d6b] transition-all duration-300 ease-[cubic-bezier(0.455,0.03,0.515,0.955)] hover:translate-x-2.5 group ${
                          item.button ? 'bg-purple-50 dark:bg-purple-900/30 font-semibold rounded-md' : ''
                        }`}
                        onClick={() => setActiveDropdown(null)}
                      >
                        <Icon className="w-5 h-5 text-purple-600 dark:text-purple-400 mt-0.5 flex-shrink-0 group-hover:text-[#f46d6b]" />
                        <div className="flex-1">
                          <div className="font-medium">{item.label}</div>
                          {item.description && (
                            <div className="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{item.description}</div>
                          )}
                        </div>
                      </Link>
                    );
                  })}
                </div>
              )}
            </div>

            {/* Sign In / Free Trial Buttons */}
            <div className="flex items-center gap-5 ml-4">
              <Link
                href="/sign-in"
                className="px-4 py-2 text-base font-medium text-gray-900 dark:text-gray-100 hover:text-[#f46d6b] dark:hover:text-[#f46d6b] transition-colors"
              >
                Sign In
              </Link>
              <Link
                href="/free-trial"
                className="px-5 py-[0.9375rem] text-base font-semibold text-white bg-gradient-to-br from-[#732cff] via-[#732cff] to-[#f46d6b] rounded-full hover:shadow-lg transition-all hover:scale-105 flex items-center gap-[0.375rem]"
              >
                Free Trial
              </Link>
            </div>
          </div>

          {/* Mobile menu button */}
          <div className="lg:hidden">
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="p-[18px] text-gray-900 hover:text-purple-600 transition-colors"
              aria-label="Toggle menu"
            >
              {mobileMenuOpen ? (
                <X className="w-6 h-6" />
              ) : (
                <div className="flex flex-col gap-1.5">
                  <div className="w-6 h-0.5 bg-gray-900 transition-all"></div>
                  <div className="w-6 h-0.5 bg-gray-900 transition-all"></div>
                  <div className="w-6 h-0.5 bg-gray-900 transition-all"></div>
                </div>
              )}
            </button>
          </div>
        </div>
      </div>

      {/* Mobile Navigation */}
      {mobileMenuOpen && (
        <div className="lg:hidden border-t border-gray-200 dark:border-gray-800 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md">
          <div className="px-4 py-4 space-y-1">
            <Link
              href="/"
              className="block px-4 py-3 text-base font-medium text-gray-900 dark:text-gray-100 hover:text-[#f46d6b] transition-colors capitalize"
              onClick={() => setMobileMenuOpen(false)}
            >
              Home
            </Link>
            <Link
              href="/services"
              className="block px-4 py-3 text-base font-medium text-gray-900 dark:text-gray-100 hover:text-[#f46d6b] transition-colors capitalize"
              onClick={() => setMobileMenuOpen(false)}
            >
              Services
            </Link>

            {/* Mobile Resources */}
            <div className="px-4 py-3">
              <button
                onClick={() => toggleDropdown('mobile-resources')}
                className="flex items-center justify-between w-full text-base font-medium text-gray-900 dark:text-gray-100 hover:text-[#f46d6b] capitalize"
              >
                Resources
                <ChevronDown className={`w-5 h-5 transition-transform ${activeDropdown === 'mobile-resources' ? 'rotate-180' : ''}`} />
              </button>
              {activeDropdown === 'mobile-resources' && (
                <div className="mt-2 ml-4 space-y-1">
                  {resourcesItems.map((item, index) => {
                    const Icon = item.icon;
                    return (
                      <Link
                        key={index}
                        href={item.href}
                        className="flex items-start gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-[#f46d6b] transition-colors"
                        onClick={() => {
                          setActiveDropdown(null);
                          setMobileMenuOpen(false);
                        }}
                      >
                        <Icon className="w-4 h-4 text-purple-600 dark:text-purple-400 mt-0.5 flex-shrink-0" />
                        <span>{item.label}</span>
                      </Link>
                    );
                  })}
                </div>
              )}
            </div>

            {/* Mobile Blog */}
            <div className="px-4 py-3">
              <button
                onClick={() => toggleDropdown('mobile-blog')}
                className="flex items-center justify-between w-full text-base font-medium text-gray-900 dark:text-gray-100 hover:text-[#f46d6b] capitalize"
              >
                Blog
                <ChevronDown className={`w-5 h-5 transition-transform ${activeDropdown === 'mobile-blog' ? 'rotate-180' : ''}`} />
              </button>
              {activeDropdown === 'mobile-blog' && (
                <div className="mt-2 ml-4 space-y-1">
                  {blogItems.map((item, index) => (
                    <Link
                      key={index}
                      href={item.href}
                      className="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-[#f46d6b] transition-colors"
                      onClick={() => {
                        setActiveDropdown(null);
                        setMobileMenuOpen(false);
                      }}
                    >
                      {item.label}
                    </Link>
                  ))}
                </div>
              )}
            </div>

            {/* Mobile Company */}
            <div className="px-4 py-3">
              <button
                onClick={() => toggleDropdown('mobile-company')}
                className="flex items-center justify-between w-full text-base font-medium text-gray-900 dark:text-gray-100 hover:text-[#f46d6b] capitalize"
              >
                Company
                <ChevronDown className={`w-5 h-5 transition-transform ${activeDropdown === 'mobile-company' ? 'rotate-180' : ''}`} />
              </button>
              {activeDropdown === 'mobile-company' && (
                <div className="mt-2 ml-4 space-y-1">
                  {companyItems.map((item, index) => {
                    const Icon = item.icon;
                    return (
                      <Link
                        key={index}
                        href={item.href}
                        className={`flex items-start gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:text-[#f46d6b] transition-colors ${
                          item.button ? 'font-semibold bg-purple-50 dark:bg-purple-900/30' : ''
                        }`}
                        onClick={() => {
                          setActiveDropdown(null);
                          setMobileMenuOpen(false);
                        }}
                      >
                        <Icon className="w-4 h-4 text-purple-600 dark:text-purple-400 flex-shrink-0 mt-0.5" />
                        <div className="flex-1">
                          <div className="font-medium">{item.label}</div>
                          {item.description && (
                            <div className="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{item.description}</div>
                          )}
                        </div>
                      </Link>
                    );
                  })}
                </div>
              )}
            </div>

            {/* Mobile Sign In / Free Trial */}
            <div className="px-4 pt-4 space-y-2 border-t border-gray-200 dark:border-gray-800">
              <Link
                href="/sign-in"
                className="block px-4 py-3 text-base font-medium text-gray-900 dark:text-gray-100 hover:text-[#f46d6b] transition-colors text-center"
                onClick={() => setMobileMenuOpen(false)}
              >
                Sign In
              </Link>
              <Link
                href="/free-trial"
                className="block px-5 py-[0.9375rem] text-base font-semibold text-white bg-gradient-to-br from-[#732cff] via-[#732cff] to-[#f46d6b] rounded-full hover:shadow-lg transition-all text-center"
                onClick={() => setMobileMenuOpen(false)}
              >
                Free Trial
              </Link>
            </div>
          </div>
        </div>
      )}
    </nav>
  );
}
