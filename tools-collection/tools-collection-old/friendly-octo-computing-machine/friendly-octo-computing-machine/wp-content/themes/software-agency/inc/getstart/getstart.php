<?php
//about theme info
add_action( 'admin_menu', 'software_agency_gettingstarted' );
function software_agency_gettingstarted() {
	add_theme_page( esc_html__('About Software Agency', 'software-agency'), esc_html__('Theme Demo Import', 'software-agency'), 'edit_theme_options', 'software_agency_guide', 'software_agency_mostrar_guide');
}

// Add a Custom CSS file to WP Admin Area
function software_agency_admin_theme_style() {
	wp_enqueue_style('software-agency-custom-admin-style', esc_url(get_template_directory_uri()) . '/inc/getstart/getstart.css');
	wp_enqueue_script('software-agency-tabs', esc_url(get_template_directory_uri()) . '/inc/getstart/js/tab.js');
}
add_action('admin_enqueue_scripts', 'software_agency_admin_theme_style');

//guidline for about theme
function software_agency_mostrar_guide() { 
	//custom function about theme customizer
	$software_agency_return = add_query_arg( array()) ;
	$software_agency_theme = wp_get_theme( 'software-agency' );
?>

<div class="wrapper-info">
    <div class="col-left  sshot-section">
    	<h2><?php esc_html_e( 'Welcome to Software Agency', 'software-agency' ); ?> <span class="version"><?php esc_html_e( 'Version', 'software-agency' ); ?>: <?php echo esc_html($software_agency_theme['Version']);?></span></h2>
    	<p><?php esc_html_e('All our WordPress themes are modern, minimalist, 100% responsive, seo-friendly,feature-rich, and multipurpose that best suit designers, bloggers and other professionals who are working in the creative fields.','software-agency'); ?></p>
    </div>
    <div class="col-right coupen-section">
    	<div class="logo-section">
			<img src="<?php echo esc_url(get_template_directory_uri()); ?>/screenshot.png" alt="" />
		</div>
		<div class="logo-right">			
			<div class="update-now">
				<div class="theme-info">
					<div class="theme-info-left">
						<h2><?php esc_html_e('TRY PREMIUM','software-agency'); ?></h2>
						<h4><?php esc_html_e('SOFTWARE AGENCY THEME','software-agency'); ?></h4>
					</div>	
					<div class="theme-info-right"></div>
				</div>	
				<div class="dicount-row">
					<div class="disc-sec">	
						<h5 class="disc-text"><?php esc_html_e('GET THE FLAT DISCOUNT OF','software-agency'); ?></h5>
						<h1 class="disc-per"><?php esc_html_e('20%','software-agency'); ?></h1>	
					</div>
					<div class="coupen-info">
						<h5 class="coupen-code"><?php esc_html_e('"VWPRO20"','software-agency'); ?></h5>
						<h5 class="coupen-text"><?php esc_html_e('USE COUPON CODE','software-agency'); ?></h5>
						<div class="info-link">						
							<a href="<?php echo esc_url( SOFTWARE_AGENCY_BUY_NOW ); ?>" target="_blank"> <?php esc_html_e( 'UPGRADE TO PRO', 'software-agency' ); ?></a>
						</div>	
					</div>	
				</div>				
			</div>
		</div> 
	
    </div>

    <div class="tab-sec">
    	<div class="tab">
    		<button class="tablinks" onclick="software_agency_open_tab(event, 'theme_offer')"><?php esc_html_e( 'Demo Importer', 'software-agency' ); ?></button>
    		<button class="tablinks" onclick="software_agency_open_tab(event, 'lite_theme')"><?php esc_html_e( 'Setup With Customizer', 'software-agency' ); ?></button>
    		
			<button class="tablinks" onclick="software_agency_open_tab(event, 'theme_pro')"><?php esc_html_e( 'Get Premium', 'software-agency' ); ?></button>
		  	<button class="tablinks" onclick="software_agency_open_tab(event, 'free_pro')"><?php esc_html_e( 'Free VS Premium', 'software-agency' ); ?></button>
		  	<button class="tablinks" onclick="software_agency_open_tab(event, 'get_bundle')"><?php esc_html_e( 'Get 350+ Themes Bundle at $99', 'software-agency' ); ?></button>
		</div>

		<?php 
			$software_agency_plugin_custom_css = '';
			if(class_exists('Ibtana_Visual_Editor_Menu_Class')){
				$software_agency_plugin_custom_css ='display: block';
			}
		?>

		<div id="theme_offer" class="tabcontent open">
			<div class="demo-content">
				<h3><?php esc_html_e( 'Click the below run importer button to import demo content', 'software-agency' ); ?></h3>
				<?php 
				/* Get Started. */ 
				require get_parent_theme_file_path( '/inc/getstart/demo-content.php' );
			 	?>
			</div> 	
		</div>

		<div id="lite_theme" class="tabcontent">
			<?php  if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
				$plugin_ins = Software_Agency_Plugin_Activation_Settings::get_instance();
				$software_agency_actions = $plugin_ins->recommended_actions;
				?>
				<div class="software-agency-recommended-plugins">
				    <div class="software-agency-action-list">
				        <?php if ($software_agency_actions): foreach ($software_agency_actions as $key => $software_agency_actionValue): ?>
				                <div class="software-agency-action" id="<?php echo esc_attr($software_agency_actionValue['id']);?>">
			                        <div class="action-inner">
			                            <h3 class="action-title"><?php echo esc_html($software_agency_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($software_agency_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($software_agency_actionValue['link']); ?>
			                            <a class="ibtana-skip-btn" get-start-tab-id="lite-theme-tab" href="javascript:void(0);"><?php esc_html_e('Skip','software-agency'); ?></a>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php } ?>
			<div class="lite-theme-tab" style="<?php echo esc_attr($software_agency_plugin_custom_css); ?>">
				<h3><?php esc_html_e( 'Lite Theme Information', 'software-agency' ); ?></h3>
				<hr class="h3hr">	
				<p><?php esc_html_e('Software Agency is a technically sound and powerful theme with a layout conducive for IT and software company’s websites, IT Startups, electronic marketing, digital marketplace, plugins, software, cyber security, designer, code snippets, stock photos, video advertising, ad networks, softwares, seo apps, PR agencies, Anti viruses, designong apps, audio files, digital shop, creative agencies, tutorials,Agency, Consulting, Technology, Startup, Services, social media marketing agency company, data-driven marketing, internet marketing, IT business consultants. It is expertly crafted and has a minimalist approach to show details regarding your software firm and the different technologies you work on. It has got an elegant design to put your business in front of your audience in a much sophisticated way. To make your site look professional, it has got a ready-made skin in which you just need to fit your content. As this theme has plenty of customization options, you will never need any coding skills to configure or personalize the theme making it user-friendly. With its stunning banner, conveying info about your services becomes much easier. This bootstrap based theme is mobile friendly and responsive making your site work phenomenally on all devices including tablets as well as smartphones. To add an interactive element to your site, there are Call To Action (CTA) buttons. This also plays the role in improving conversion rates. The optimized codes deliver faster page load time and don’t keep your visitors waiting. You can achieve a lot more with its social media options and translation ready design.','software-agency'); ?></p>		  	
			  	<div class="col-left-inner">
			  		<h4><?php esc_html_e( 'Theme Documentation', 'software-agency' ); ?></h4>
					<p><?php esc_html_e( 'If you need any assistance regarding setting up and configuring the Theme, our documentation is there.', 'software-agency' ); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( SOFTWARE_AGENCY_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'software-agency' ); ?></a>
					</div>
					<hr>
					<h4><?php esc_html_e('Theme Customizer', 'software-agency'); ?></h4>
					<p> <?php esc_html_e('To begin customizing your website, start by clicking "Customize".', 'software-agency'); ?></p>
					<div class="info-link">
						<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Customizing', 'software-agency'); ?></a>
					</div>
					<hr>				
					<h4><?php esc_html_e('Having Trouble, Need Support?', 'software-agency'); ?></h4>
					<p> <?php esc_html_e('Our dedicated team is well prepared to help you out in case of queries and doubts regarding our theme.', 'software-agency'); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( SOFTWARE_AGENCY_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Support Forum', 'software-agency'); ?></a>
					</div>
					<hr>
					<h4><?php esc_html_e('Reviews & Testimonials', 'software-agency'); ?></h4>
					<p> <?php esc_html_e('All the features and aspects of this WordPress Theme are phenomenal. I\'d recommend this theme to all.', 'software-agency'); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( SOFTWARE_AGENCY_REVIEW ); ?>" target="_blank"><?php esc_html_e('Reviews', 'software-agency'); ?></a>
					</div>
					<div class="link-customizer">
						<h3><?php esc_html_e( 'Link to customizer', 'software-agency' ); ?></h3>
						<hr class="h3hr">
						<div class="first-row">
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','software-agency'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-slides"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=software_agency_slidersettings') ); ?>" target="_blank"><?php esc_html_e('Slider Settings','software-agency'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-welcome-write-blog"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=software_agency_top_header') ); ?>" target="_blank"><?php esc_html_e('Top Header','software-agency'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-products"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=software_agency_product_section') ); ?>" target="_blank"><?php esc_html_e('BestSelling Product','software-agency'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','software-agency'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','software-agency'); ?></a>
								</div>
							</div>

							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-format-gallery"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=software_agency_post_settings') ); ?>" target="_blank"><?php esc_html_e('Post settings','software-agency'); ?></a>
								</div>
								 <div class="row-box2">
									<span class="dashicons dashicons-admin-customizer"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=software_agency_global_typography') ); ?>" target="_blank"><?php esc_html_e('Typography','software-agency'); ?></a>
								</div> 
							</div>
							
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-admin-generic"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=software_agency_left_right') ); ?>" target="_blank"><?php esc_html_e('General Settings','software-agency'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-text-page"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=software_agency_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','software-agency'); ?></a>
								</div>
							</div>
						</div>
					</div>
			  	</div>
				<div class="col-right-inner">
					<h3 class="page-template"><?php esc_html_e('How to set up Home Page Template','software-agency'); ?></h3>
				  	<hr class="h3hr">
					<p><?php esc_html_e('Follow these instructions to setup Home page.','software-agency'); ?></p>
                  	<p><span class="strong"><?php esc_html_e('1. Create a new page :','software-agency'); ?></span><?php esc_html_e(' Go to ','software-agency'); ?>
					  	<b><?php esc_html_e(' Dashboard >> Pages >> Add New Page','software-agency'); ?></b></p>
                  	<p><?php esc_html_e('Name it as "Home" then select the template "Custom Home Page".','software-agency'); ?></p>
                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/home-page-template.png" alt="" />
                  	<p><span class="strong"><?php esc_html_e('2. Set the front page:','software-agency'); ?></span><?php esc_html_e(' Go to ','software-agency'); ?>
					  	<b><?php esc_html_e(' Settings >> Reading ','software-agency'); ?></b></p>
				  	<p><?php esc_html_e('Select the option of Static Page, now select the page you created to be the homepage, while another page to be your default page.','software-agency'); ?></p>
                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/set-front-page.png" alt="" />
                  	<p><?php esc_html_e(' Once you are done with setup, then follow the','software-agency'); ?> <a class="doc-links" href="<?php echo esc_url( SOFTWARE_AGENCY_FREE_THEME_DOC ); ?>" target="_blank"><?php esc_html_e('Documentation','software-agency'); ?></a></p>
			  	</div>
			</div>
		</div>


		<div id="theme_pro" class="tabcontent">
		  	<h3><?php esc_html_e( 'Premium Theme Information', 'software-agency' ); ?></h3>
			<hr class="h3hr">
		    <div class="col-left-pro">
		    	<p><?php esc_html_e('This Software Company WordPress Theme is clean, crisp and has a sophisticated design. It can also be used by tech-savvy and programmers to write blogs regarding the latest technologies and software. A prominent web presence will help you carve a niche in the market where there is n-number of companies and start-ups working in the same business domain. If you own a software company, you need a strong web presence to impress both your visitors as well as competitors. The WP Software Company WordPress Theme does this for you. It has different sections that are designed thoughtfully by the developer. Its wonderful full-width slider welcomes your prospective new clients. They can view the core information about your software company and once they start scrolling down, they will be able to explore more details about your business and services. There is enough space for showing the details of the software services offered by your firm.','software-agency'); ?></p>
		    </div>
		    <div class="col-right-pro">
		    	<div class="pro-links">
			    	<a href="<?php echo esc_url( SOFTWARE_AGENCY_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'software-agency'); ?></a>
					<a href="<?php echo esc_url( SOFTWARE_AGENCY_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Buy Pro', 'software-agency'); ?></a>
					<a href="<?php echo esc_url( SOFTWARE_AGENCY_PRO_DOC ); ?>" target="_blank"><?php esc_html_e('Pro Documentation', 'software-agency'); ?></a>
					<a href="<?php echo esc_url( SOFTWARE_AGENCY_THEME_BUNDLE_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Get 350+ Themes Bundle at $99', 'software-agency'); ?></a>
				</div>
		    	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/responsive.png" alt="" />
		    </div>
		</div>

		<div id="free_pro" class="tabcontent">
		  	<div class="featurebox">
			    <h3><?php esc_html_e( 'Theme Features', 'software-agency' ); ?></h3>
				<hr class="h3hr">
				<div class="table-image">
					<table class="tablebox">
						<thead>
							<tr>
								<th></th>
								<th><?php esc_html_e('Free Themes', 'software-agency'); ?></th>
								<th><?php esc_html_e('Premium Themes', 'software-agency'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php esc_html_e('Theme Customization', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Responsive Design', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Logo Upload', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Social Media Links', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Slider Settings', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Number of Slides', 'software-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('4', 'software-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('Unlimited', 'software-agency'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Template Pages', 'software-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('3', 'software-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('6', 'software-agency'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Home Page Template', 'software-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'software-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'software-agency'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Theme sections', 'software-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('2', 'software-agency'); ?></td>
								<td class="table-img"><?php esc_html_e('16', 'software-agency'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Contact us Page Template', 'software-agency'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('1', 'software-agency'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Blog Templates & Layout', 'software-agency'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('3(Full width/Left/Right Sidebar)', 'software-agency'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Page Templates & Layout', 'software-agency'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('2(Left/Right Sidebar)', 'software-agency'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Color Pallete For Particular Sections', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Global Color Option', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Section Reordering', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Demo Importer', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Allow To Set Site Title, Tagline, Logo', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Enable Disable Options On All Sections, Logo', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Full Documentation', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Latest WordPress Compatibility', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Woo-Commerce Compatibility', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Support 3rd Party Plugins', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Secure and Optimized Code', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Exclusive Functionalities', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Section Enable / Disable', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Section Google Font Choices', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Gallery', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Simple & Mega Menu Option', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Support to add custom CSS / JS ', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Shortcodes', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Custom Background, Colors, Header, Logo & Menu', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Premium Membership', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Budget Friendly Value', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Priority Error Fixing', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Custom Feature Addition', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('All Access Theme Pass', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Seamless Customer Support', 'software-agency'); ?></td>
								<td class="table-img"><span class="dashicons dashicons-no"></span></td>
								<td class="table-img"><span class="dashicons dashicons-saved"></span></i></td>
							</tr>
							<tr>
								<td></td>
								<td class="table-img"></td>
								<td class="update-link"><a href="<?php echo esc_url( SOFTWARE_AGENCY_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Upgrade to Pro', 'software-agency'); ?></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
			
		<div id="get_bundle" class="tabcontent">		  	
		   <div class="col-left-pro">
		   	<h3><?php esc_html_e( 'WP Theme Bundle', 'software-agency' ); ?></h3>
		    	<p><?php esc_html_e('Enhance your website effortlessly with our WP Theme Bundle. Get access to 350+ premium WordPress themes and 5+ powerful plugins, all designed to meet diverse business needs. Enjoy seamless integration with any plugins, ultimate customization flexibility, and regular updates to keep your site current and secure. Plus, benefit from our dedicated customer support, ensuring a smooth and professional web experience.','software-agency'); ?></p>
		    	<div class="feature">
		    		<h4><?php esc_html_e( 'Features:', 'software-agency' ); ?></h4>
		    		<p><?php esc_html_e('350+ Premium Themes & 5+ Plugins.', 'software-agency'); ?></p>
		    		<p><?php esc_html_e('Seamless Integration.', 'software-agency'); ?></p>
		    		<p><?php esc_html_e('Customization Flexibility.', 'software-agency'); ?></p>
		    		<p><?php esc_html_e('Regular Updates.', 'software-agency'); ?></p>
		    		<p><?php esc_html_e('Dedicated Support.', 'software-agency'); ?></p>
		    	</div>
		    	<p><?php esc_html_e('Upgrade now and give your website the professional edge it deserves, all at an unbeatable price of $99!', 'software-agency'); ?></p>
		    	<div class="pro-links">
					<a href="<?php echo esc_url( SOFTWARE_AGENCY_THEME_BUNDLE_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Buy Now', 'software-agency'); ?></a>
					<a href="<?php echo esc_url( SOFTWARE_AGENCY_THEME_BUNDLE_DOC ); ?>" target="_blank"><?php esc_html_e('Documentation', 'software-agency'); ?></a>
				</div>
		   </div>
		   <div class="col-right-pro">
		    	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/bundle.png" alt="" />
		   </div>		    
		</div>
			
	</div>
</div>

<?php } ?>