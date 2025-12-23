import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import AnimatedScreen from '@/components/AnimatedScreen';
import Separator from '@/components/Separator';
import { Shield, TrendingUp, Zap, BarChart3, CheckCircle, AlertTriangle, Play, Pause, Clock, ArrowUp, ArrowDown, Star } from 'lucide-react';

export default function OptimizationPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        {/* Hero Section */}
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive z-10">
            <div className="text-center mb-8 phone:mb-12">
              <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
                <span className="text-white">Campaign</span>{' '}
                <span className="text-gradient-vibrant">Optimization</span>
              </h1>
              <p className="text-lg phone:text-xl text-gray-300 max-w-3xl mx-auto px-4 mb-8">
                Protect your budget 24/7 with automated rules. Boost ROI by fixing broken data pipelines.
              </p>
              <AnimatedButton href="/free-trial" size="large">
                Try Free (30 Day Trial)
              </AnimatedButton>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        {/* Data Quality Section */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4">
                Do You Trust Your <span className="text-gradient-vibrant">Data?</span>
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 max-w-2xl mx-auto px-4">
                Boost ROI by fixing broken data pipelines & optimizing Meta Conversions API
              </p>
            </div>

            <AnimatedScreen>
              <div className="card-premium card-3d max-w-6xl mx-auto">
                <div className="mb-6">
                  <div className="flex items-center justify-between mb-4">
                    <h3 className="text-xl font-bold text-white">Meta Conversions API Gateway</h3>
                    <div className="flex gap-2">
                      <button className="px-4 py-2 bg-gray-800 border border-purple-500/20 rounded-lg text-white text-sm hover:border-purple-500/50 transition-colors">
                        Manage & Access
                      </button>
                      <button className="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white text-sm font-medium transition-colors">
                        Add Account
                      </button>
                    </div>
                  </div>
                </div>

                {/* Purchase Metrics */}
                <div className="grid grid-cols-1 tablet:grid-cols-2 gap-6 mb-8">
                  <div className="bg-gray-800/50 rounded-xl p-6 border border-purple-500/20">
                    <div className="text-4xl font-bold text-white mb-2">529</div>
                    <div className="text-gray-400 text-sm">Purchases (Before)</div>
                  </div>
                  <div className="bg-gradient-to-br from-green-500/20 to-green-600/20 rounded-xl p-6 border border-green-500/30">
                    <div className="text-4xl font-bold text-green-400 mb-2">1064</div>
                    <div className="text-gray-300 text-sm">Purchases (After)</div>
                    <div className="text-green-400 text-xs mt-2">+101% Improvement</div>
                  </div>
                </div>

                {/* Quality Scores */}
                <div className="grid grid-cols-2 gap-4 mb-8">
                  <div className="bg-gray-800/50 rounded-lg p-4 border border-purple-500/20">
                    <div className="flex items-center gap-2 mb-2">
                      <CheckCircle className="w-5 h-5 text-green-400" />
                      <span className="text-sm text-gray-400">Data Quality</span>
                    </div>
                    <div className="text-2xl font-bold text-white">100%</div>
                  </div>
                  <div className="bg-gray-800/50 rounded-lg p-4 border border-purple-500/20">
                    <div className="flex items-center gap-2 mb-2">
                      <CheckCircle className="w-5 h-5 text-green-400" />
                      <span className="text-sm text-gray-400">Event Match Quality</span>
                    </div>
                    <div className="text-2xl font-bold text-white">100%</div>
                  </div>
                </div>

                {/* Event Chart Placeholder */}
                <div className="bg-gray-800/50 rounded-lg p-6 mb-6 border border-purple-500/20">
                  <h4 className="text-sm text-gray-400 mb-4">Events captured with Meta Conversions API Gateway</h4>
                  <div className="h-48 bg-gray-900/50 rounded-lg flex items-end justify-around p-4">
                    {[40, 60, 45, 80, 70, 90, 100].map((height, idx) => (
                      <div key={idx} className="flex flex-col items-center gap-2">
                        <div
                          className="w-8 bg-gradient-to-t from-purple-500 to-green-500 rounded-t"
                          style={{ height: `${height}%` }}
                        ></div>
                        <span className="text-xs text-gray-500">Day {idx + 1}</span>
                      </div>
                    ))}
                  </div>
                </div>

                {/* Benefits */}
                <div className="grid grid-cols-1 tablet:grid-cols-2 gap-4">
                  {[
                    'Better and clear results',
                    'Alignment between Meta and now',
                    'Growth without guesswork',
                    'Meta\'s bot works for you again',
                  ].map((benefit, idx) => (
                    <div key={idx} className="flex items-start gap-3 p-4 bg-gray-800/30 rounded-lg">
                      <CheckCircle className="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5" />
                      <span className="text-gray-300">{benefit}</span>
                    </div>
                  ))}
                </div>

                <div className="mt-6 text-center">
                  <AnimatedButton href="/free-trial">
                    Get Started
                  </AnimatedButton>
                </div>
              </div>
            </AnimatedScreen>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Automation Section */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="text-center mb-8 phone:mb-12">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4">
                No More Wasted <span className="text-gradient-vibrant">Ad Spend</span>
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 max-w-2xl mx-auto px-4">
                Protect your budget 24/7 with automated rules created by the pros
              </p>
            </div>

            <AnimatedScreen>
              <div className="card-premium card-3d max-w-6xl mx-auto">
                <div className="mb-6">
                  <h3 className="text-xl font-bold text-white mb-2">What your automation did for you</h3>
                  <p className="text-gray-400 text-sm">Last 7 days performance</p>
                </div>

                {/* Performance Metrics */}
                <div className="grid grid-cols-2 tablet:grid-cols-4 gap-4 mb-8">
                  {[
                    { label: 'Spend', value: '$12.5K', change: '+15%', positive: true },
                    { label: 'ROAS', value: '30%', change: '+5%', positive: true },
                    { label: 'Purchases', value: '49', change: '+12%', positive: true },
                    { label: 'Budget Saved', value: '$2.1K', change: '+8%', positive: true },
                  ].map((metric, idx) => (
                    <div key={idx} className="bg-gray-800/50 rounded-lg p-4 border border-purple-500/20">
                      <div className="text-xs text-gray-400 mb-1">{metric.label}</div>
                      <div className="text-2xl font-bold text-white mb-1">{metric.value}</div>
                      <div className={`text-xs ${metric.positive ? 'text-green-400' : 'text-red-400'}`}>
                        {metric.change}
                      </div>
                    </div>
                  ))}
                </div>

                {/* Automation Rules */}
                <div className="mb-6">
                  <h4 className="text-sm text-gray-400 mb-4">Automation Rules Status</h4>
                  <div className="grid grid-cols-1 tablet:grid-cols-3 gap-4">
                    {[
                      { status: 'Paused', count: 1, icon: Pause, color: 'yellow' },
                      { status: 'Active', count: 2, icon: Play, color: 'green' },
                      { status: 'Pending', count: 0, icon: Clock, color: 'gray' },
                    ].map((rule, idx) => {
                      const Icon = rule.icon;
                      return (
                        <div key={idx} className="bg-gray-800/50 rounded-lg p-4 border border-purple-500/20">
                          <div className="flex items-center justify-between mb-3">
                            <div className={`w-10 h-10 rounded-lg bg-${rule.color}-500/20 flex items-center justify-center`}>
                              <Icon className={`w-5 h-5 text-${rule.color}-400`} />
                            </div>
                            <span className="text-2xl font-bold text-white">{rule.count}</span>
                          </div>
                          <div className="text-sm text-gray-400">{rule.status} rule</div>
                          {rule.status === 'Active' && (
                            <div className="mt-2 text-xs text-gray-500">
                              <div>• Decrease budget</div>
                              <div>• Increase ROAS</div>
                            </div>
                          )}
                        </div>
                      );
                    })}
                  </div>
                </div>

                {/* Benefits */}
                <div className="grid grid-cols-1 tablet:grid-cols-3 gap-4 mb-6">
                  {[
                    '100% Protection with real-time triggers',
                    'Don\'t get stuck in front of Meta Ads Manager',
                    'Go beyond human limits',
                  ].map((benefit, idx) => (
                    <div key={idx} className="flex items-start gap-3 p-4 bg-gray-800/30 rounded-lg">
                      <Shield className="w-5 h-5 text-purple-400 flex-shrink-0 mt-0.5" />
                      <span className="text-gray-300 text-sm">{benefit}</span>
                    </div>
                  ))}
                </div>

                {/* Testimonial */}
                <div className="bg-gray-800/30 rounded-lg p-6 border border-purple-500/20">
                  <div className="flex items-start gap-4">
                    <div className="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                      <span className="text-white font-bold">KV</span>
                    </div>
                    <div>
                      <div className="flex items-center gap-2 mb-2">
                        <span className="font-semibold text-white">Katya Vlasova</span>
                        <div className="flex">
                          {[...Array(5)].map((_, i) => (
                            <Star key={i} className="w-4 h-4 text-yellow-400 fill-current" />
                          ))}
                        </div>
                      </div>
                      <p className="text-gray-300 text-sm leading-relaxed">
                        "We saved up to 20 hours per month on managing our Meta Ads Manager. This software... There was a time I used to manage my Ads manager myself - it's completely scary."
                      </p>
                    </div>
                  </div>
                </div>

                <div className="mt-6 text-center">
                  <AnimatedButton href="/free-trial" size="large">
                    Try Free (30 Day Trial)
                  </AnimatedButton>
                </div>
              </div>
            </AnimatedScreen>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* CTA Section */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="card-premium card-3d max-w-4xl mx-auto text-center">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4 text-white">
                Start Optimizing Today
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                Fix your data pipelines and automate your ad spend. Get results without the guesswork.
              </p>
              <div className="flex flex-col phone:flex-row gap-4 justify-center">
                <AnimatedButton href="/free-trial" size="large">
                  Get Started Free
                </AnimatedButton>
                <AnimatedButton href="/app/dashboard" variant="secondary" size="large">
                  View Dashboard
                </AnimatedButton>
              </div>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
