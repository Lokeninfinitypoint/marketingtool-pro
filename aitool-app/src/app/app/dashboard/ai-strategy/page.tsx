'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { ArrowLeft, TrendingUp, Brain, Target, Settings, DollarSign, BarChart3, Zap } from 'lucide-react';

export default function AIStrategyDashboardPage() {
  const [forecasts, setForecasts] = useState([
    { period: 'Next 7 Days', spend: '$5,200', revenue: '$21,840', roas: '4.2x', confidence: '92%' },
    { period: 'Next 30 Days', spend: '$22,500', revenue: '$94,500', roas: '4.2x', confidence: '88%' },
    { period: 'Next 90 Days', spend: '$67,500', revenue: '$283,500', roas: '4.2x', confidence: '85%' },
  ]);

  const recommendations = [
    { title: 'Increase Budget for "Summer Sale"', impact: 'High', potential: '+$2,400 revenue' },
    { title: 'Pause Underperforming Creatives', impact: 'Medium', potential: 'Save $800/month' },
    { title: 'Expand Winning Campaigns', impact: 'High', potential: '+$1,200 revenue' },
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
            <div className="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
              <Brain className="w-8 h-8 text-white" />
            </div>
            <div>
              <h1 className="text-3xl phone:text-4xl font-bold text-white">AI Strategy & Forecasting</h1>
              <p className="text-gray-400">AI-powered insights and growth recommendations</p>
            </div>
          </div>
        </div>

        {/* Forecasts */}
        <div className="card-premium card-3d mb-8">
          <h2 className="text-2xl font-bold text-white mb-6">Revenue Forecasts</h2>
          <div className="grid grid-cols-1 tablet:grid-cols-3 gap-4">
            {forecasts.map((forecast, idx) => (
              <div key={idx} className="p-4 bg-gray-800/50 rounded-lg border border-purple-500/20">
                <div className="text-sm text-gray-400 mb-2">{forecast.period}</div>
                <div className="text-2xl font-bold text-white mb-1">{forecast.revenue}</div>
                <div className="text-xs text-gray-500 mb-3">from {forecast.spend} spend</div>
                <div className="flex items-center justify-between">
                  <div>
                    <div className="text-xs text-gray-400">ROAS</div>
                    <div className="text-green-400 font-semibold">{forecast.roas}</div>
                  </div>
                  <div>
                    <div className="text-xs text-gray-400">Confidence</div>
                    <div className="text-purple-400 font-semibold">{forecast.confidence}</div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Recommendations */}
        <div className="card-premium card-3d mb-8">
          <h2 className="text-2xl font-bold text-white mb-6">AI Recommendations</h2>
          <div className="space-y-4">
            {recommendations.map((rec, idx) => (
              <div key={idx} className="p-4 bg-gray-800/50 rounded-lg border border-purple-500/20 hover:border-purple-500/50 transition-all">
                <div className="flex items-start justify-between mb-2">
                  <h3 className="text-lg font-semibold text-white">{rec.title}</h3>
                  <span className={`px-2 py-1 rounded text-xs font-medium ${
                    rec.impact === 'High' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400'
                  }`}>
                    {rec.impact} Impact
                  </span>
                </div>
                <p className="text-gray-400 text-sm">{rec.potential}</p>
                <button className="mt-3 px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white text-sm font-medium transition-colors">
                  Apply Recommendation
                </button>
              </div>
            ))}
          </div>
        </div>

        {/* Features */}
        <div className="grid grid-cols-1 tablet:grid-cols-3 gap-6 phone:gap-8 mb-8">
          {[
            { icon: DollarSign, title: 'Spend Forecasting', desc: 'Predict future spend and revenue with AI' },
            { icon: BarChart3, title: 'Outcome Simulation', desc: 'Model different scenarios and outcomes' },
            { icon: Zap, title: 'Growth Recommendations', desc: 'AI-powered growth strategies and insights' },
          ].map((feature, idx) => {
            const Icon = feature.icon;
            return (
              <div key={idx} className="card-premium card-3d">
                <div className="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mb-4">
                  <Icon className="w-6 h-6 text-white" />
                </div>
                <h3 className="text-lg font-bold text-white mb-2">{feature.title}</h3>
                <p className="text-gray-400 text-sm">{feature.desc}</p>
              </div>
            );
          })}
        </div>

        {/* Strategy Chart */}
        <div className="card-premium card-3d">
          <h3 className="text-xl font-bold text-white mb-4">Growth Strategy Overview</h3>
          <div className="h-64 bg-gray-800/50 rounded-lg flex items-center justify-center border border-purple-500/20">
            <TrendingUp className="w-16 h-16 text-gray-600 opacity-50" />
          </div>
        </div>
      </main>
    </div>
  );
}
