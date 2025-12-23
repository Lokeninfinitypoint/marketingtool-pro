'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { ArrowLeft, Target, TrendingUp, DollarSign, BarChart3, Settings, Zap, Search, TrendingDown, AlertCircle, CheckCircle } from 'lucide-react';

export default function GoogleAdsDashboardPage() {
  const [connected, setConnected] = useState(false);
  const [campaigns, setCampaigns] = useState([
    { id: 1, name: 'Summer Sale Campaign', status: 'Active', spend: '$1,250', roas: '4.2x', conversions: 45 },
    { id: 2, name: 'Product Launch', status: 'Active', spend: '$890', roas: '3.8x', conversions: 32 },
    { id: 3, name: 'Brand Awareness', status: 'Paused', spend: '$450', roas: '2.1x', conversions: 12 },
  ]);

  const metrics = [
    { label: 'Total Spend', value: '$2,590', change: '+12%', positive: true },
    { label: 'ROAS', value: '3.7x', change: '+0.3x', positive: true },
    { label: 'Conversions', value: '89', change: '+15', positive: true },
    { label: 'CPA', value: '$29.10', change: '-$2.40', positive: true },
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
            <div className="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
              <Target className="w-8 h-8 text-white" />
            </div>
            <div>
              <h1 className="text-3xl phone:text-4xl font-bold text-white">Google Ads</h1>
              <p className="text-gray-400">Performance & Intent</p>
            </div>
          </div>
        </div>

        {/* Connect Status */}
        {!connected ? (
          <div className="card-premium card-3d mb-8">
            <div className="flex items-center justify-between">
              <div>
                <h3 className="text-xl font-bold text-white mb-2">Account Not Connected</h3>
                <p className="text-gray-400">Connect your Google Ads account to start optimizing</p>
              </div>
              <button 
                onClick={() => setConnected(true)}
                className="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white font-semibold hover:shadow-lg transition-all"
              >
                Connect Google Ads
              </button>
            </div>
          </div>
        ) : (
          <>
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

            {/* Campaigns List */}
            <div className="card-premium card-3d mb-8">
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-2xl font-bold text-white">Campaigns</h2>
                <button className="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white text-sm font-medium transition-colors">
                  + New Campaign
                </button>
              </div>
              <div className="space-y-4">
                {campaigns.map((campaign) => (
                  <div key={campaign.id} className="p-4 bg-gray-800/50 rounded-lg border border-purple-500/20 hover:border-purple-500/50 transition-all">
                    <div className="flex items-center justify-between mb-2">
                      <h3 className="text-lg font-semibold text-white">{campaign.name}</h3>
                      <span className={`px-2 py-1 rounded text-xs font-medium ${
                        campaign.status === 'Active' ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400'
                      }`}>
                        {campaign.status}
                      </span>
                    </div>
                    <div className="grid grid-cols-3 gap-4 text-sm">
                      <div>
                        <div className="text-gray-400">Spend</div>
                        <div className="text-white font-semibold">{campaign.spend}</div>
                      </div>
                      <div>
                        <div className="text-gray-400">ROAS</div>
                        <div className="text-green-400 font-semibold">{campaign.roas}</div>
                      </div>
                      <div>
                        <div className="text-gray-400">Conversions</div>
                        <div className="text-white font-semibold">{campaign.conversions}</div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </>
        )}

        {/* Features */}
        <div className="grid grid-cols-1 tablet:grid-cols-3 gap-6 phone:gap-8 mb-8">
          {[
            { icon: TrendingUp, title: 'Bidding Intelligence', desc: 'AI-powered bid optimization based on performance data' },
            { icon: Search, title: 'Search Intent Analysis', desc: 'Understand search behavior and optimize keywords' },
            { icon: DollarSign, title: 'Budget Allocation', desc: 'Smart budget distribution across campaigns' },
          ].map((feature, idx) => {
            const Icon = feature.icon;
            return (
              <div key={idx} className="card-premium card-3d">
                <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-4">
                  <Icon className="w-6 h-6 text-white" />
                </div>
                <h3 className="text-lg font-bold text-white mb-2">{feature.title}</h3>
                <p className="text-gray-400 text-sm">{feature.desc}</p>
              </div>
            );
          })}
        </div>

        {/* Performance Chart Placeholder */}
        {connected && (
          <div className="card-premium card-3d">
            <h3 className="text-xl font-bold text-white mb-4">Performance Overview</h3>
            <div className="h-64 bg-gray-800/50 rounded-lg flex items-center justify-center border border-purple-500/20">
              <BarChart3 className="w-16 h-16 text-gray-600 opacity-50" />
            </div>
          </div>
        )}
      </main>
    </div>
  );
}
