import Navigation from '@/components/Navigation';
import Footer from '@/components/Footer';
import AnimatedButton from '@/components/AnimatedButton';
import Separator from '@/components/Separator';
import { Phone, Mail, MapPin, Send, Clock, MessageCircle, FileText } from 'lucide-react';

export default function ContactPage() {
  return (
    <div className="min-h-screen flex flex-col">
      <Navigation />
      
      <main className="flex-1 pt-16 phone:pt-20">
        {/* Hero Section - Rich Content */}
        <section className="relative py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-purple-900/20 to-gray-900 overflow-hidden">
          <div className="absolute inset-0 starry-bg opacity-20"></div>
          <div className="absolute top-1/4 left-1/4 w-64 phone:w-96 h-64 phone:h-96 bg-purple-500/10 rounded-full blur-3xl animate-float-3d"></div>
          
          <div className="relative container-responsive text-center z-10">
            <h1 className="text-4xl phone:text-5xl tablet:text-6xl font-bold mb-4 phone:mb-6">
              <span className="text-white">Get in</span>{' '}
              <span className="text-gradient-vibrant">Touch</span>
            </h1>
            <p className="text-lg phone:text-xl text-gray-300 max-w-2xl mx-auto px-4 mb-8 phone:mb-12">
              Have questions? We're here to help. Reach out to our team and we'll get back to you as soon as possible.
            </p>
            
            <div className="rich-content text-left max-w-3xl mx-auto px-4">
              <p className="text-gray-400 leading-relaxed">
                Whether you need help with your account, have questions about our platform, 
                or want to discuss enterprise solutions, our support team is ready to assist you. 
                We typically respond within 24 hours and offer priority support for enterprise customers.
              </p>
            </div>
          </div>
        </section>

        {/* Contact Section - Rich Content with 3D */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gray-900/50 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="grid grid-cols-1 desktop:grid-cols-2 gap-8 phone:gap-12">
              {/* Contact Info - Rich Content */}
              <div className="space-y-6 phone:space-y-8">
                <div>
                  <h2 className="text-3xl phone:text-4xl font-bold mb-6 phone:mb-8 text-white">
                    Contact Information
                  </h2>
                  <p className="text-gray-400 mb-6 phone:mb-8 leading-relaxed">
                    Reach out to us through any of these channels. We're available 24/7 to assist 
                    with your needs and answer any questions about our platform.
                  </p>
                </div>
                
                <div className="space-y-4 phone:space-y-6">
                  <div className="card-premium card-3d flex items-start gap-4 phone:gap-6">
                    <div className="w-12 phone:w-14 h-12 phone:h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0 animate-float-3d">
                      <Phone className="w-6 phone:w-7 h-6 phone:h-7 text-white" />
                    </div>
                    <div className="flex-1">
                      <h3 className="text-lg phone:text-xl font-semibold text-white mb-1 phone:mb-2">Phone</h3>
                      <a href="tel:+18555742506" className="text-gray-300 hover:text-purple-400 transition-colors text-base phone:text-lg">
                        +1-855-574-2506
                      </a>
                      <p className="text-gray-500 text-sm phone:text-base mt-1">Available 24/7</p>
                    </div>
                  </div>
                  
                  <div className="card-premium card-3d flex items-start gap-4 phone:gap-6">
                    <div className="w-12 phone:w-14 h-12 phone:h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0 animate-float-3d">
                      <Mail className="w-6 phone:w-7 h-6 phone:h-7 text-white" />
                    </div>
                    <div className="flex-1">
                      <h3 className="text-lg phone:text-xl font-semibold text-white mb-1 phone:mb-2">Email</h3>
                      <a href="mailto:Help@aitool.software" className="text-gray-300 hover:text-purple-400 transition-colors text-base phone:text-lg break-all">
                        Help@aitool.software
                      </a>
                      <p className="text-gray-500 text-sm phone:text-base mt-1">Response within 24 hours</p>
                    </div>
                  </div>
                  
                  <div className="card-premium card-3d flex items-start gap-4 phone:gap-6">
                    <div className="w-12 phone:w-14 h-12 phone:h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center flex-shrink-0 animate-float-3d">
                      <MapPin className="w-6 phone:w-7 h-6 phone:h-7 text-white" />
                    </div>
                    <div className="flex-1">
                      <h3 className="text-lg phone:text-xl font-semibold text-white mb-1 phone:mb-2">Address</h3>
                      <p className="text-gray-300 text-base phone:text-lg leading-relaxed">
                        F-12 Govindam Tower<br />
                        Jaipur 302012<br />
                        India
                      </p>
                    </div>
                  </div>
                </div>

                {/* Registration Info - Rich Content */}
                <div className="card-premium card-3d mt-8 phone:mt-12">
                  <div className="flex items-start gap-3 phone:gap-4 mb-4 phone:mb-6">
                    <FileText className="w-6 phone:w-7 h-6 phone:h-7 text-purple-400 flex-shrink-0 mt-1" />
                    <h3 className="text-lg phone:text-xl font-semibold text-white">Registration Details</h3>
                  </div>
                  <div className="space-y-2 phone:space-y-3 text-sm phone:text-base">
                    <p className="text-gray-300">
                      <span className="text-gray-400">Registration Certificate:</span>{' '}
                      <span className="text-white font-medium">RJ-17-0526261</span>
                    </p>
                    <p className="text-gray-300">
                      <span className="text-gray-400">Registration Number:</span>{' '}
                      <span className="text-white font-medium">08AAUFC7776R1Z2</span>
                    </p>
                  </div>
                </div>
              </div>

              {/* Contact Form - Rich Content with 3D */}
              <div className="card-premium card-3d">
                <div className="mb-6 phone:mb-8">
                  <h2 className="text-3xl phone:text-4xl font-bold mb-3 phone:mb-4 text-white">
                    Send us a Message
                  </h2>
                  <p className="text-gray-400 leading-relaxed">
                    Fill out the form below and we'll get back to you as soon as possible. 
                    For urgent matters, please call us directly.
                  </p>
                </div>
                
                <form className="space-y-4 phone:space-y-6">
                  <div>
                    <label htmlFor="name" className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                      Full Name *
                    </label>
                    <input
                      type="text"
                      id="name"
                      name="name"
                      required
                      className="w-full px-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors text-sm phone:text-base"
                      placeholder="John Doe"
                    />
                  </div>
                  
                  <div>
                    <label htmlFor="email" className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                      Email Address *
                    </label>
                    <input
                      type="email"
                      id="email"
                      name="email"
                      required
                      className="w-full px-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors text-sm phone:text-base"
                      placeholder="your@email.com"
                    />
                  </div>
                  
                  <div>
                    <label htmlFor="subject" className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                      Subject *
                    </label>
                    <input
                      type="text"
                      id="subject"
                      name="subject"
                      required
                      className="w-full px-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors text-sm phone:text-base"
                      placeholder="What's this about?"
                    />
                  </div>
                  
                  <div>
                    <label htmlFor="message" className="block text-sm phone:text-base font-medium text-gray-300 mb-2">
                      Message *
                    </label>
                    <textarea
                      id="message"
                      name="message"
                      rows={6}
                      required
                      className="w-full px-4 py-3 bg-gray-900 border border-purple-500/20 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 transition-colors resize-none text-sm phone:text-base"
                      placeholder="Tell us how we can help..."
                    ></textarea>
                  </div>
                  
                  <div className="w-full">
                    <AnimatedButton href="#" className="w-full flex items-center justify-center gap-2">
                      <Send className="w-5 h-5" />
                      Send Message
                    </AnimatedButton>
                  </div>
                  
                  <p className="text-xs phone:text-sm text-gray-500 text-center">
                    By submitting this form, you agree to our privacy policy. We'll never share your information.
                  </p>
                </form>
              </div>
            </div>
          </div>
        </section>

        <Separator opacity={0.1} />

        {/* Support Hours Section */}
        <section className="py-12 phone:py-16 tablet:py-20 bg-gradient-to-b from-gray-900/50 to-gray-900 relative">
          <div className="absolute inset-0 starry-bg opacity-10"></div>
          <div className="relative container-responsive">
            <div className="card-premium card-3d max-w-3xl mx-auto px-6 phone:px-8 py-12 phone:py-16 text-center">
              <div className="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-6 animate-float-3d">
                <Clock className="w-8 h-8 text-white" />
              </div>
              <h2 className="text-3xl phone:text-4xl font-bold mb-4 phone:mb-6 text-white">
                Support Hours
              </h2>
              <div className="rich-content text-left max-w-md mx-auto">
                <p className="text-gray-300 mb-4 leading-relaxed">
                  Our support team is available 24/7 to assist you with any questions or issues. 
                  For urgent matters, please call our phone line directly.
                </p>
                <ul className="space-y-2 text-gray-400">
                  <li className="flex items-center gap-2">
                    <MessageCircle className="w-5 h-5 text-purple-400" />
                    <span>Email support: Response within 24 hours</span>
                  </li>
                  <li className="flex items-center gap-2">
                    <Phone className="w-5 h-5 text-purple-400" />
                    <span>Phone support: Available 24/7</span>
                  </li>
                  <li className="flex items-center gap-2">
                    <Clock className="w-5 h-5 text-purple-400" />
                    <span>Enterprise customers: Priority support</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </section>
      </main>
      
      <Footer />
    </div>
  );
}
