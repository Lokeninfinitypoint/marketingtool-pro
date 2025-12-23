'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { ArrowLeft, Zap, Play, Pause, Settings, Clock, DollarSign, RotateCcw } from 'lucide-react';

export default function AutomationDashboardPage() {
  const [rules, setRules] = useState([
    { id: 1, name: 'Pause Low ROAS', status: 'Active', triggers: 12, action: 'Pause campaigns with ROAS < 2.0x' },
    { id: 2, name: 'Scale Winners', status: 'Active', triggers: 8, action: 'Increase budget by 20% for ROAS > 4.0x' },
    { id: 3, name: 'Creative Rotation', status: 'Paused', triggers: 0, action: 'Rotate creatives every 7 days' },
  ]);

  const metrics = [
    { label: 'Active Rules', value: '2', change: '+1', positive: true },
    { label: 'Total Triggers', value: '20', change: '+5', positive: true },
    { label: 'Time Saved', value: '45h', change: '+12h', positive: true },
    { label: 'Budget Optimized', value: '$8,240', change: '+$1,200', positive: true },
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
            <div className="w-16 h-16 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center">
              <Zap className="w-8 h-8 text-white" />
            </div>
            <div>
              <h1 className="text-3xl phone:text-4xl font-bold text-white">Automation & Scaling</h1>
              <p className="text-gray-400">Automated rules and campaign lifecycle management</p>
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

        {/* Automation Rules */}
        <div className="card-premium card-3d mb-8">
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-2xl font-bold text-white">Automation Rules</h2>
            <button className="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white text-sm font-medium transition-colors">
              + Create Rule
            </button>
          </div>
          <div className="space-y-4">
            {rules.map((rule) => (
              <div key={rule.id} className="p-4 bg-gray-800/50 rounded-lg border border-purple-500/20 hover:border-purple-500/50 transition-all">
                <div className="flex items-center justify-between mb-2">
                  <h3 className="text-lg font-semibold text-white">{rule.name}</h3>
                  <div className="flex items-center gap-3">
                    <span className="text-sm text-gray-400">{rule.triggers} triggers</span>
                    <button className={`p-1.5 rounded ${
                      rule.status === 'Active' ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400'
                    }`}>
                      {rule.status === 'Active' ? <Play className="w-4 h-4" /> : <Pause className="w-4 h-4" />}
                    </button>
                  </div>
                </div>
                <p className="text-gray-400 text-sm">{rule.action}</p>
              </div>
            ))}
          </div>
        </div>

        {/* Features */}
        <div className="grid grid-cols-1 tablet:grid-cols-3 gap-6 phone:gap-8 mb-8">
          {[
            { icon: DollarSign, title: 'Budget Rules', desc: 'Automated budget allocation and optimization' },
            { icon: RotateCcw, title: 'Creative Rotation', desc: 'Intelligent creative refresh and rotation' },
            { icon: Clock, title: 'Campaign Lifecycle', desc: 'Automated campaign management workflows' },
          ].map((feature, idx) => {
            const Icon = feature.icon;
            return (
              <div key={idx} className="card-premium card-3d">
                <div className="w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mb-4">
                  <Icon className="w-6 h-6 text-white" />
                </div>
                <h3 className="text-lg font-bold text-white mb-2">{feature.title}</h3>
                <p className="text-gray-400 text-sm">{feature.desc}</p>
              </div>
            );
          })}
        </div>

        {/* Activity Log */}
        <div className="card-premium card-3d">
          <h3 className="text-xl font-bold text-white mb-4">Recent Activity</h3>
          <div className="space-y-3">
            {[
              { action: 'Paused campaign "Summer Sale"', time: '2 hours ago', type: 'pause' },
              { action: 'Increased budget for "Product Launch"', time: '5 hours ago', type: 'scale' },
              { action: 'Rotated creatives in "Brand Awareness"', time: '1 day ago', type: 'rotate' },
            ].map((activity, idx) => (
              <div key={idx} className="flex items-center gap-3 p-3 bg-gray-800/50 rounded-lg">
                <div className={`w-2 h-2 rounded-full ${
                  activity.type === 'pause' ? 'bg-red-400' :
                  activity.type === 'scale' ? 'bg-green-400' : 'bg-blue-400'
                }`}></div>
                <div className="flex-1">
                  <div className="text-white text-sm">{activity.action}</div>
                  <div className="text-gray-500 text-xs">{activity.time}</div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </main>
    </div>
  );
}
