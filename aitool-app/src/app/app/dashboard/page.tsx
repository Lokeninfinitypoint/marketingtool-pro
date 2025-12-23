'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { 
  Target, Sparkles, BarChart3, Zap, TrendingUp, 
  Plus, Settings, LogOut, User, 
  ArrowRight, CheckCircle, AlertCircle,
  Layers, Workflow, Brain, Activity, Grid3x3
} from 'lucide-react';

export default function DashboardPage() {
  const [connectedApps, setConnectedApps] = useState<string[]>([]);

  const tools = [
    {
      id: 'google-ads',
      name: 'Google Ads',
      category: 'Performance & Intent',
      icon: Target,
      color: 'from-blue-500 to-blue-600',
      features: ['Bidding intelligence', 'Search intent analysis', 'Budget allocation'],
      connected: connectedApps.includes('google-ads'),
      href: '/app/dashboard/google-ads',
    },
    {
      id: 'facebook-ads',
      name: 'Facebook Ads',
      category: 'Creative & Scaling',
      icon: Sparkles,
      color: 'from-blue-600 to-blue-700',
      features: ['Creative scoring', 'Hook analysis', 'Fatigue detection'],
      connected: connectedApps.includes('facebook-ads'),
      href: '/app/dashboard/facebook-ads',
    },
    {
      id: 'analytics',
      name: 'Analytics & Attribution',
      icon: BarChart3,
      color: 'from-purple-500 to-purple-600',
      features: ['Cross-channel insights', 'Performance decay signals', 'ROI prediction'],
      connected: connectedApps.includes('analytics'),
      href: '/app/dashboard/analytics',
    },
    {
      id: 'automation',
      name: 'Automation & Forecasting',
      icon: Zap,
      color: 'from-pink-500 to-pink-600',
      features: ['Budget rules', 'Creative rotation', 'Campaign lifecycle logic'],
      connected: connectedApps.includes('automation'),
      href: '/app/dashboard/automation',
    },
    {
      id: 'ai-strategy',
      name: 'AI Strategy & Forecasting',
      icon: TrendingUp,
      color: 'from-green-500 to-green-600',
      features: ['Spend forecasting', 'Outcome simulation', 'Growth recommendations'],
      connected: connectedApps.includes('ai-strategy'),
      href: '/app/dashboard/ai-strategy',
    },
  ];

  return (
    <div className="min-h-screen bg-gray-900">
      {/* Dashboard Header */}
      <header className="bg-gray-900/95 backdrop-blur-xl border-b border-purple-500/20 sticky top-0 z-50">
        <div className="container-responsive">
          <div className="flex items-center justify-between h-16 phone:h-20">
            <Link href="/" className="text-xl phone:text-2xl font-bold bg-gradient-to-r from-purple-400 via-pink-400 to-purple-400 bg-clip-text text-transparent">
              AITool.Software
            </Link>
            
            <div className="flex items-center gap-4">
              <button className="p-2 text-gray-400 hover:text-purple-400 transition-colors">
                <Settings className="w-5 h-5" />
              </button>
              <button className="p-2 text-gray-400 hover:text-purple-400 transition-colors">
                <User className="w-5 h-5" />
              </button>
              <button className="p-2 text-gray-400 hover:text-purple-400 transition-colors">
                <LogOut className="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>
      </header>

      <main className="container-responsive py-8 phone:py-12">
        {/* Welcome Section */}
        <div className="mb-8 phone:mb-12">
          <div className="flex items-center gap-3 mb-4">
            <h1 className="text-3xl phone:text-4xl tablet:text-5xl font-bold text-white">
              Welcome to Your <span className="text-gradient-vibrant">Dashboard</span>
            </h1>
            <div className="hidden phone:flex items-center gap-2 px-3 py-1 bg-purple-500/20 rounded-full border border-purple-500/30">
              <Grid3x3 className="w-4 h-4 text-purple-400" />
              <span className="text-purple-400 text-xs font-semibold">425+ Tools</span>
            </div>
          </div>
          <p className="text-lg phone:text-xl text-gray-400 max-w-2xl">
            Connect your ad accounts and start optimizing with AI-powered tools
          </p>
        </div>

        {/* Systems Overview */}
        <div className="grid grid-cols-2 tablet:grid-cols-4 gap-4 phone:gap-6 mb-8 phone:mb-12">
          {[
            { icon: Layers, label: 'Ad Systems', count: '12+' },
            { icon: Workflow, label: 'Growth Pipelines', count: '50+' },
            { icon: Brain, label: 'AI Engines', count: '25+' },
            { icon: Activity, label: 'Usage Intelligence', count: '338+' },
          ].map((system, idx) => {
            const Icon = system.icon;
            return (
              <div key={idx} className="card-premium card-3d text-center">
                <Icon className="w-6 h-6 text-purple-400 mx-auto mb-2" />
                <div className="text-xl phone:text-2xl font-bold text-gradient-vibrant mb-1">
                  {system.count}
                </div>
                <div className="text-xs phone:text-sm text-gray-400">{system.label}</div>
              </div>
            );
          })}
        </div>

        {/* Connect Apps Section */}
        {connectedApps.length === 0 && (
          <div className="card-premium card-3d mb-8 phone:mb-12">
            <div className="text-center mb-6">
              <h2 className="text-2xl phone:text-3xl font-bold mb-4 text-white">
                Connect Your First App
              </h2>
              <p className="text-gray-400">
                Start by connecting Google Ads or Facebook Ads to access powerful AI tools
              </p>
            </div>
            
            <div className="grid grid-cols-1 phone:grid-cols-2 gap-4">
              <button className="p-6 bg-gray-800/50 border border-purple-500/20 rounded-lg hover:border-purple-500/50 transition-all text-left group">
                <div className="flex items-center gap-4 mb-4">
                  <div className="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <span className="text-white font-bold text-xl">G</span>
                  </div>
                  <div>
                    <h3 className="text-lg font-semibold text-white">Google Ads</h3>
                    <p className="text-sm text-gray-400">Connect via OAuth</p>
                  </div>
                </div>
                <div className="flex items-center gap-2 text-purple-400 group-hover:gap-3 transition-all">
                  Connect <ArrowRight className="w-4 h-4" />
                </div>
              </button>
              
              <button className="p-6 bg-gray-800/50 border border-purple-500/20 rounded-lg hover:border-purple-500/50 transition-all text-left group">
                <div className="flex items-center gap-4 mb-4">
                  <div className="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                    <span className="text-white font-bold text-xl">f</span>
                  </div>
                  <div>
                    <h3 className="text-lg font-semibold text-white">Facebook Ads</h3>
                    <p className="text-sm text-gray-400">Connect via Meta OAuth</p>
                  </div>
                </div>
                <div className="flex items-center gap-2 text-purple-400 group-hover:gap-3 transition-all">
                  Connect <ArrowRight className="w-4 h-4" />
                </div>
              </button>
            </div>
          </div>
        )}

        {/* Tools Grid */}
        <div className="mb-8 phone:mb-12">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-2xl phone:text-3xl font-bold text-white">
              Available Tools
            </h2>
            <button className="flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white text-sm font-medium transition-colors">
              <Plus className="w-4 h-4" />
              Add Tool
            </button>
          </div>
          
          <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-3 gap-6 phone:gap-8">
            {tools.map((tool) => {
              const Icon = tool.icon;
              return (
                <Link
                  key={tool.id}
                  href={tool.href}
                  className="card-premium card-3d group"
                >
                  <div className={`w-14 h-14 bg-gradient-to-br ${tool.color} rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform`}>
                    <Icon className="w-7 h-7 text-white" />
                  </div>
                  
                  <div className="flex items-start justify-between mb-2">
                    <div>
                      <h3 className="text-xl font-bold text-white group-hover:text-purple-300 transition-colors">
                        {tool.name}
                      </h3>
                      <p className="text-sm text-purple-300 mt-1">{tool.category}</p>
                    </div>
                    {tool.connected ? (
                      <CheckCircle className="w-5 h-5 text-green-400 flex-shrink-0" />
                    ) : (
                      <AlertCircle className="w-5 h-5 text-gray-500 flex-shrink-0" />
                    )}
                  </div>
                  
                  <ul className="space-y-2 mb-4">
                    {tool.features.map((feature, idx) => (
                      <li key={idx} className="flex items-start gap-2 text-sm text-gray-300">
                        <span className="text-purple-400 mt-0.5">•</span>
                        <span>{feature}</span>
                      </li>
                    ))}
                  </ul>
                  
                  <div className="flex items-center gap-2 text-purple-400 font-medium text-sm group-hover:gap-3 transition-all">
                    {tool.connected ? 'Open Tool' : 'Connect to Use'} <ArrowRight className="w-4 h-4" />
                  </div>
                </Link>
              );
            })}
          </div>
        </div>

        {/* Quick Stats */}
        <div className="grid grid-cols-2 tablet:grid-cols-4 gap-4 phone:gap-6">
          {[
            { label: 'Connected Apps', value: connectedApps.length },
            { label: 'Active Tools', value: tools.filter(t => t.connected).length },
            { label: 'Campaigns', value: '0' },
            { label: 'ROAS', value: '0.00x' },
          ].map((stat, idx) => (
            <div key={idx} className="card-premium text-center">
              <div className="text-2xl phone:text-3xl font-bold text-gradient-vibrant mb-1">
                {stat.value}
              </div>
              <div className="text-sm text-gray-400">{stat.label}</div>
            </div>
          ))}
        </div>
      </main>
    </div>
  );
}
