<?php get_header(); ?>

<!-- WordStream-style Hero Section -->
<section class="ws-hero">
    <div class="ws-hero-container">
        <h1>Stop wasting money in Google Ads</h1>
        <p>Get an instant audit of your account with the Free Google Ads Performance Grader.</p>
        <div class="ws-hero-actions">
            <a href="<?php echo esc_url(home_url('/google-ads-grader')); ?>" class="ws-btn ws-btn-primary">Get Your Grade</a>
            <a href="<?php echo esc_url(home_url('/learn-more')); ?>" class="ws-btn ws-btn-secondary">Learn More</a>
        </div>
    </div>
</section>

<!-- Performance Stats Section -->
<section class="ws-stats">
    <div class="ws-container">
        <h2>Get a true read on your performance</h2>
        <p class="ws-stats-subtitle">THE GOOGLE ADS PERFORMANCE GRADER</p>
        <p>How are your Google Ads really performing? Find out with a free, instant report card, with scores in nine key areas (plus tips on how to improve!)</p>
        
        <div class="ws-grid ws-grid-4" style="margin-top: 40px;">
            <div class="ws-stat-item">
                <div class="ws-stat-icon">💰</div>
                <h4>Wasted spend</h4>
            </div>
            <div class="ws-stat-item">
                <div class="ws-stat-icon">⭐</div>
                <h4>Quality Score</h4>
            </div>
            <div class="ws-stat-item">
                <div class="ws-stat-icon">👆</div>
                <h4>Click-through rate</h4>
            </div>
            <div class="ws-stat-item">
                <div class="ws-stat-icon">👁️</div>
                <h4>Impression share</h4>
            </div>
        </div>
        
        <div class="ws-cta-section" style="margin-top: 40px; text-align: center;">
            <a href="<?php echo esc_url(home_url('/google-ads-grader')); ?>" class="ws-btn ws-btn-primary">Grade My Account</a>
        </div>
    </div>
</section>

<!-- Business Growth Section -->
<section class="ws-section">
    <div class="ws-container">
        <div class="ws-grid ws-grid-2" style="align-items: center;">
            <div>
                <h2>Get ready to grow your business</h2>
                <p>LocaliQ offers technology-backed marketing solutions built to help you find, convert, and keep more customers.</p>
                
                <div class="ws-business-stats" style="margin: 30px 0;">
                    <div class="ws-stat-row">
                        <span class="ws-stat-number">142M</span>
                        <span class="ws-stat-label">hours saved</span>
                    </div>
                    <div class="ws-stat-row">
                        <span class="ws-stat-number">275M</span>
                        <span class="ws-stat-label">leads delivered</span>
                    </div>
                    <div class="ws-stat-row">
                        <span class="ws-stat-number">$15.9B</span>
                        <span class="ws-stat-label">in revenue generated</span>
                    </div>
                </div>
                
                <h3>Seize your potential</h3>
                <p>Schedule a demo today to learn how LocaliQ's marketing platform can help you achieve your goals.</p>
                <a href="<?php echo esc_url(home_url('/demo')); ?>" class="ws-btn ws-btn-primary">Let's Talk</a>
            </div>
            <div>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/business-growth-graphic.png" alt="Business Growth" class="ws-responsive-image" />
            </div>
        </div>
    </div>
</section>

<!-- Google Ads Guide Section -->
<section class="ws-section ws-section-alt">
    <div class="ws-container">
        <div class="ws-grid ws-grid-2" style="align-items: center;">
            <div>
                <h2>The definitive guide to Google Ads performance</h2>
                <h5 style="color: var(--ws-accent-orange); font-weight: bold; margin-bottom: 20px;">NEW INDUSTRY BENCHMARKS FOR 2025</h5>
                
                <p>Get exclusive access to search advertising benchmarks from over 16,000 campaigns across 23 industries.</p>
                
                <ul class="ws-feature-list">
                    <li>Current industry averages for key metrics</li>
                    <li>Expert analysis from leading PPC professionals</li>
                    <li>Practical optimization checklists and action items</li>
                    <li>Actionable strategies to improve your ad performance</li>
                </ul>
                
                <a href="<?php echo esc_url(home_url('/benchmarks-guide')); ?>" class="ws-btn ws-btn-primary">Get My Guide</a>
            </div>
            <div>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/benchmarks-guide.png" alt="Google Ads Benchmarks Guide" class="ws-responsive-image" />
            </div>
        </div>
    </div>
</section>

