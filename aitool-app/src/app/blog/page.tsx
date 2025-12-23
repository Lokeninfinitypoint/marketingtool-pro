import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Calendar, User, ArrowRight, Tag, Clock } from 'lucide-react';

export default function BlogPage() {
  const blogCategories = [
    { name: 'All Posts', href: '/blog', active: true, count: 24 },
    { name: 'PPC', href: '/blog/category/ppc', active: false, count: 8 },
    { name: 'SEO', href: '/blog/category/seo', active: false, count: 6 },
    { name: 'Writing', href: '/blog/category/writing', active: false, count: 5 },
    { name: 'Tools', href: '/blog/category/tools', active: false, count: 5 },
  ];

  const blogPosts = [
    {
      id: 1,
      title: 'How AI is Transforming PPC Campaign Management in 2025',
      excerpt: 'Discover how artificial intelligence is revolutionizing pay-per-click advertising and maximizing ROAS. Learn about the latest AI tools and strategies that top marketers are using.',
      category: 'PPC',
      author: 'AI Team',
      date: 'December 20, 2025',
      readTime: '8 min read',
      featured: true,
    },
    {
      id: 2,
      title: '10 Essential SEO Strategies for 2025: A Complete Guide',
      excerpt: 'Learn the latest SEO techniques that will help your website rank higher in search results. From technical SEO to content optimization, we cover everything you need to know.',
      category: 'SEO',
      author: 'SEO Experts',
      date: 'December 19, 2025',
      readTime: '12 min read',
      featured: false,
    },
    {
      id: 3,
      title: 'Writing Compelling Ad Copy That Converts: The Ultimate Guide',
      excerpt: 'Master the art of writing ad copy that captures attention and drives conversions. Learn proven frameworks and techniques used by top-performing advertisers.',
      category: 'Writing',
      author: 'Content Team',
      date: 'December 18, 2025',
      readTime: '10 min read',
      featured: false,
    },
    {
      id: 4,
      title: 'Top 10 Marketing Tools Every Marketer Needs in 2025',
      excerpt: 'Explore the essential marketing tools that can streamline your workflow and boost productivity. From automation platforms to analytics tools, discover what you need.',
      category: 'Tools',
      author: 'Tool Experts',
      date: 'December 17, 2025',
      readTime: '15 min read',
      featured: false,
    },
    {
      id: 5,
      title: 'Meta Ads vs Google Ads: Which Platform Should You Choose?',
      excerpt: 'A comprehensive comparison of Meta Ads and Google Ads. Learn about the strengths and weaknesses of each platform and when to use them for maximum ROI.',
      category: 'PPC',
      author: 'PPC Specialists',
      date: 'December 16, 2025',
      readTime: '9 min read',
      featured: false,
    },
    {
      id: 6,
      title: 'Advanced Bid Management Strategies for Maximum ROAS',
      excerpt: 'Learn advanced bid management techniques that can help you maximize your return on ad spend. From manual bidding to automated strategies, we cover it all.',
      category: 'PPC',
      author: 'Bid Management Team',
      date: 'December 15, 2025',
      readTime: '11 min read',
      featured: false,
    },
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        {/* Blog Header - Rich Content */}
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="absolute top-1/4 left-1/4 w-64 phone:w-96 h-64 phone:h-96 bg-purple-500/10 rounded-full blur-3xl animate-float-3d"></div>
          
          <div className="relative container-responsive text-center z-10">
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">Blog</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Latest insights, tips, and strategies for PPC, SEO, and digital marketing. 
              Stay ahead with expert advice and industry trends.
            </p>
            
            <div className="rich-content text-left max-w-3xl mx-auto px-4">
              <p className="text-gray-400 leading-relaxed">
                Welcome to the AITool.Software blog, your go-to resource for everything related to 
                pay-per-click advertising, search engine optimization, content marketing, and digital 
                marketing tools. Our team of experts shares actionable insights, case studies, and 
                best practices to help you succeed in your marketing campaigns.
              </p>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        {/* Blog Categories - Responsive */}
        <section className="py-6 phone:py-8 bg-gray-900/50 border-b border-purple-500/20 sticky top-16 phone:top-20 z-40 backdrop-blur-xl">
          <div className="container-responsive">
            <div className="flex flex-wrap justify-center gap-3 phone:gap-4">
              {blogCategories.map((category) => (
                <Link
                  key={category.name}
                  href={category.href}
                  className={`px-4 phone:px-6 py-2 rounded-lg font-medium transition-all text-sm phone:text-base card-3d ${
                    category.active
                      ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg shadow-purple-500/50'
                      : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white'
                  }`}
                >
                  {category.name} <span className="text-xs opacity-75">({category.count})</span>
                </Link>
              ))}
            </div>
          </div>
        </section>

        {/* Featured Post - Rich Content */}
        {blogPosts.find(p => p.featured) && (
          <section className="py-8 phone:py-12 tablet:py-16 bg-gray-900/30">
            <div className="container-responsive">
              {blogPosts.filter(p => p.featured).map((post) => (
                <Link
                  key={post.id}
                  href={`/blog/post/${post.id}`}
                  className="block card-premium card-3d group overflow-hidden"
                >
                  <div className="grid grid-cols-1 desktop:grid-cols-2 gap-6 phone:gap-8">
                    <div className="aspect-video desktop:aspect-auto bg-gradient-to-br from-purple-600 to-pink-600 relative overflow-hidden rounded-lg">
                      <div className="absolute inset-0 flex items-center justify-center">
                        <span className="text-6xl phone:text-8xl opacity-30">📝</span>
                      </div>
                      <div className="absolute top-4 left-4">
                        <span className="px-3 py-1 bg-purple-900/90 backdrop-blur-sm rounded-full text-xs phone:text-sm font-medium text-white">
                          Featured
                        </span>
                      </div>
                    </div>
                    
                    <div className="p-6 phone:p-8 flex flex-col justify-center">
                      <div className="flex items-center gap-3 mb-3">
                        <span className="px-3 py-1 bg-purple-500/20 border border-purple-500/30 rounded-full text-xs phone:text-sm font-medium text-purple-300">
                          {post.category}
                        </span>
                        <span className="text-xs phone:text-sm text-gray-400">{post.readTime}</span>
                      </div>
                      
                      <h2 className="text-2xl phone:text-3xl tablet:text-4xl font-bold mb-3 phone:mb-4 text-white group-hover:text-purple-300 transition-colors">
                        {post.title}
                      </h2>
                      
                      <p className="text-gray-400 mb-6 phone:mb-8 leading-relaxed text-sm phone:text-base">
                        {post.excerpt}
                      </p>
                      
                      <div className="flex items-center justify-between">
                        <div className="flex items-center gap-4 text-sm text-gray-500">
                          <div className="flex items-center gap-2">
                            <User className="w-4 h-4" />
                            <span>{post.author}</span>
                          </div>
                          <div className="flex items-center gap-2">
                            <Calendar className="w-4 h-4" />
                            <span>{post.date}</span>
                          </div>
                        </div>
                        
                        <div className="flex items-center gap-2 text-purple-400 font-medium group-hover:gap-3 transition-all">
                          Read More <ArrowRight className="w-5 h-5" />
                        </div>
                      </div>
                    </div>
                  </div>
                </Link>
              ))}
            </div>
          </section>
        )}

        {/* Blog Posts Grid - Rich Content with 3D */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <h2 className="text-2xl phone:text-3xl tablet:text-4xl font-bold mb-8 phone:mb-12 text-center">
              Latest <span className="text-gradient-vibrant">Articles</span>
            </h2>
            
            <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-3 gap-6 phone:gap-8">
              {blogPosts.filter(p => !p.featured).map((post) => (
                <article
                  key={post.id}
                  className="card-premium card-3d group overflow-hidden"
                >
                  <Link href={`/blog/post/${post.id}`}>
                    <div className="aspect-video bg-gradient-to-br from-purple-600 to-pink-600 relative overflow-hidden rounded-lg mb-4 phone:mb-6">
                      <div className="absolute inset-0 flex items-center justify-center">
                        <span className="text-4xl phone:text-5xl opacity-50">📝</span>
                      </div>
                      <div className="absolute top-4 left-4">
                        <span className="px-3 py-1 bg-purple-900/80 backdrop-blur-sm rounded-full text-xs font-medium text-white">
                          {post.category}
                        </span>
                      </div>
                    </div>
                    
                    <div className="p-4 phone:p-6">
                      <div className="flex items-center gap-3 mb-3 text-xs phone:text-sm text-gray-500">
                        <div className="flex items-center gap-1.5">
                          <Clock className="w-3 h-3" />
                          <span>{post.readTime}</span>
                        </div>
                        <span>•</span>
                        <span>{post.date}</span>
                      </div>
                      
                      <h2 className="text-xl phone:text-2xl font-bold mb-3 text-white group-hover:text-purple-300 transition-colors line-clamp-2">
                        {post.title}
                      </h2>
                      
                      <p className="text-gray-400 mb-4 phone:mb-6 text-sm phone:text-base line-clamp-3 leading-relaxed">
                        {post.excerpt}
                      </p>
                      
                      <div className="flex items-center justify-between">
                        <div className="flex items-center gap-2 text-xs phone:text-sm text-gray-500">
                          <User className="w-4 h-4" />
                          <span>{post.author}</span>
                        </div>
                        
                        <div className="flex items-center gap-2 text-purple-400 font-medium text-sm group-hover:gap-3 transition-all">
                          Read <ArrowRight className="w-4 h-4" />
                        </div>
                      </div>
                    </div>
                  </Link>
                </article>
              ))}
            </div>
          </div>
        </section>

        {/* Newsletter CTA - Rich Content */}
        <section className="py-12 phone:py-16 tablet:py-20 relative">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive">
            <div className="card-premium card-3d max-w-3xl mx-auto px-6 phone:px-8 py-12 phone:py-16 text-center">
              <h2 className="text-3xl phone:text-4xl font-bold mb-4 phone:mb-6 text-white">
                Stay Updated
              </h2>
              <p className="text-lg phone:text-xl text-gray-300 mb-8 phone:mb-10">
                Subscribe to our newsletter and get the latest marketing insights delivered to your inbox
              </p>
              <div className="flex flex-col phone:flex-row gap-4 max-w-md mx-auto">
                <input
                  type="email"
                  placeholder="Enter your email"
                  className="flex-1 px-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 text-sm phone:text-base"
                />
                <AnimatedButton href="/free-trial">
                  Subscribe
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
