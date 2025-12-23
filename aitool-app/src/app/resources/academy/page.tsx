import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import Link from 'next/link';
import { BookOpen, Play, CheckCircle, Award } from 'lucide-react';

export default function AcademyPage() {
  const courses = [
    { title: 'PPC Fundamentals', duration: '2 hours', level: 'Beginner' },
    { title: 'AI-Powered Ad Creation', duration: '3 hours', level: 'Intermediate' },
    { title: 'Campaign Optimization', duration: '4 hours', level: 'Advanced' },
    { title: 'Facebook Ads Mastery', duration: '5 hours', level: 'Advanced' },
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative container-responsive z-10">
            <div className="text-center mb-8 phone:mb-12">
              <BookOpen className="w-16 phone:w-20 h-16 phone:h-20 text-purple-400 mx-auto mb-6 animate-float-3d" />
              <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
                <span className="text-white">Academy</span>
              </h1>
              <p className="text-lg phone:text-xl text-gray-300 max-w-3xl mx-auto px-4 mb-8">
                Learn marketing strategies and master AI-powered PPC campaigns
              </p>
            </div>
          </div>
        </section>

        <Separator opacity={1} />

        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="max-w-5xl mx-auto">
              <h2 className="text-3xl phone:text-4xl font-bold mb-8 text-white text-center">
                Available Courses
              </h2>
              <div className="grid grid-cols-1 tablet:grid-cols-2 gap-6">
                {courses.map((course, idx) => (
                  <div key={idx} className="card-premium card-3d">
                    <div className="flex items-start justify-between mb-4">
                      <div>
                        <h3 className="text-xl font-bold text-white mb-2">{course.title}</h3>
                        <div className="flex items-center gap-4 text-sm text-gray-400">
                          <span className="flex items-center gap-1">
                            <Play className="w-4 h-4" />
                            {course.duration}
                          </span>
                          <span className="px-2 py-1 bg-purple-500/20 rounded text-purple-300">
                            {course.level}
                          </span>
                        </div>
                      </div>
                    </div>
                    <AnimatedButton href="/app/dashboard" className="w-full">
                      Enroll Now
                    </AnimatedButton>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
