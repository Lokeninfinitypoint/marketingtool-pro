'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { ArrowLeft, BarChart3, TrendingUp, Target, Settings, Activity, DollarSign, Eye } from 'lucide-react';

export default function AnalyticsDashboardPage() {
  const [dataLoaded, setDataLoaded] = useState(false);

  const metrics = [
    { label: 'Total Revenue', value: '$45,230', change: '+23%', positive: true },
    { label: 'ROAS', value: '4.2x', change: '+0.4x', positive: true },
    { label: 'Conversion Rate', value: '3.8%', change: '+0.5%', positive: true },
    { label: 'Attribution Quality', value: '94%', change: '+6%', positive: true },
  ];

  const channels = [
    { name: 'Google Ads', revenue: '$18,500', roas: '4.5x', share: '41%' },
    { name: 'Facebook Ads', revenue: '$22,300', roas: '4.1x', share: '49%' },
    { name: 'Other', revenue: '$4,430', roas: '3.2x', share: '10%' },
  ];

  return (
    <div className="min-h-screen bg-gray-900">
      <header className="bg-gray-900/95 backdrop-blur-xl border-b border-purple-500/20 sticky top-0 z-50">
        <div className="container-responsive">
          <div className="flex items-center justify-between h-16 phone:h-20">
            <Link href="/app/dashboard" className="flex items-center gap-3 text-gray-400 hover:text-purple-400 transition-colors">
              <ArrowLeft className="w-5 h-5" />
              <span>Back to Dashboard</span>
            </Link>
            <div className="flex items-center gap-4">
              <button className="p-2 text-gray-400 hover:text-purple-400 transition-colors">
                <Settings className="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>
      </header>

      <main className="container-responsive py-8 phone:py-12">
        {/* Header */}
        <div className="mb-8">
          <div className="flex items-center gap-4 mb-4">
            <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
              <BarChart3 className="w-8 h-8 text-white" />
            </div>
            <div>
              <h1 className="text-3xl phone:text-4xl font-bold text-white">Analytics & Attribution</h1>
              <p className="text-gray-400">Cross-channel insights and performance tracking</p>
            </div>
          </div>
        </div>

        {/* Metrics Dashboard */}
        <div className="grid grid-cols-2 tablet:grid-cols-4 gap-4 phone:gap-6 mb-8">
          {metrics.map((metric, idx) => (
            <div key={idx} className="card-premium card-3d">
              <div className="text-xs text-gray-400 mb-1">{metric.label}</div>
              <div className="text-2xl phone:text-3xl font-bold text-white mb-1">{metric.value}</div>
              <div className={`text-xs ${metric.positive ? 'text-green-400' : 'text-red-400'}`}>
                {metric.change}
              </div>
            </div>
          ))}
        </div>

        {/* Channel Performance */}
        <div className="card-premium card-3d mb-8">
          <h2 className="text-2xl font-bold text-white mb-6">Channel Performance</h2>
          <div className="space-y-4">
            {channels.map((channel, idx) => (
              <div key={idx} className="p-4 bg-gray-800/50 rounded-lg border border-purple-500/20">
                <div className="flex items-center justify-between mb-3">
                  <h3 className="text-lg font-semibold text-white">{channel.name}</h3>
                  <div className="text-sm text-gray-400">{channel.share} of total</div>
                </div>
                <div className="grid grid-cols-2 gap-4 text-sm">
                  <div>
                    <div className="text-gray-400">Revenue</div>
                    <div className="text-white font-semibold">{channel.revenue}</div>
                  </div>
                  <div>
                    <div className="text-gray-400">ROAS</div>
                    <div className="text-green-400 font-semibold">{channel.roas}</div>
                  </div>
                </div>
                <div className="mt-3 h-2 bg-gray-700 rounded-full overflow-hidden">
                  <div 
                    className="h-full bg-gradient-to-r from-purple-500 to-pink-500"
                    style={{ width: channel.share }}
                  ></div>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Features */}
        <div className="grid grid-cols-1 tablet:grid-cols-3 gap-6 phone:gap-8 mb-8">
          {[
            { icon: BarChart3, title: 'Cross-Channel Insights', desc: 'Unified view across all advertising platforms' },
            { icon: TrendingUp, title: 'Performance Decay Signals', desc: 'Early warning system for performance drops' },
            { icon: Target, title: 'ROI Prediction', desc: 'AI-powered ROI forecasting and optimization' },
          ].map((feature, idx) => {
            const Icon = feature.icon;
            return (
              <div key={idx} className="card-premium card-3d">
                <div className="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-4">
                  <Icon className="w-6 h-6 text-white" />
                </div>
                <h3 className="text-lg font-bold text-white mb-2">{feature.title}</h3>
                <p className="text-gray-400 text-sm">{feature.desc}</p>
              </div>
            );
          })}
        </div>

        {/* Performance Chart */}
        <div className="card-premium card-3d">
          <h3 className="text-xl font-bold text-white mb-4">Performance Trends</h3>
          <div className="h-64 bg-gray-800/50 rounded-lg flex items-center justify-center border border-purple-500/20">
            <BarChart3 className="w-16 h-16 text-gray-600 opacity-50" />
          </div>
        </div>
      </main>
    </div>
  );
}
