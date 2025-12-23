<?php
//about theme info
add_action( 'admin_menu', 'marketing_agency_gettingstarted' );
function marketing_agency_gettingstarted() {    	
	add_theme_page( esc_html__('About Marketing Agency', 'marketing-agency'), esc_html__('Theme Demo Import', 'marketing-agency'), 'edit_theme_options', 'marketing_agency_guide', 'marketing_agency_mostrar_guide');   
}

// Add a Custom CSS file to WP Admin Area
function marketing_agency_admin_theme_style() {
   wp_enqueue_style('marketing-agency-custom-admin-style', esc_url(get_template_directory_uri()) . '/inc/getstart/getstart.css');
   wp_enqueue_script('marketing-agency-tabs', esc_url(get_template_directory_uri()) . '/inc/getstart/js/tab.js');
}
add_action('admin_enqueue_scripts', 'marketing_agency_admin_theme_style');

//guidline for about theme
function marketing_agency_mostrar_guide() { 
	//custom function about theme customizer
	$return = add_query_arg( array()) ;
	$theme = wp_get_theme( 'marketing-agency' );
?>

<div class="wrapper-info">
    <div class="col-left sshot-section">
    	<h2><?php esc_html_e( 'Welcome to Marketing Agency Theme', 'marketing-agency' ); ?> <span class="version">Version: <?php echo esc_html($theme['Version']);?></span></h2>
    	<p><?php esc_html_e('All our WordPress themes are modern, minimalist, 100% responsive, seo-friendly,feature-rich, and multipurpose that best suit designers, bloggers and other professionals who are working in the creative fields.','marketing-agency'); ?></p>
    </div>
    <div class="col-right coupen-section">
    	<div class="logo-section">
			<img src="<?php echo esc_url(get_template_directory_uri()); ?>/screenshot.png" alt="" />
		</div>
		<div class="logo-right">			
			<div class="update-now">
				<div class="theme-info">
					<div class="theme-info-left">
						<h2><?php esc_html_e('TRY PREMIUM','marketing-agency'); ?></h2>
						<h4><?php esc_html_e('MARKETING AGENCY THEME','marketing-agency'); ?></h4>
					</div>	
					<div class="theme-info-right"></div>
				</div>	
				<div class="dicount-row">
					<div class="disc-sec">	
						<h5 class="disc-text"><?php esc_html_e('GET THE FLAT DISCOUNT OF','marketing-agency'); ?></h5>
						<h1 class="disc-per"><?php esc_html_e('20%','marketing-agency'); ?></h1>	
					</div>
					<div class="coupen-info">
						<h5 class="coupen-code"><?php esc_html_e('"VWPRO20"','marketing-agency'); ?></h5>
						<h5 class="coupen-text"><?php esc_html_e('USE COUPON CODE','marketing-agency'); ?></h5>
						<div class="info-link">						
							<a href="<?php echo esc_url( MARKETING_AGENCY_BUY_NOW ); ?>" target="_blank"> <?php esc_html_e( 'UPGRADE TO PRO', 'marketing-agency' ); ?></a>
						</div>	
					</div>	
				</div>				
			</div>
		</div> 
		
    </div>

    <div class="tab-sec">
		<div class="tab">
			<button class="tablinks" onclick="marketing_agency_open_tab(event, 'theme_offer')"><?php esc_html_e( 'Demo Importer', 'marketing-agency' ); ?></button>
			<button class="tablinks" onclick="marketing_agency_open_tab(event, 'lite_theme')"><?php esc_html_e( 'Setup With Customizer', 'marketing-agency' ); ?></button>
			
			<button class="tablinks" onclick="marketing_agency_open_tab(event, 'theme_pro')"><?php esc_html_e( 'Get Premium', 'marketing-agency' ); ?></button>
			<button class="tablinks" onclick="marketing_agency_open_tab(event, 'free_pro')"><?php esc_html_e( 'Free VS Premium', 'marketing-agency' ); ?></button>
			<button class="tablinks" onclick="marketing_agency_open_tab(event, 'get_bundle')"><?php esc_html_e( 'Get 350+ Themes Bundle at $99', 'marketing-agency' ); ?></button>
		</div>

		<?php 
			$marketing_agency_plugin_custom_css = '';
			if(class_exists('Ibtana_Visual_Editor_Menu_Class')){
				$marketing_agency_plugin_custom_css ='display: block';
			}
		?>

		<div id="theme_offer" class="tabcontent open">
			<div class="demo-content">
				<h3><?php esc_html_e( 'Click the below run importer button to import demo content', 'marketing-agency' ); ?></h3>
				<?php 
				/* Get Started. */ 
				require get_parent_theme_file_path( '/inc/getstart/demo-content.php' );
			 	?>
			</div> 	
		</div>

		<div id="lite_theme" class="tabcontent">
			<?php  if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
				$plugin_ins = Marketing_Agency_Plugin_Activation_Settings::get_instance();
				$marketing_agency_actions = $plugin_ins->recommended_actions;
				?>
				<div class="marketing-agency-recommended-plugins">
				    <div class="marketing-agency-action-list">
				        <?php if ($marketing_agency_actions): foreach ($marketing_agency_actions as $key => $marketing_agency_actionValue): ?>
				                <div class="marketing-agency-action" id="<?php echo esc_attr($marketing_agency_actionValue['id']);?>">
			                        <div class="action-inner">
			                            <h3 class="action-title"><?php echo esc_html($marketing_agency_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($marketing_agency_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($marketing_agency_actionValue['link']); ?>
			                            <a class="ibtana-skip-btn" get-start-tab-id="lite-theme-tab" href="javascript:void(0);"><?php esc_html_e('Skip','marketing-agency'); ?></a>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php } ?>
			<div class="lite-theme-tab" style="<?php echo esc_attr($marketing_agency_plugin_custom_css); ?>">
				<h3><?php esc_html_e( 'Lite Theme Information', 'marketing-agency' ); ?></h3>
				<hr class="h3hr">
			  	<p><?php esc_html_e('Marketing Agency is a professional and expertly crafted theme for setting up a sophisticated website for your digital marketing agency. To yield outstanding websites for advertising and SEO agencies or any kind of online marketing business, cooperatives, advertise, server security, server management, digital marketplace, ebooks, music, tutorials, videos, code snippets, resume, corporate agency, portfolio,Marketing, Agency, Consulting, Digital, Branding, image ads or video advertising, Affiliate Marketing, Technical Agency, Support Agency, startups, consultancy corporations, on line marketing, web-marketing, accountants, brokers, image ads advertisment or video advertising, finance recruitment, consults, wealth advisors, construction, life coach, financial centers, ad networks, business consultant, investment firms, tax advisor, investors, creative agencies,this free theme has an elegant layout with a minimalist design bringing your main content into the limelight. With ample personalization options, you get the creative freedom to transform the look of your site and give it your own twist. To provide your business with a boost, social media options are integrated into it that can comprehensively work in promoting your marketing skills. Its design is retina-ready giving a solid display of images projected on the theme banner which is attractive enough to grab the attention of your potential clients. To make sure that you never lose the traffic coming from mobile users, this theme is made responsive and mobile-friendly making your website adaptive to screens of any size. Interactive elements are added by introducing Call To Action (CTA) buttons at desired places and the codes included are highly optimized for giving quick loading pages. This theme is made translation ready as it supports WPML and RTL. It also has a testimonial section, many Theme Options, shortcodes, post formats, transparent header, Flexible Header and supports plugins','marketing-agency'); ?></p>
			  	<div class="col-left-inner">
			  		<h4><?php esc_html_e( 'Theme Documentation', 'marketing-agency' ); ?></h4>
					<p><?php esc_html_e( 'If you need any assistance regarding setting up and configuring the Theme, our documentation is there.', 'marketing-agency' ); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( MARKETING_AGENCY_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'marketing-agency' ); ?></a>
					</div>
					<hr>
					<h4><?php esc_html_e('Theme Customizer', 'marketing-agency'); ?></h4>
					<p> <?php esc_html_e('To begin customizing your website, start by clicking "Customize".', 'marketing-agency'); ?></p>
					<div class="info-link">
						<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Customizing', 'marketing-agency'); ?></a>
					</div>
					<hr>				
					<h4><?php esc_html_e('Having Trouble, Need Support?', 'marketing-agency'); ?></h4>
					<p> <?php esc_html_e('Our dedicated team is well prepared to help you out in case of queries and doubts regarding our theme.', 'marketing-agency'); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( MARKETING_AGENCY_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Support Forum', 'marketing-agency'); ?></a>
					</div>
					<hr>
					<h4><?php esc_html_e('Reviews & Testimonials', 'marketing-agency'); ?></h4>
					<p> <?php esc_html_e('All the features and aspects of this WordPress Theme are phenomenal. I\'d recommend this theme to all.', 'marketing-agency'); ?>  </p>
					<div class="info-link">
						<a href="<?php echo esc_url( MARKETING_AGENCY_REVIEW ); ?>" target="_blank"><?php esc_html_e('Reviews', 'marketing-agency'); ?></a>
					</div>
			  		<div class="link-customizer">
						<h3><?php esc_html_e( 'Link to customizer', 'marketing-agency' ); ?></h3>
						<hr class="h3hr">
						<div class="first-row">
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','marketing-agency'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-align-center"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=marketing_agency_top_header') ); ?>" target="_blank"><?php esc_html_e('Top Header','marketing-agency'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-slides"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=marketing_agency_slidersettings') ); ?>" target="_blank"><?php esc_html_e('Slider Section','marketing-agency'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-text-page"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=marketing_agency_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','marketing-agency'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','marketing-agency'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-admin-customizer"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=marketing_agency_typography') ); ?>" target="_blank"><?php esc_html_e('Typography','marketing-agency'); ?></a>
								</div>
							</div>

							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-format-gallery"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=marketing_agency_post_settings') ); ?>" target="_blank"><?php esc_html_e('Post settings','marketing-agency'); ?></a>
								</div>
								 <div class="row-box2">
									<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','marketing-agency'); ?></a>
								</div> 
							</div>
						</div>
					</div>
			  	</div>
				<div class="col-right-inner">
					<h3 class="page-template"><?php esc_html_e('How to set up Home Page Template','marketing-agency'); ?></h3>
				  	<hr class="h3hr">
					<p><?php esc_html_e('Follow these instructions to setup Home page.','marketing-agency'); ?></p>
	                <ul>
	                  	<p><span class="strong"><?php esc_html_e('1. Create a new page :','marketing-agency'); ?></span><?php esc_html_e(' Go to ','marketing-agency'); ?>
					  	<b><?php esc_html_e(' Dashboard >> Pages >> Add New Page','marketing-agency'); ?></b></p>

	                  	<p><?php esc_html_e('Name it as "Home" then select the template "Custom Home Page".','marketing-agency'); ?></p>
	                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/home-page-template.png" alt="" />
	                  	<p><span class="strong"><?php esc_html_e('2. Set the front page:','marketing-agency'); ?></span><?php esc_html_e(' Go to ','marketing-agency'); ?>
					  	<b><?php esc_html_e(' Settings >> Reading ','marketing-agency'); ?></b></p>
					  	<p><?php esc_html_e('Select the option of Static Page, now select the page you created to be the homepage, while another page to be your default page.','marketing-agency'); ?></p>
	                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/set-front-page.png" alt="" />
	                  	<p><?php esc_html_e(' Once you are done with this, then follow the','marketing-agency'); ?> <a class="doc-links" href="https://preview.vwthemesdemo.com/docs/free-marketing-agency/" target="_blank"><?php esc_html_e('Documentation','marketing-agency'); ?></a></p>
	                </ul>
			  	</div>
			</div>
		</div>

		<div id="theme_pro" class="tabcontent">
		  	<h3><?php esc_html_e( 'Premium Theme Information', 'marketing-agency' ); ?></h3>
			<hr class="h3hr">
		    <div class="col-left-pro">
		    	<p><?php esc_html_e('Marketing Agency WordPress Theme is a top theme to boost your marketing firm. Visual aesthetics of this theme are simply outstanding as it binds the audience and makes them stay on your page for a longer period of time. Filled with tremendous possibilities for marketing businesses, it has the best design that not only elevates your web appearance but also does a comprehensive job in promoting it. The main reason for this is its unconventional design which makes your site easily noticeable. The entire content on your website can be managed very easily giving a sorted and sophisticated look to your page. You get a well-built content space for displaying every information related to your work. WP Marketing Agency WordPress Theme provides you an easy way to style your website by giving you tonnes of convenient options at your disposal. The cutting edge technology used while crafting this theme will result in a website giving a flawless performance.','marketing-agency'); ?></p>
		    </div>
		    <div class="col-right-pro">
		    	<div class="pro-links">
			    	<a href="<?php echo esc_url( MARKETING_AGENCY_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'marketing-agency'); ?></a>
					<a href="<?php echo esc_url( MARKETING_AGENCY_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Buy Pro', 'marketing-agency'); ?></a>
					<a href="<?php echo esc_url( MARKETING_AGENCY_PRO_DOC ); ?>" target="_blank"><?php esc_html_e('Pro Documentation', 'marketing-agency'); ?></a>
					<a href="<?php echo esc_url( MARKETING_AGENCY_THEME_BUNDLE_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Get 350+ Themes Bundle at $99', 'marketing-agency'); ?></a>
				</div>
		    	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/responsive.png" alt="" />
		    </div>
		</div>

		<div id="free_pro" class="tabcontent">
		  	<div class="featurebox">
			    <h3><?php esc_html_e( 'Theme Features', 'marketing-agency' ); ?></h3>
				<hr class="h3hr">
				<div class="table-image">
					<table class="tablebox">
						<thead>
							<tr>
								<th></th>
								<th><?php esc_html_e('Free Themes', 'marketing-agency'); ?></th>
								<th><?php esc_html_e('Premium Themes', 'marketing-agency'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php esc_html_e('Theme Customization', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Responsive Design', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Logo Upload', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Social Media Links', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Slider Settings', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Number of Slides', 'marketing-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('4', 'marketing-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('Unlimited', 'marketing-agency'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Template Pages', 'marketing-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('3', 'marketing-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('6', 'marketing-agency'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Home Page Template', 'marketing-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'marketing-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'marketing-agency'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Theme sections', 'marketing-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('2', 'marketing-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('15', 'marketing-agency'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Contact us Page Template', 'marketing-agency'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('1', 'marketing-agency'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Blog Templates & Layout', 'marketing-agency'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('3(Full width/Left/Right Sidebar)', 'marketing-agency'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Page Templates & Layout', 'marketing-agency'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('2(Left/Right Sidebar)', 'marketing-agency'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Color Pallete For Particular Sections', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Global Color Option', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Section Reordering', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Demo Importer', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Allow To Set Site Title, Tagline, Logo', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Enable Disable Options On All Sections, Logo', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Full Documentation', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Latest WordPress Compatibility', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Woo-Commerce Compatibility', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Support 3rd Party Plugins', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Secure and Optimized Code', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Exclusive Functionalities', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Section Enable / Disable', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Section Google Font Choices', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Gallery', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Simple & Mega Menu Option', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Support to add custom CSS / JS ', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Shortcodes', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Custom Background, Colors, Header, Logo & Menu', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Premium Membership', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Budget Friendly Value', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Priority Error Fixing', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Custom Feature Addition', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('All Access Theme Pass', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Seamless Customer Support', 'marketing-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td></td>
								<td class="table-img"></td>
								<td class="update-link"><a href="<?php echo esc_url( MARKETING_AGENCY_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Upgrade to Pro', 'marketing-agency'); ?></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div id="get_bundle" class="tabcontent">		  	
		   <div class="col-left-pro">
		   	<h3><?php esc_html_e( 'WP Theme Bundle', 'marketing-agency' ); ?></h3>
		    	<p><?php esc_html_e('Enhance your website effortlessly with our WP Theme Bundle. Get access to 350+ premium WordPress themes and 5+ powerful plugins, all designed to meet diverse business needs. Enjoy seamless integration with any plugins, ultimate customization flexibility, and regular updates to keep your site current and secure. Plus, benefit from our dedicated customer support, ensuring a smooth and professional web experience.','marketing-agency'); ?></p>
		    	<div class="feature">
		    		<h4><?php esc_html_e( 'Features:', 'marketing-agency' ); ?></h4>
		    		<p><?php esc_html_e('350+ Premium Themes & 5+ Plugins.', 'marketing-agency'); ?></p>
		    		<p><?php esc_html_e('Seamless Integration.', 'marketing-agency'); ?></p>
		    		<p><?php esc_html_e('Customization Flexibility.', 'marketing-agency'); ?></p>
		    		<p><?php esc_html_e('Regular Updates.', 'marketing-agency'); ?></p>
		    		<p><?php esc_html_e('Dedicated Support.', 'marketing-agency'); ?></p>
		    	</div>
		    	<p><?php esc_html_e('Upgrade now and give your website the professional edge it deserves, all at an unbeatable price of $99!', 'marketing-agency'); ?></p>
		    	<div class="pro-links">
					<a href="<?php echo esc_url( MARKETING_AGENCY_THEME_BUNDLE_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Buy Now', 'marketing-agency'); ?></a>
					<a href="<?php echo esc_url( MARKETING_AGENCY_THEME_BUNDLE_DOC ); ?>" target="_blank"><?php esc_html_e('Documentation', 'marketing-agency'); ?></a>
				</div>
		   </div>
		   <div class="col-right-pro">
		    	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/bundle.png" alt="" />
		   </div>		    
		</div>
		
	</div>
</div>
<?php } ?>