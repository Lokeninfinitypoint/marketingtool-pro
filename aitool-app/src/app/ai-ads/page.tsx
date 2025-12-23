import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import AnimatedScreen from '@/components/AnimatedScreen';
import Separator from '@/components/Separator';
import { Sparkles, Wand2, Image, Library, Zap, Star, Filter, Search, Download, Heart } from 'lucide-react';

export default function AIAdsPage() {
  const adExamples = [
    { id: 1, image: '/api/placeholder/400/300', title: 'Luxury Watch', category: 'Fashion' },
    { id: 2, image: '/api/placeholder/400/300', title: 'Beauty Products', category: 'Beauty' },
    { id: 3, image: '/api/placeholder/400/300', title: 'Urban Escape', category: 'Travel' },
    { id: 4, image: '/api/placeholder/400/300', title: 'Tech Innovation', category: 'Technology' },
    { id: 5, image: '/api/placeholder/400/300', title: 'Fitness Journey', category: 'Health' },
    { id: 6, image: '/api/placeholder/400/300', title: 'Food Delight', category: 'Food' },
  ];

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
                <span className="text-white">AI Ads</span>{' '}
                <span className="text-gradient-vibrant">Generator</span>
              </h1>
              <p className="text-lg phone:text-xl text-gray-300 max-w-3xl mx-auto px-4 mb-8">
                Generate winning ad creatives with AI. No endless testing, just results.
              </p>
              <div className="flex flex-col phone:flex-row gap-4 justify-center">
                <AnimatedButton href="/free-trial" size="large">
                  Try Free (No CC)
                </AnimatedButton>
                <AnimatedButton href="/app/dashboard" variant="secondary" size="large">
                  Generate Ads with AI
                </AnimatedButton>
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        {/* Ad Library Section */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="mb-8 phone:mb-12">
              <div className="flex items-center justify-between mb-6">
                <div>
                  <h2 className="text-3xl phone:text-4xl font-bold mb-2 text-white">
                    Ad <span className="text-gradient-vibrant">Library</span>
                  </h2>
                  <p className="text-gray-400">
                    Spy on winning ads in your industry – then use AI to adapt them
                  </p>
                </div>
                <div className="flex items-center gap-2">
                  <button className="px-4 py-2 bg-gray-800 border border-purple-500/20 rounded-lg text-white text-sm hover:border-purple-500/50 transition-colors">
                    <Filter className="w-4 h-4 inline mr-2" />
                    Filter
                  </button>
                  <button className="px-4 py-2 bg-gray-800 border border-purple-500/20 rounded-lg text-white text-sm hover:border-purple-500/50 transition-colors">
                    <Search className="w-4 h-4 inline mr-2" />
                    Search
                  </button>
                </div>
              </div>

              {/* Filter Tabs */}
              <div className="flex flex-wrap gap-2 mb-6">
                {['For You', 'All Creatives', 'Ad Types', 'Ad Platform'].map((tab) => (
                  <button
                    key={tab}
                    className="px-4 py-2 bg-gray-800/50 border border-purple-500/20 rounded-lg text-white text-sm hover:border-purple-500/50 transition-colors"
                  >
                    {tab}
                  </button>
                ))}
              </div>

              {/* Ad Grid */}
              <div className="grid grid-cols-2 tablet:grid-cols-3 desktop:grid-cols-4 gap-4 phone:gap-6">
                {adExamples.map((ad) => (
                  <div key={ad.id} className="card-premium card-3d group relative">
                    <div className="aspect-square bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-lg mb-3 flex items-center justify-center overflow-hidden">
                      <Image className="w-16 h-16 text-purple-400 opacity-50" />
                    </div>
                    <div className="flex items-start justify-between">
                      <div>
                        <h3 className="text-sm font-semibold text-white mb-1">{ad.title}</h3>
                        <p className="text-xs text-gray-400">{ad.category}</p>
                      </div>
                      <button className="p-2 text-gray-400 hover:text-purple-400 transition-colors">
                        <Heart className="w-4 h-4" />
                      </button>
                    </div>
                    <div className="mt-3 flex gap-2">
                      <button className="flex-1 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 rounded text-white text-xs font-medium transition-colors">
                        Adapt with AI
                      </button>
                      <button className="p-1.5 bg-gray-800 hover:bg-gray-700 rounded text-gray-400 hover:text-white transition-colors">
                        <Download className="w-4 h-4" />
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* AI Creative Generator Section */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="max-w-5xl mx-auto">
              <div className="text-center mb-8 phone:mb-12">
                <Wand2 className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-4 animate-float-3d" />
                <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4">
                  AI Creative <span className="text-gradient-vibrant">Generator</span>
                </h2>
                <p className="text-lg phone:text-xl text-gray-400 max-w-2xl mx-auto px-4">
                  Generate new ad creatives with AI. Customize text, adapt styles, create variations instantly.
                </p>
              </div>

              <AnimatedScreen>
                <div className="bg-gray-900 rounded-2xl phone:rounded-3xl p-6 phone:p-8 border border-purple-500/20">
                  <div className="bg-gray-800 rounded-xl p-6 mb-6">
                    <div className="flex items-center justify-between mb-4">
                      <h3 className="text-lg font-semibold text-white">New Creative Idea</h3>
                      <button className="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white text-sm font-medium transition-colors">
                        Generate
                      </button>
                    </div>
                    <div className="space-y-4">
                      <div>
                        <label className="block text-sm text-gray-400 mb-2">Custom Text</label>
                        <input
                          type="text"
                          placeholder="Enter your ad copy..."
                          className="w-full px-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors"
                        />
                      </div>
                      <div className="aspect-video bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-lg flex items-center justify-center border border-purple-500/20">
                        <div className="text-center">
                          <Sparkles className="w-12 h-12 text-purple-400 mx-auto mb-2 opacity-50" />
                          <p className="text-gray-400 text-sm">Generated ad preview will appear here</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </AnimatedScreen>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Key Messaging Section */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="max-w-4xl mx-auto text-center">
              <h2 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-6 text-white">
                Built for People Who Need Results Yesterday
              </h2>
              <p className="text-xl phone:text-2xl text-gray-300 mb-8">
                You're not here to improve creative skills, you're here to win.
              </p>
              <AnimatedButton href="/free-trial" size="large">
                Try Free (No CC)
              </AnimatedButton>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* AI Image Editor Section */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="max-w-5xl mx-auto">
              <div className="text-center mb-8 phone:mb-12">
                <Image className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-4 animate-float-3d" />
                <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4">
                  AI Image <span className="text-gradient-vibrant">Editor</span>
                </h2>
                <p className="text-lg phone:text-xl text-gray-400 max-w-2xl mx-auto px-4">
                  The Only Ad Tool That Doesn't Waste Your Time. No endless ad testing, just results.
                </p>
              </div>

              <AnimatedScreen>
                <div className="bg-gray-900 rounded-2xl phone:rounded-3xl p-6 phone:p-8 border border-purple-500/20">
                  <div className="bg-gray-800 rounded-xl p-6">
                    <div className="flex items-center justify-between mb-4">
                      <h3 className="text-lg font-semibold text-white">Edit Your Ad Creative</h3>
                      <div className="flex gap-2">
                        <button className="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white text-sm font-medium transition-colors">
                          Reset
                        </button>
                        <button className="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-white text-sm font-medium transition-colors">
                          Save
                        </button>
                      </div>
                    </div>
                    <div className="aspect-video bg-gradient-to-br from-green-500/20 to-blue-500/20 rounded-lg flex items-center justify-center border border-purple-500/20 mb-4">
                      <div className="text-center">
                        <div className="text-3xl font-bold text-white mb-2">Ride the Revolution</div>
                        <p className="text-gray-300">Your ad creative preview</p>
                      </div>
                    </div>
                    <div className="grid grid-cols-2 tablet:grid-cols-4 gap-4">
                      {['Text', 'Image', 'Color', 'Style'].map((tool) => (
                        <button
                          key={tool}
                          className="px-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white text-sm hover:border-purple-500/50 transition-colors"
                        >
                          {tool}
                        </button>
                      ))}
                    </div>
                  </div>
                </div>
              </AnimatedScreen>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* CTA Section */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="card-premium card-3d max-w-4xl mx-auto text-center">
              <h2 className="text-3xl phone:text-4xl tablet:text-5xl font-bold mb-4 text-white">
                Launch Your AI Ads in a Few Clicks
              </h2>
              <p className="text-lg phone:text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                Generate, edit, and launch winning ad creatives with AI. No design skills needed.
              </p>
              <div className="flex flex-col phone:flex-row gap-4 justify-center">
                <AnimatedButton href="/free-trial" size="large">
                  Generate Ads with AI
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
