import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import { Briefcase, MapPin, Clock } from 'lucide-react';

export default function CareersPage() {
  const openPositions = [
    {
      title: 'Senior Full-Stack Developer',
      location: 'Remote / Jaipur',
      type: 'Full-time',
      department: 'Engineering',
    },
    {
      title: 'AI/ML Engineer',
      location: 'Remote / Jaipur',
      type: 'Full-time',
      department: 'Engineering',
    },
    {
      title: 'PPC Campaign Manager',
      location: 'Remote',
      type: 'Full-time',
      department: 'Marketing',
    },
    {
      title: 'Product Designer',
      location: 'Remote / Jaipur',
      type: 'Full-time',
      department: 'Design',
    },
  ];

  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-20">
        {/* Hero Section */}
        <section className="relative py-20 bg-gradient-to-b from-purple-900/20 to-gray-900">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 className="text-5xl md:text-6xl font-bold mb-4">
              <span className="text-white">Join the</span>{' '}
              <span className="text-gradient-vibrant">Team</span>
            </h1>
            <p className="text-xl text-gray-300 max-w-2xl mx-auto">
              Help us revolutionize PPC campaign management with AI
            </p>
          </div>
        </section>

        {/* Why Join Us */}
        <section className="py-20">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 className="text-4xl font-bold text-center mb-12 text-white">
              Why Join <span className="text-gradient-vibrant">AITool.Software</span>?
            </h2>
            
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
              <div className="bg-gray-800/50 border border-purple-500/20 rounded-xl p-8">
                <h3 className="text-xl font-bold mb-3 text-white">Innovation First</h3>
                <p className="text-gray-400">
                  Work on cutting-edge AI technology that's transforming the PPC industry
                </p>
              </div>
              
              <div className="bg-gray-800/50 border border-purple-500/20 rounded-xl p-8">
                <h3 className="text-xl font-bold mb-3 text-white">Remote Friendly</h3>
                <p className="text-gray-400">
                  Work from anywhere. We believe in results, not location
                </p>
              </div>
              
              <div className="bg-gray-800/50 border border-purple-500/20 rounded-xl p-8">
                <h3 className="text-xl font-bold mb-3 text-white">Growth Opportunities</h3>
                <p className="text-gray-400">
                  Grow your career with a fast-growing company in the AI space
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* Open Positions */}
        <section className="py-20 bg-gray-900/50">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 className="text-4xl font-bold text-center mb-12 text-white">
              Open <span className="text-gradient-vibrant">Positions</span>
            </h2>
            
            <div className="space-y-4">
              {openPositions.map((position, index) => (
                <div
                  key={index}
                  className="bg-gray-800/50 border border-purple-500/20 rounded-xl p-6 hover:border-purple-500/50 transition-all"
                >
                  <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                      <h3 className="text-xl font-bold text-white mb-2">{position.title}</h3>
                      <div className="flex flex-wrap gap-4 text-sm text-gray-400">
                        <div className="flex items-center gap-2">
                          <Briefcase className="w-4 h-4" />
                          <span>{position.department}</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <MapPin className="w-4 h-4" />
                          <span>{position.location}</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <Clock className="w-4 h-4" />
                          <span>{position.type}</span>
                        </div>
                      </div>
                    </div>
                    <AnimatedButton href={`/careers/apply?position=${encodeURIComponent(position.title)}`} className="whitespace-nowrap">
                      Apply Now
                    </AnimatedButton>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* CTA Section */}
        <section className="py-20">
          <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 className="text-4xl font-bold mb-4 text-white">
              Don't See Your Role?
            </h2>
            <p className="text-xl text-gray-300 mb-8">
              We're always looking for talented individuals. Send us your resume!
            </p>
            <a
              href="mailto:careers@aitool.software"
              className="inline-block px-8 py-4 bg-gray-800 border border-purple-500/30 rounded-lg text-white font-semibold hover:bg-gray-700 transition-all"
            >
              Send Resume
            </a>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