<!-- Free Keyword Tool Section -->
<section class="ws-section">
    <div class="ws-container">
        <div class="ws-grid ws-grid-2" style="align-items: center;">
            <div>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/keyword-tool.png" alt="Free Keyword Tool" class="ws-responsive-image" />
            </div>
            <div>
                <h2>Keyword research made fast and easy</h2>
                <h5 style="color: var(--ws-accent-orange); font-weight: bold; margin-bottom: 20px;">THE FREE KEYWORD TOOL</h5>
                
                <p>Keyword research can be a slog, but our free tool makes it a cinch to find the keywords your business needs to drive traffic through SEO and PPC. Simply enter a keyword or URL to get:</p>
                
                <ul class="ws-feature-list">
                    <li>Hundreds of relevant keyword suggestions</li>
                    <li>Tailored to your industry and location</li>
                    <li>With keyword volume and cost per click data</li>
                    <li>Using the latest Google Search data</li>
                </ul>
                
                <a href="<?php echo esc_url(home_url('/keyword-tool')); ?>" class="ws-btn ws-btn-primary">Find My Keywords</a>
            </div>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section class="ws-section ws-section-alt">
    <div class="ws-container">
        <h2 style="text-align: center; margin-bottom: 50px;">The WordStream Blog</h2>
        
        <div class="ws-grid ws-grid-3">
            <?php
            // Get latest 3 blog posts
            $blog_posts = new WP_Query(array(
                'posts_per_page' => 3,
                'post_status' => 'publish'
            ));
            
            if ($blog_posts->have_posts()) :
                while ($blog_posts->have_posts()) : $blog_posts->the_post();
            ?>
                <article class="ws-blog-post">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php the_title(); ?>" class="ws-blog-image" />
                    <?php endif; ?>
                    
                    <div class="ws-blog-content">
                        <h3 class="ws-blog-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="ws-blog-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                        <a href="<?php the_permalink(); ?>" class="ws-blog-link">Keep Reading</a>
                    </div>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
                <!-- Fallback content if no posts -->
                <article class="ws-blog-post">
                    <div class="ws-blog-content">
                        <h3 class="ws-blog-title">The Ultimate Cheat Sheet to Holiday Advertising in 2025</h3>
                        <p class="ws-blog-excerpt">Make a winning game plan for your business this season with this complete holiday advertising playbook featuring resources from top platforms.</p>
                        <a href="#" class="ws-blog-link">Keep Reading</a>
                    </div>
                </article>
                
                <article class="ws-blog-post">
                    <div class="ws-blog-content">
                        <h3 class="ws-blog-title">Total Guide to Instagram Advertising</h3>
                        <p class="ws-blog-excerpt">Whether you're getting started with your first Instagram advertising campaign or looking to rework your strategy, this guide has everything you need.</p>
                        <a href="#" class="ws-blog-link">Keep Reading</a>
                    </div>
                </article>
                
                <article class="ws-blog-post">
                    <div class="ws-blog-content">
                        <h3 class="ws-blog-title">AI Prompts for Holiday Marketing</h3>
                        <p class="ws-blog-excerpt">Use AI to grow on every channel this holiday season. These prompts will help you do it.</p>
                        <a href="#" class="ws-blog-link">Keep Reading</a>
                    </div>
                </article>
            <?php endif; ?>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="<?php echo esc_url(home_url('/blog')); ?>" class="ws-btn ws-btn-secondary">Visit the Blog</a>
        </div>
    </div>
</section>

<!-- Free Tools Overview -->
<section class="ws-tools">
    <div class="ws-container">
        <h2 style="text-align: center; margin-bottom: 50px;">Our Free Marketing Tools</h2>
        
        <div class="ws-grid ws-grid-4">
            <div class="ws-tool-card">
                <div class="ws-tool-icon">📊</div>
                <h4>Google Ads Grader</h4>
                <p>Get an instant audit of your Google Ads account performance.</p>
                <a href="<?php echo esc_url(home_url('/google-ads-grader')); ?>" class="ws-btn ws-btn-primary">Try It Free</a>
            </div>
            
            <div class="ws-tool-card">
                <div class="ws-tool-icon">📘</div>
                <h4>Facebook Ads Grader</h4>
                <p>Analyze and improve your Facebook advertising campaigns.</p>
                <a href="<?php echo esc_url(home_url('/facebook-ads-grader')); ?>" class="ws-btn ws-btn-primary">Try It Free</a>
            </div>
            
            <div class="ws-tool-card">
                <div class="ws-tool-icon">🔍</div>
                <h4>Free Keyword Tool</h4>
                <p>Find the best keywords for your SEO and PPC campaigns.</p>
                <a href="<?php echo esc_url(home_url('/keyword-tool')); ?>" class="ws-btn ws-btn-primary">Try It Free</a>
            </div>
            
            <div class="ws-tool-card">
                <div class="ws-tool-icon">🌐</div>
                <h4>Website Grader</h4>
                <p>Audit your website's performance and user experience.</p>
                <a href="<?php echo esc_url(home_url('/website-grader')); ?>" class="ws-btn ws-btn-primary">Try It Free</a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>