import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { Calendar, User, ArrowRight, Tag, Clock, ArrowLeft } from 'lucide-react';
import { notFound } from 'next/navigation';

export async function generateStaticParams() {
  return [
    { category: 'ppc' },
    { category: 'seo' },
    { category: 'writing' },
    { category: 'tools' },
  ];
}

export default function BlogCategoryPage({ params }: { params: { category: string } }) {
  const categoryMap: Record<string, { name: string; description: string }> = {
    ppc: { name: 'PPC', description: 'Pay-per-click advertising strategies, tips, and insights' },
    seo: { name: 'SEO', description: 'Search engine optimization techniques and best practices' },
    writing: { name: 'Writing', description: 'Content writing, ad copy, and creative strategies' },
    tools: { name: 'Tools', description: 'Marketing tools, software reviews, and productivity tips' },
  };

  const category = categoryMap[params.category.toLowerCase()];
  
  if (!category) {
    notFound();
  }

  const blogCategories = [
    { name: 'All Posts', href: '/blog', active: false, count: 24 },
    { name: 'PPC', href: '/blog/category/ppc', active: params.category.toLowerCase() === 'ppc', count: 8 },
    { name: 'SEO', href: '/blog/category/seo', active: params.category.toLowerCase() === 'seo', count: 6 },
    { name: 'Writing', href: '/blog/category/writing', active: params.category.toLowerCase() === 'writing', count: 5 },
    { name: 'Tools', href: '/blog/category/tools', active: params.category.toLowerCase() === 'tools', count: 5 },
  ];

  const allPosts = [
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
    {
      id: 7,
      title: 'On-Page SEO Checklist: Everything You Need to Know',
      excerpt: 'A complete guide to on-page SEO optimization. Learn how to optimize your content, meta tags, and HTML structure for better search rankings.',
      category: 'SEO',
      author: 'SEO Experts',
      date: 'December 14, 2025',
      readTime: '7 min read',
      featured: false,
    },
    {
      id: 8,
      title: 'How to Write Headlines That Get Clicks',
      excerpt: 'Master the art of writing headlines that capture attention and drive engagement. Learn proven formulas and techniques used by top copywriters.',
      category: 'Writing',
      author: 'Content Team',
      date: 'December 13, 2025',
      readTime: '6 min read',
      featured: false,
    },
  ];

  const categoryPosts = allPosts.filter(post => 
    post.category.toLowerCase() === params.category.toLowerCase()
  );

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        {/* Category Header */}
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive z-10">
            <div className="mb-6">
              <Link 
                href="/blog"
                className="inline-flex items-center gap-2 text-purple-400 hover:text-purple-300 transition-colors mb-6"
              >
                <ArrowLeft className="w-4 h-4" />
                <span>Back to Blog</span>
              </Link>
            </div>
            
            <div className="text-center">
              <div className="mb-4">
                <span className="inline-block px-4 py-2 bg-purple-500/20 border border-purple-500/30 rounded-full text-purple-300 text-sm font-medium">
                  {category.name}
                </span>
              </div>
              <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
                <span className="text-white">{category.name}</span>{' '}
                <span className="text-gradient-vibrant">Articles</span>
              </h1>
              <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4">
                {category.description}
              </p>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        {/* Blog Categories */}
        <section className="py-6 phone:py-8 bg-gray-900/50 border-b border-purple-500/20 sticky top-16 phone:top-20 z-40 backdrop-blur-xl">
          <div className="container-responsive">
            <div className="flex flex-wrap justify-center gap-3 phone:gap-4">
              {blogCategories.map((cat) => (
                <Link
                  key={cat.name}
                  href={cat.href}
                  className={`px-4 phone:px-6 py-2 rounded-lg font-medium transition-all text-sm phone:text-base card-3d ${
                    cat.active
                      ? 'bg-gradient-to-r from-purple-600 to-pink-600 text-white shadow-lg shadow-purple-500/50'
                      : 'bg-gray-800 text-gray-300 hover:bg-gray-700 hover:text-white'
                  }`}
                >
                  {cat.name} <span className="text-xs opacity-75">({cat.count})</span>
                </Link>
              ))}
            </div>
          </div>
        </section>

        {/* Category Posts */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            {categoryPosts.length > 0 ? (
              <>
                <h2 className="text-2xl phone:text-3xl tablet:text-4xl font-bold mb-8 phone:mb-12 text-center">
                  {categoryPosts.length} {category.name} <span className="text-gradient-vibrant">Articles</span>
                </h2>
                
                <div className="grid grid-cols-1 tablet:grid-cols-2 desktop:grid-cols-3 gap-6 phone:gap-8">
                  {categoryPosts.map((post) => (
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
              </>
            ) : (
              <div className="text-center py-12 phone:py-16">
                <p className="text-gray-400 text-lg mb-6">No articles found in this category.</p>
                <AnimatedButton href="/blog">View All Posts</AnimatedButton>
              </div>
            )}
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
