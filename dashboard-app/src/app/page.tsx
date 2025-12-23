'use client';

import React from 'react';
import DashboardLayout from '@/components/DashboardLayout';
import Link from 'next/link';
import { Sparkles, TrendingUp, Zap, Library, BarChart3, Target, Users, ArrowRight, Award, Clock, DollarSign } from 'lucide-react';

export default function DashboardPage() {
  return (
    <DashboardLayout>
      {/* Welcome Section */}
      <div className="mb-8">
        <div className="bg-gradient-to-br from-purple-600 via-purple-500 to-pink-500 rounded-2xl p-8 text-white card-glow">
          <div className="flex items-start justify-between">
            <div className="flex-1">
              <h1 className="text-4xl font-bold mb-3">
                Welcome to MarketingTool.Pro
              </h1>
              <p className="text-purple-100 text-lg mb-6 max-w-2xl">
                Your AI-powered marketing command center. Access 418+ marketing tools, generate content, optimize campaigns, and scale your business with intelligent automation.
              </p>
              <div className="flex gap-4">
                <Link
                  href="/ai/content-generator"
                  className="px-6 py-3 bg-white text-purple-600 rounded-lg font-semibold hover:shadow-lg transition-all"
                >
                  Generate Content
                </Link>
                <Link
                  href="/tools"
                  className="px-6 py-3 bg-purple-700/50 backdrop-blur-sm text-white rounded-lg font-semibold hover:bg-purple-700 transition-all"
                >
                  Browse 418 Tools
                </Link>
              </div>
            </div>
            <div className="hidden lg:block">
              <div className="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                <Sparkles className="w-12 h-12 text-white" />
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Quick Stats */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div className="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all">
          <div className="flex items-center justify-between mb-4">
            <div className="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
              <Library className="w-6 h-6 text-white" />
            </div>
            <span className="text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full">
              Active
            </span>
          </div>
          <div className="text-3xl font-bold text-gray-900 mb-1">418</div>
          <div className="text-sm text-gray-600">Marketing Tools</div>
        </div>

        <div className="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all">
          <div className="flex items-center justify-between mb-4">
            <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
              <Target className="w-6 h-6 text-white" />
            </div>
            <span className="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
              Running
            </span>
          </div>
          <div className="text-3xl font-bold text-gray-900 mb-1">24</div>
          <div className="text-sm text-gray-600">Active Campaigns</div>
        </div>

        <div className="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all">
          <div className="flex items-center justify-between mb-4">
            <div className="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
              <TrendingUp className="w-6 h-6 text-white" />
            </div>
            <span className="text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full">
              +15%
            </span>
          </div>
          <div className="text-3xl font-bold text-gray-900 mb-1">3.2x</div>
          <div className="text-sm text-gray-600">Average ROAS</div>
        </div>

        <div className="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-all">
          <div className="flex items-center justify-between mb-4">
            <div className="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg flex items-center justify-center">
              <DollarSign className="w-6 h-6 text-white" />
            </div>
            <span className="text-sm font-medium text-orange-600 bg-orange-50 px-3 py-1 rounded-full">
              This Month
            </span>
          </div>
          <div className="text-3xl font-bold text-gray-900 mb-1">$45K</div>
          <div className="text-sm text-gray-600">Ad Spend</div>
        </div>
      </div>

      {/* AI Features Grid */}
      <div className="mb-8">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-2xl font-bold text-gray-900">AI-Powered Features</h2>
          <span className="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white text-sm font-semibold rounded-full">
            Powered by GPT-4 & Claude
          </span>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {/* AI Content Generator */}
          <Link href="/ai/content-generator" className="group">
            <div className="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border-gradient-animated">
              <div className="flex items-start justify-between mb-4">
                <div className="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                  <Sparkles className="w-7 h-7 text-white" />
                </div>
                <span className="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                  AI
                </span>
              </div>
              <h3 className="text-xl font-bold text-gray-900 mb-2 group-hover:text-gradient">
                AI Content Generator
              </h3>
              <p className="text-gray-600 text-sm mb-4">
                Generate high-converting ad copy, blog posts, and social media content in seconds with AI.
              </p>
              <div className="flex items-center text-purple-600 font-semibold text-sm group-hover:gap-3 gap-2 transition-all">
                Generate Now <ArrowRight className="w-4 h-4" />
              </div>
            </div>
          </Link>

          {/* AI Campaign Optimizer */}
          <Link href="/ai/campaign-optimizer" className="group">
            <div className="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border-gradient-animated">
              <div className="flex items-start justify-between mb-4">
                <div className="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                  <TrendingUp className="w-7 h-7 text-white" />
                </div>
                <span className="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                  AI
                </span>
              </div>
              <h3 className="text-xl font-bold text-gray-900 mb-2 group-hover:text-gradient">
                AI Campaign Optimizer
              </h3>
              <p className="text-gray-600 text-sm mb-4">
                Optimize your campaigns with AI-powered insights and recommendations for better ROAS.
              </p>
              <div className="flex items-center text-blue-600 font-semibold text-sm group-hover:gap-3 gap-2 transition-all">
                Optimize Now <ArrowRight className="w-4 h-4" />
              </div>
            </div>
          </Link>

          {/* AI Chatbot */}
          <Link href="/ai/chatbot" className="group">
            <div className="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border-gradient-animated">
              <div className="flex items-start justify-between mb-4">
                <div className="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                  <Zap className="w-7 h-7 text-white" />
                </div>
                <span className="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                  AI
                </span>
              </div>
              <h3 className="text-xl font-bold text-gray-900 mb-2 group-hover:text-gradient">
                AI Marketing Chatbot
              </h3>
              <p className="text-gray-600 text-sm mb-4">
                Ask questions, get strategies, and receive instant marketing advice from your AI assistant.
              </p>
              <div className="flex items-center text-green-600 font-semibold text-sm group-hover:gap-3 gap-2 transition-all">
                Start Chat <ArrowRight className="w-4 h-4" />
              </div>
            </div>
          </Link>
        </div>
      </div>

      {/* Quick Actions & Recent Activity */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Quick Actions */}
        <div className="bg-white rounded-xl border border-gray-200 p-6">
          <h3 className="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <Award className="w-5 h-5 text-purple-600" />
            Quick Actions
          </h3>
          <div className="space-y-3">
            <Link
              href="/tools"
              className="flex items-center justify-between p-4 bg-gray-50 hover:bg-purple-50 rounded-lg transition-colors group"
            >
              <div className="flex items-center gap-3">
                <Library className="w-5 h-5 text-purple-600" />
                <span className="font-medium text-gray-900">Browse All Tools</span>
              </div>
              <span className="text-sm text-purple-600 font-semibold group-hover:gap-2 flex items-center gap-1">
                418 Tools <ArrowRight className="w-4 h-4" />
              </span>
            </Link>

            <Link
              href="/campaigns"
              className="flex items-center justify-between p-4 bg-gray-50 hover:bg-blue-50 rounded-lg transition-colors group"
            >
              <div className="flex items-center gap-3">
                <Target className="w-5 h-5 text-blue-600" />
                <span className="font-medium text-gray-900">View Campaigns</span>
              </div>
              <ArrowRight className="w-4 h-4 text-blue-600 group-hover:translate-x-1 transition-transform" />
            </Link>

            <Link
              href="/analytics"
              className="flex items-center justify-between p-4 bg-gray-50 hover:bg-green-50 rounded-lg transition-colors group"
            >
              <div className="flex items-center gap-3">
                <BarChart3 className="w-5 h-5 text-green-600" />
                <span className="font-medium text-gray-900">View Analytics</span>
              </div>
              <ArrowRight className="w-4 h-4 text-green-600 group-hover:translate-x-1 transition-transform" />
            </Link>

            <Link
              href="/audience"
              className="flex items-center justify-between p-4 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors group"
            >
              <div className="flex items-center gap-3">
                <Users className="w-5 h-5 text-orange-600" />
                <span className="font-medium text-gray-900">Manage Audience</span>
              </div>
              <ArrowRight className="w-4 h-4 text-orange-600 group-hover:translate-x-1 transition-transform" />
            </Link>
          </div>
        </div>

        {/* Recent Activity */}
        <div className="bg-white rounded-xl border border-gray-200 p-6">
          <h3 className="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <Clock className="w-5 h-5 text-purple-600" />
            Recent Activity
          </h3>
          <div className="space-y-4">
            <div className="flex items-start gap-3 pb-4 border-b border-gray-100">
              <div className="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <Sparkles className="w-5 h-5 text-white" />
              </div>
              <div className="flex-1 min-w-0">
                <p className="font-medium text-gray-900 text-sm">AI Content Generated</p>
                <p className="text-xs text-gray-500 mt-1">Created 5 ad variations for Summer Campaign</p>
                <span className="text-xs text-gray-400 mt-1 block">2 minutes ago</span>
              </div>
            </div>

            <div className="flex items-start gap-3 pb-4 border-b border-gray-100">
              <div className="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <TrendingUp className="w-5 h-5 text-white" />
              </div>
              <div className="flex-1 min-w-0">
                <p className="font-medium text-gray-900 text-sm">Campaign Optimized</p>
                <p className="text-xs text-gray-500 mt-1">Summer Sale campaign ROAS improved by 12%</p>
                <span className="text-xs text-gray-400 mt-1 block">15 minutes ago</span>
              </div>
            </div>

            <div className="flex items-start gap-3 pb-4 border-b border-gray-100">
              <div className="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <DollarSign className="w-5 h-5 text-white" />
              </div>
              <div className="flex-1 min-w-0">
                <p className="font-medium text-gray-900 text-sm">Credits Added</p>
                <p className="text-xs text-gray-500 mt-1">150 AI credits added to your account</p>
                <span className="text-xs text-gray-400 mt-1 block">1 hour ago</span>
              </div>
            </div>

            <div className="flex items-start gap-3">
              <div className="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <Target className="w-5 h-5 text-white" />
              </div>
              <div className="flex-1 min-w-0">
                <p className="font-medium text-gray-900 text-sm">New Campaign Launched</p>
                <p className="text-xs text-gray-500 mt-1">Fall Collection campaign started successfully</p>
                <span className="text-xs text-gray-400 mt-1 block">2 hours ago</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
