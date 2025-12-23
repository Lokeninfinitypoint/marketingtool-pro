'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { ArrowLeft, Sparkles, Star, Zap, AlertTriangle, Settings, Image, TrendingUp, Eye } from 'lucide-react';

export default function FacebookAdsDashboardPage() {
  const [connected, setConnected] = useState(false);
  const [creatives, setCreatives] = useState([
    { id: 1, name: 'Summer Collection Ad', score: 92, status: 'Winning', impressions: '125K', ctr: '3.2%' },
    { id: 2, name: 'Product Showcase', score: 78, status: 'Testing', impressions: '89K', ctr: '2.8%' },
    { id: 3, name: 'Brand Story', score: 45, status: 'Fatigued', impressions: '45K', ctr: '1.1%' },
  ]);

  const metrics = [
    { label: 'Total Spend', value: '$3,240', change: '+18%', positive: true },
    { label: 'ROAS', value: '4.1x', change: '+0.5x', positive: true },
    { label: 'Creative Score', value: '85', change: '+8', positive: true },
    { label: 'Ad Fatigue', value: '12%', change: '-5%', positive: true },
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
            <div className="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center">
              <Sparkles className="w-8 h-8 text-white" />
            </div>
            <div>
              <h1 className="text-3xl phone:text-4xl font-bold text-white">Facebook Ads</h1>
              <p className="text-gray-400">Creative & Scaling</p>
            </div>
          </div>
        </div>

        {/* Connect Status */}
        {!connected ? (
          <div className="card-premium card-3d mb-8">
            <div className="flex items-center justify-between">
              <div>
                <h3 className="text-xl font-bold text-white mb-2">Account Not Connected</h3>
                <p className="text-gray-400">Connect your Facebook Ads account to start optimizing</p>
              </div>
              <button 
                onClick={() => setConnected(true)}
                className="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg text-white font-semibold hover:shadow-lg transition-all"
              >
                Connect Facebook Ads
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

            {/* Creatives List */}
            <div className="card-premium card-3d mb-8">
              <div className="flex items-center justify-between mb-6">
                <h2 className="text-2xl font-bold text-white">Ad Creatives</h2>
                <button className="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white text-sm font-medium transition-colors">
                  + Create Ad
                </button>
              </div>
              <div className="space-y-4">
                {creatives.map((creative) => (
                  <div key={creative.id} className="p-4 bg-gray-800/50 rounded-lg border border-purple-500/20 hover:border-purple-500/50 transition-all">
                    <div className="flex items-center justify-between mb-2">
                      <h3 className="text-lg font-semibold text-white">{creative.name}</h3>
                      <div className="flex items-center gap-3">
                        <div className="text-right">
                          <div className="text-xs text-gray-400">Score</div>
                          <div className={`text-lg font-bold ${
                            creative.score >= 80 ? 'text-green-400' : creative.score >= 60 ? 'text-yellow-400' : 'text-red-400'
                          }`}>
                            {creative.score}
                          </div>
                        </div>
                        <span className={`px-2 py-1 rounded text-xs font-medium ${
                          creative.status === 'Winning' ? 'bg-green-500/20 text-green-400' :
                          creative.status === 'Testing' ? 'bg-yellow-500/20 text-yellow-400' :
                          'bg-red-500/20 text-red-400'
                        }`}>
                          {creative.status}
                        </span>
                      </div>
                    </div>
                    <div className="grid grid-cols-3 gap-4 text-sm">
                      <div>
                        <div className="text-gray-400">Impressions</div>
                        <div className="text-white font-semibold">{creative.impressions}</div>
                      </div>
                      <div>
                        <div className="text-gray-400">CTR</div>
                        <div className="text-white font-semibold">{creative.ctr}</div>
                      </div>
                      <div>
                        <div className="text-gray-400">Status</div>
                        <div className="text-white font-semibold">{creative.status}</div>
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
            { icon: Star, title: 'Creative Scoring', desc: 'AI-powered creative performance analysis and scoring' },
            { icon: Zap, title: 'Hook Analysis', desc: 'Identify high-performing ad hooks and patterns' },
            { icon: AlertTriangle, title: 'Fatigue Detection', desc: 'Automatically detect and flag ad fatigue' },
          ].map((feature, idx) => {
            const Icon = feature.icon;
            return (
              <div key={idx} className="card-premium card-3d">
                <div className="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center mb-4">
                  <Icon className="w-6 h-6 text-white" />
                </div>
                <h3 className="text-lg font-bold text-white mb-2">{feature.title}</h3>
                <p className="text-gray-400 text-sm">{feature.desc}</p>
              </div>
            );
          })}
        </div>

        {/* Creative Performance Chart */}
        {connected && (
          <div className="card-premium card-3d">
            <h3 className="text-xl font-bold text-white mb-4">Creative Performance</h3>
            <div className="h-64 bg-gray-800/50 rounded-lg flex items-center justify-center border border-purple-500/20">
              <Image className="w-16 h-16 text-gray-600 opacity-50" />
            </div>
          </div>
        )}
      </main>
    </div>
  );
}
